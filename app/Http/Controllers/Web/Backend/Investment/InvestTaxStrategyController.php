<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use App\Models\TaxStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvestTaxStrategyController extends Controller
{
    /**
     * Show tax strategy page
     */
    public function index()
    {
        return view("backend.layouts.investment.tax_strategy");
    }

    /**
     * Get all tax strategies ordered by 'order' column
     */
    public function getAllTaxStrategies()
    {
        try {
            $strategies = TaxStrategy::orderBy('order', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $strategies
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load data'
            ], 500);
        }
    }

    /**
     * Store new tax strategy
     */
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:30000'
            ]);

            // Get the max order and add 1
            $maxOrder = TaxStrategy::max('order') ?? 0;
            $validated_data['order'] = $maxOrder + 1;

            TaxStrategy::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Tax strategy added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating tax strategy!',
            ], 500);
        }
    }

    /**
     * Update tax strategy
     */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:30000'
            ]);

            $strategy = TaxStrategy::findOrFail($id);
            $strategy->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Tax strategy updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating tax strategy!',
            ], 500);
        }
    }

    /**
     * Update order of tax strategies
     */
    public function updateOrder(Request $request)
    {
        try {
            $orderData = $request->order_data;

            DB::beginTransaction();

            foreach ($orderData as $item) {
                TaxStrategy::where('id', $item['id'])
                    ->update(['order' => $item['order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order!'
            ], 500);
        }
    }

    /**
     * Delete tax strategy
     */
    public function destroy($id)
    {
        try {
            $item = TaxStrategy::findOrFail($id);
            $deletedOrder = $item->order;

            $item->delete();

            // Reorder remaining items
            TaxStrategy::where('order', '>', $deletedOrder)
                ->decrement('order');

            return response()->json([
                'success' => true,
                'message' => 'Tax strategy deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting tax strategy'
            ], 500);
        }
    }
}
