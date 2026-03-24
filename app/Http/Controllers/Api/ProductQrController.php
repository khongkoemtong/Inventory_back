<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductQrController extends Controller
{
    public function getUserProducts($userId)
    {
        $products = DB::table('products')
            ->where('seller_id', $userId)
            ->select('id', 'name', 'price', 'stock_qty', 'image_url', 'status')
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'រកមិនឃើញផលិតផល'], 404);
        }

        // ប្រើ IP ដែលអ្នកឆែកឃើញក្នុង Debian (hostname -I)
        $my_computer_ip = "192.168.0.152"; 
        
        // ប្រើ Port 5173 ព្រោះអ្នកប្រើ Vite/React
        $frontendUrl = "http://{$my_computer_ip}:5173/inventory/{$userId}";

        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($frontendUrl);

        $base64Qr = 'data:image/png;base64,' . base64_encode($qrCode);

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'qr_code' => $base64Qr,
            'products' => $products
        ]);
    }
}