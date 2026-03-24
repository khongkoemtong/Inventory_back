<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Added missing import
use Illuminate\Support\Facades\DB;        // Added for database safety

class ProductController extends Controller
{
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock_qty'   => 'required|integer',
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'image_url'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image_url')) {
                // 1. Store the file
                $path = $request->file('image_url')->store('products', 'public');
                $imagePath = '/storage/' . $path;
            }

            $product = ProductModel::create([
                'seller_id'   => $request->user()->id,
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'stock_qty'   => $request->stock_qty,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'image_url'   => $imagePath, // Now consistent with Categories
                'status'      => $request->status ?? 'in stock',
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'បង្កើតផលិតផលបានជោគជ័យ',
                'data'    => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = ProductModel::find($id);
        if (!$product) return response()->json(['message' => 'រកមិនឃើញ'], 404);

        if ($product->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'No permission'], 403);
        }

        $data = $request->except(['image_url', 'seller_id']);

        if ($request->hasFile('image_url')) {
            // Delete old file
            if ($product->image_url) {
                // Clean path: remove '/storage/' to get the actual disk path
                $oldPath = str_replace('/storage/', '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Upload new and format consistently
            $path = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product->update($data);
        
        return response()->json(['status' => 'success', 'data' => $product]);
    }

    /**
     * 3. Delete Product
     */
    public function delete(Request $request, $id)
    {
        $product = ProductModel::find($id);
        if (!$product) {
            return response()->json(['message' => 'រកមិនឃើញផលិតផល'], 404);
        }

        if ($product->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'អ្នកគ្មានសិទ្ធិលុបផលិតផលនេះទេ'], 403);
        }

        // Remove file from Storage
        if ($product->image_url) {
            $path = str_replace('/storage/', '', $product->image_url);
            Storage::disk('public')->delete($path);
        }

        $product->delete();
        
        return response()->json([
            'status'  => 'success',
            'message' => 'លុបផលិតផល និងរូបភាពបានជោគជ័យ'
        ]);
    }

    /**
     * 4. Read All
     */
    public function read(Request $request) 
    {
        $user = $request->user();
        $query = ProductModel::with(['category', 'supplier'])->latest();

        // If not Admin, only show products owned by the user
        if ($user->role->name !== 'SuperAdmin') {
            $query->where('seller_id', $user->id);
        }

        return response()->json([
            'status' => 'success', 
            'data'   => $query->get()
        ]);
    }

    /**
     * 5. Read One
     */
    public function readOne($id)
    {
        $product = ProductModel::with(['category', 'supplier'])->find($id);
        if (!$product) {
            return response()->json(['message' => 'រកមិនឃើញផលិតផល'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $product
        ]);
    }
}