<?php

namespace App\Http\Controllers;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function readall() {
        $orders = OrderModel::with('user')->latest()->get(); 
        
        return response()->json([
            'status' => 'success',
            'data'   => $orders
        ], 200);
    }

    public function read($id)
    {
        $myorder = OrderModel::with(['user', 'items.product'])->find($id);
        if (!$myorder) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        // ប្រើ Key 'data' ដូចគ្នា
        return response()->json([
            'status' => 'success',
            'data'   => $myorder
        ], 200);
    }

    public function create(Request $request)
    {

        return DB::transaction(function () use ($request) {
            try {
                $order = OrderModel::create([
                    'user_id'      => $request->user_id,
                    'total_amount' => $request->total_amount,
                    'status'       => 'pending',
                    'order_date'   => now(),
                ]);

                foreach ($request->items as $item) {
                    OrderItemModel::create([
                        'order_id'   => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);

                    $product = ProductModel::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock_qty', $item['quantity']);
                    }
                }

                return response()->json([
                    'message' => 'Order Created Successfully',
                    'data'    => $order // បន្ថែមទិន្នន័យ order ត្រឡប់ទៅវិញ
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
            }
        });
    }

    // ៤. កែប្រែទិន្នន័យ
    public function update(Request $request, $id)
    {
        $order = OrderModel::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->update($request->all());
        return response()->json(['message' => 'Updated Successfully', 'data' => $order]);
    }

    // ៥. លុបទិន្នន័យ
    public function delete($id)
    {
        $order = OrderModel::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}