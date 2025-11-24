<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use Illuminate\Http\Request;
use App\Models\InvestmentTypes;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvestmentTypeController extends Controller
{
    /**
     * Show investment types page
     */
    public function index(Request $request)
    {
        return view("backend.layouts.investment.investment_type");
    }

    /**
     * Get all investment types ordered by 'order' column
     */
    public function getAllClasses()
    {
        try {
            $types = InvestmentTypes::orderBy('order', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load data'
            ], 500);
        }
    }

    /**
     * Store new investment type
     */
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:30000'
            ]);

            // Get the max order and add 1
            $maxOrder = InvestmentTypes::max('order') ?? 0;
            $validated_data['order'] = $maxOrder + 1;

            InvestmentTypes::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Type added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating type!',
            ], 500);
        }
    }

    /**
     * Update investment type
     */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:30000'
            ]);

            $type = InvestmentTypes::findOrFail($id);
            $type->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Type updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating type!',
            ], 500);
        }
    }

    /**
     * Update order of investment types
     */
    public function updateOrder(Request $request)
    {
        try {
            $orderData = $request->order_data;

            DB::beginTransaction();

            foreach ($orderData as $item) {
                InvestmentTypes::where('id', $item['id'])
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
     * Delete investment type
     */
    public function destroy($id)
    {
        try {
            $item = InvestmentTypes::findOrFail($id);
            $deletedOrder = $item->order;

            $item->delete();

            // Reorder remaining items
            InvestmentTypes::where('order', '>', $deletedOrder)
                ->decrement('order');

            return response()->json([
                'success' => true,
                'message' => 'Type deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting type'
            ], 500);
        }
    }
}
