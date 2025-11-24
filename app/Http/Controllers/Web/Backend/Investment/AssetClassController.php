<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use App\Models\AssetClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AssetClassController extends Controller
{
    /**
     * Show asset class page
     */
    public function index()
    {
        return view("backend.layouts.investment.asset_class");
    }

    /**
     * Get all asset classes ordered by 'order' column
     */
    public function getAllClasses()
    {
        try {
            $classes = AssetClass::orderBy('order', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load data'
            ], 500);
        }
    }

    /**
     * Store new asset class
     */
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'asset_type' => 'nullable|string',
                'description' => 'nullable|string|max:30000'
            ]);

            // Get the max order and add 1
            $maxOrder = AssetClass::max('order') ?? 0;
            $validated_data['order'] = $maxOrder + 1;

            AssetClass::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Asset class added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating asset class!',
            ], 500);
        }
    }

    /**
     * Update asset class
     */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'asset_type' => 'nullable|string',
                'description' => 'nullable|string|max:30000'
            ]);

            $class = AssetClass::findOrFail($id);
            $class->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Asset class updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating asset class!',
            ], 500);
        }
    }

    /**
     * Update order of asset classes
     */
    public function updateOrder(Request $request)
    {
        try {
            $orderData = $request->order_data;

            DB::beginTransaction();

            foreach ($orderData as $item) {
                AssetClass::where('id', $item['id'])
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
     * Delete asset class
     */
    public function destroy($id)
    {
        try {
            $item = AssetClass::findOrFail($id);
            $deletedOrder = $item->order;

            $item->delete();

            // Reorder remaining items
            AssetClass::where('order', '>', $deletedOrder)
                ->decrement('order');

            return response()->json([
                'success' => true,
                'message' => 'Asset class deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting asset class'
            ], 500);
        }
    }
}
