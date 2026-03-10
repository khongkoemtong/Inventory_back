<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    /**
     * ១. ទាញយកទិន្នន័យ (Read All)
     * Admin ឃើញតែរបស់ខ្លួនឯង, SuperAdmin ឃើញទាំងអស់
     */
  public function read(Request $request) 
{
    $user = $request->user();

    // បើជា SuperAdmin ឱ្យឃើញទាំងអស់
    if ($user->role->name === 'SuperAdmin') {
        $products = ProductModel::with(['category', 'supplier'])->latest()->get();
    } else {
        // បើជា Admin ឃើញតែរបស់ខ្លួនឯង
        $products = ProductModel::where('seller_id', $user->id)
                    ->with(['category', 'supplier'])
                    ->latest()->get();
    }

    return response()->json(['status' => 'success', 'data' => $products]);
}

    /**
     * ២. បង្កើតផលិតផលថ្មី
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric',
                'stock_qty'   => 'required|integer',
                'category_id' => 'required|integer',
                'supplier_id' => 'required|integer',
                'image_url'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $imagePath = null;
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $path = $file->store('products', 'public');
                $imagePath = url('storage/' . $path);
            }

            $product = ProductModel::create([
                'seller_id'   => $request->user()->id, // Admin ណាបង្កើត ជាប់ ID ម្នាក់ហ្នឹង
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'stock_qty'   => $request->stock_qty,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'image_url'   => $imagePath,
                'status'      => $request->status ?? 'in stock',
            ]);

            return response()->json(['message' => 'បង្កើតផលិតផលបានជោគជ័យ', 'data' => $product], 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ៣. កែប្រែផលិតផល
     */
    public function update(Request $request, $id)
    {
        $product = ProductModel::find($id);
        if (!$product) return response()->json(['message' => 'រកមិនឃើញផលិតផល'], 404);

        // ឆែកសិទ្ធិ៖ បើមិនមែនម្ចាស់ ហើយក៏មិនមែន SuperAdmin គឺហាមកែ
        if ($product->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'អ្នកគ្មានសិទ្ធិកែប្រែផលិតផលរបស់អ្នកដទៃទេ'], 403);
        }

        $data = $request->all();
        
        // គ្រប់គ្រងរូបភាពចាស់ បើមានការដូររូបថ្មី
        if ($request->hasFile('image_url')) {
            if ($product->image_url) {
                $oldPath = str_replace(url('storage/'), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = url('storage/' . $path);
        }

        $product->update($data);
        return response()->json(['message' => 'កែប្រែបានជោគជ័យ', 'data' => $product]);
    }

    /**
     * ៤. លុបផលិតផល
     */
    public function delete(Request $request, $id)
    {
        $product = ProductModel::find($id);
        if (!$product) return response()->json(['message' => 'រកមិនឃើញផលិតផល'], 404);

        // ឆែកសិទ្ធិ៖ បើមិនមែនម្ចាស់ ហើយក៏មិនមែន SuperAdmin គឺហាមលុប
        if ($product->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'អ្នកគ្មានសិទ្ធិលុបផលិតផលនេះទេ'], 403);
        }

        if ($product->image_url) {
            $path = str_replace(url('storage/'), '', $product->image_url);
            Storage::disk('public')->delete($path);
        }

        $product->delete();
        return response()->json(['message' => 'លុបផលិតផលបានជោគជ័យ']);
    }

    /**
     * ៥. មើលផលិតផលមួយ
     */
    public function readOne($id)
    {
        $product = ProductModel::with(['category', 'supplier'])->find($id);
        if (!$product) return response()->json(['message' => 'រកមិនឃើញផលិតផល'], 404);
        
        return response()->json(['data' => $product]);
    }
}