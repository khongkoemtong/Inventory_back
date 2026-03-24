<?php

namespace App\Http\Controllers;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function readall()
    {
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

        // ✅ Validate first (IMPORTANT)
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'items' => 'required|array',
            'total_amount' => 'required'
        ]);

        $user = Auth::guard('sanctum')->user();

        // ✅ Always prioritize request data
        $customerName = $request->customer_name ?? ($user->name ?? 'Unknown');
        $phone = $request->phone ?? ($user->phone ?? 'N/A');

        // ✅ Create order
        $order = OrderModel::create([
            'user_id'       => $user?->id,
            'total_amount'  => $request->total_amount,
            'status'        => 'pending',
            'order_date'    => now(),
            'customer_name' => $customerName,
            'phone'         => $phone,
        ]);

        $itemsDetail = "";

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

                $itemsDetail .= "🔹 {$product->name} (x{$item['quantity']}) - \$" 
                    . ($item['price'] * $item['quantity']) . "\n";
            }
        }

        // ✅ Send Telegram (AFTER everything saved)
        $this->sendToTelegram($order, $itemsDetail);

        return response()->json([
            'status' => 'success',
            'message' => 'Order Created Successfully',
            'data' => $order
        ], 201);
    });
}

   private function sendToTelegram($order, $itemsDetail)
{
    $token = env('TELEGRAM_BOT_TOKEN');
    $chatId = env('TELEGRAM_CHAT_ID');

    // ✅ Detect user from order
    $type = $order->user_id ? "🏠 សមាជិក (Online)" : "🛒 ភ្ញៀវ";

    // ✅ Safe fallback (important)
    $name = $order->customer_name ?: "មិនស្គាល់ឈ្មោះ";
    $phone = $order->phone ?: "គ្មានលេខទូរស័ព្ទ";

    $message = "📦 *មានការកម្ម៉ង់ថ្មី!*\n\n"
        . "📍 ប្រភេទ: *{$type}*\n"
        . "👤 ឈ្មោះ: *{$name}*\n"
        . "📞 លេខ: *{$phone}*\n"
        . "💰 សរុប: *\${$order->total_amount}*\n"
        . "---------------------------\n"
        . "🛒 *ទំនិញ:*\n"
        . $itemsDetail . "\n"
        . "⏰ " . now();

    try {
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    } catch (\Exception $e) {
        \Log::error('Telegram Error: ' . $e->getMessage());
    }
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
