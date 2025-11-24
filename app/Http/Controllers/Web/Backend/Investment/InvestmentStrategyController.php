<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use Illuminate\Http\Request;
use App\Models\InvestmentStrategy;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvestmentStrategyController extends Controller
{
    /**
     * Show investment strategy page
     */
    public function index()
    {
        return view("backend.layouts.investment.investment_strategy");
    }

    /**
     * Get all investment strategies ordered by 'order' column
     */
    public function getAllStrategies()
    {
        try {
            $strategies = InvestmentStrategy::orderBy('order', 'ASC')
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
     * Store new investment strategy
     */
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:30000'
            ]);

            // Get the max order and add 1
            $maxOrder = InvestmentStrategy::max('order') ?? 0;
            $validated_data['order'] = $maxOrder + 1;

            InvestmentStrategy::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Strategy added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating strategy!',
            ], 500);
        }
    }

    /**
     * Update investment strategy
     */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:30000'
            ]);

            $strategy = InvestmentStrategy::findOrFail($id);
            $strategy->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Strategy updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating strategy!',
            ], 500);
        }
    }

    /**
     * Update order of investment strategies
     */
    public function updateOrder(Request $request)
    {
        try {
            $orderData = $request->order_data;

            DB::beginTransaction();

            foreach ($orderData as $item) {
                InvestmentStrategy::where('id', $item['id'])
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
     * Delete investment strategy
     */
    public function destroy($id)
    {
        try {
            $item = InvestmentStrategy::findOrFail($id);
            $deletedOrder = $item->order;

            $item->delete();

            // Reorder remaining items
            InvestmentStrategy::where('order', '>', $deletedOrder)
                ->decrement('order');

            return response()->json([
                'success' => true,
                'message' => 'Strategy deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting strategy'
            ], 500);
        }
    }
}
