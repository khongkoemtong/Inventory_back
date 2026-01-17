<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(Request $request){
         $imagePath = null;
        if ($request->hasFile('image')) {
              $path = $request->file('image')->store('users', 'public');
            $imagePath = asset('http://127.0.0.1:8000/storage/' . $path); // full URL
        }

        $product = ProductModel::create([
            'seller_id'=>$request->seller_id,
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'stock'=>$request->stock,
            'stock_qty'=>$request->stock_qty,
            'category_id'=>$request->category_id,
            'supplier_id'=>$request->supplyer_id,
            'image_url'=>$imagePath,
            'status'=>$request->status,

        ]);

        if(!$product){
            return response()->json([
                'message'=>'can not create product ',
            ]);
        }



        return response()->json([
            'message'=>'create product successfully'

        ]);

    }

    public function read(){
        $product = ProductModel::all();
        if (!$product){
            return response()->json([
                'message'=>'product not found !'
            ]);
        }

        return response()->json([
            'message'=>$product
        ]);
    }

    public function update(Request $request ,$id){
        $findProduct = ProductModel::find($id);

        if (!$findProduct){
            return response()->json([
                'message'=>'can not find product !'
            ]);
        }
         $imagePath = null;
        if ($request->hasFile('image')) {
              $path = $request->file('image')->store('users', 'public');
            $imagePath = asset('http://127.0.0.1:8000/storage/' . $path); // full URL
        }

        $UpdateProduct = $findProduct->update([
             'seller_id'=>$request->seller_id,
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'stock'=>$request->stock,
            'stock_qty'=>$request->stock_qty,
            'category_id'=>$request->category_id,
            'supplier_id'=>$request->supplyer_id,
            'image_url'=>$imagePath,
            'status'=>$request->status,

        ]);

        if (!$UpdateProduct){
            return  response()->json([
                'message'=>'can not update product',
            ]);
        }

        return response()->json([
            'message'=>'update product successfully!'
        ]);
    }

    public function delete($id){
        $DeletProduct = ProductModel::destroy($id);

        if (!$DeletProduct){
            return response()->json([
                'message'=>'can not delete product ',
            ]);
        }
        return response()->json([
            'message'=>'delete product successfully'
        ]);
    }
}
