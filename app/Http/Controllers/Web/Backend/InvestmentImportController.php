<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\AssetClass;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Models\InvestmentTypes;
use App\Models\InvestmentStrategy;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class InvestmentImportController extends Controller
{
    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('backend.layouts.excel.import');
    }

    /**
     * Import CSV/Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx,xls|max:102400', // 100MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file format. Only CSV, XLS, XLSX allowed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            // Parse file based on extension
            if ($extension === 'csv') {
                $data = $this->parseCsv($file);
            } else {
                $data = $this->parseExcel($file);
            }

            // Process and insert data
            $result = $this->processImportData($data);

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$result['success']} investments. {$result['failed']} failed.",
                'details' => $result
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCsv($file)
    {
        $data = [];
        $handle = fopen($file->getRealPath(), 'r');

        // Get headers (first row)
        $headers = fgetcsv($handle);
        $headers = array_map('trim', $headers); // Remove whitespace

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $data;
    }

    /**
     * Parse Excel file using PhpSpreadsheet
     */
    private function parseExcel($file)
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $headers = array_map('trim', array_shift($rows)); // First row as headers
        $data = [];

        foreach ($rows as $row) {
            if (count($row) === count($headers) && !empty(array_filter($row))) {
                $data[] = array_combine($headers, $row);
            }
        }

        return $data;
    }

    /**
     * Process import data and insert into database
     */
    private function processImportData($data)
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                DB::beginTransaction();

                // Map CSV columns to database columns
                $investmentData = $this->mapCsvToDatabase($row);

                // Validate required fields
                if (empty($investmentData['title'])) {
                    throw new Exception("Title is required");
                }

                // Handle relationships (Asset Class, Type, Strategy)
                $investmentData = $this->handleRelationships($investmentData);

                // Create investment
                Investment::create($investmentData);

                DB::commit();
                $successCount++;

            } catch (Exception $e) {
                DB::rollBack();
                $failedCount++;
                $errors[] = [
                    'row' => $index + 2, // +2 for header and 0-index
                    'error' => $e->getMessage(),
                    'data' => $row
                ];
            }
        }

        return [
            'success' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors
        ];
    }

    /**
     * Map CSV columns to database columns
     * Modify this based on your CSV structure
     */
    private function mapCsvToDatabase($row)
    {
        return [
            // Basic Info
            'title' => $row['Title'] ?? $row['Deal Name'] ?? $row['title'] ?? null,
            'summary' => $row['Summary'] ?? $row['Description'] ?? $row['summary'] ?? null,

            // Financial
            'term' => $row['Term'] ?? $row['term'] ?? null,
            'min_investment' => $row['Min Investment'] ?? $row['Minimum Investment'] ?? $row['min_investment'] ?? null,
            'targeted_irr' => $row['Target IRR'] ?? $row['Targeted IRR'] ?? $row['targeted_irr'] ?? null,
            'targeted_eps' => $row['Target ESP'] ?? $row['Targeted ESP'] ?? $row['targeted_eps'] ?? null,

            // Location
            'country' => $row['Country'] ?? $row['country'] ?? null,
            'state' => $row['State'] ?? $row['state'] ?? null,
            'city' => $row['City'] ?? $row['city'] ?? null,
            'address' => $row['Deal Address'] ?? $row['address'] ?? null,
            'latitude' => $row['Latitude'] ?? $row['latitude'] ?? null,
            'longitude' => $row['Longitude'] ?? $row['longitude'] ?? null,

            // Contact
            'banker_phone' => $row['Banker Phone'] ?? $row['Contact Phone'] ?? $row['banker_phone'] ?? null,
            'banker_email' => $row['Banker Email'] ?? $row['Contact Email'] ?? $row['banker_email'] ?? null,

            // Status
            'status' => strtolower($row['Status'] ?? $row['status'] ?? 'draft'),

            // Categories (will be processed separately)
            '_asset_class' => $row['Asset Class'] ?? $row['asset_class'] ?? null,
            '_investment_type' => $row['Investment Type'] ?? $row['investment_type'] ?? null,
            '_strategy' => $row['Strategy'] ?? $row['Investment Strategy'] ?? $row['strategy'] ?? null,
        ];
    }

    /**
     * Handle Asset Class, Investment Type, and Strategy relationships
     */
    private function handleRelationships($data)
    {
        // Handle Asset Class
        if (!empty($data['_asset_class'])) {
            $assetClass = AssetClass::firstOrCreate(
                ['name' => trim($data['_asset_class'])],
                ['description' => null]
            );
            $data['asset_class_id'] = $assetClass->id;
        }
        unset($data['_asset_class']);

        // Handle Investment Type
        if (!empty($data['_investment_type'])) {
            $investmentType = InvestmentTypes::firstOrCreate(
                ['name' => trim($data['_investment_type'])],
                ['description' => null]
            );
            $data['investment_type_id'] = $investmentType->id;
        }
        unset($data['_investment_type']);

        // Handle Strategy
        if (!empty($data['_strategy'])) {
            $strategy = InvestmentStrategy::firstOrCreate(
                ['name' => trim($data['_strategy'])],
                ['description' => null]
            );
            $data['investments_strategy_id'] = $strategy->id;
        }
        unset($data['_strategy']);

        return $data;
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Title',
            'Summary',
            'Asset Class',
            'Investment Type',
            'Strategy',
            'Term',
            'Min Investment',
            'Targeted IRR',
            'Targeted ESP',
            'Country',
            'State',
            'City',
            'Address',
            'Latitude',
            'Longitude',
            'Banker Phone',
            'Banker Email',
            'Status'
        ];

        $filename = 'investment_import_template_' . date('Y-m-d') . '.csv';

        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, $headers);

        // Sample row
        fputcsv($handle, [
            'Sample Investment',
            'This is a sample investment description',
            'Real Estate',
            'Equity',
            'Value-Add',
            '5 Years',
            '50000',
            '15%',
            '8%',
            'Bangladesh',
            'Dhaka',
            'Dhaka',
            'Gulshan, Dhaka',
            '23.8103',
            '90.4125',
            '+880123456789',
            'banker@example.com',
            'active'
        ]);

        fclose($handle);
        exit;
    }
}
