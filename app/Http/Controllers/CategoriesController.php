<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function Create(Request $request){

     $imagePath = null;
        if ($request->hasFile('image')) {
              $path = $request->file('image')->store('users', 'public');
            $imagePath = asset('http://127.0.0.1:8000/storage/' . $path); // full URL
        }
        $user = CategoryModel::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'image'=>$imagePath            
        ]);

        if (!$user){
            return response()->json([
                'Message'=>'can not insert in to categories',
            ]);
        }

        return response()->json([
            'message'=>'insert success ',
            'data'=>$user
        ]);


    }

    public  function read (){
        $AllCagory = CategoryModel::all(); 

        if (!$AllCagory){
            return response()->json([
                'message'=>'Category not found !'
            ]);

        }
        return response()->json([
            'message'=>$AllCagory
        ]);
    }

    public  function fetchone($id){
        $OneCategory = CategoryModel::findOrFail($id);

        if (!$OneCategory){
            return response()->json([
                'message'=>'Data not found !'
            ]);
        }

        return response()->json([
            'message'=>$OneCategory
        ]);

    }


    public function delete($id){
        $DeleteUser = CategoryModel::destroy($id);


        if (!$DeleteUser){
            return response()->json([
                'message'=>"can not delete user "
            ]);
        }

        return response()->json([
            'message'=>'delete data success !',
        ]);

    }

    public function update(Request $request,$id){
        $updateUser = CategoryModel::find($id);
       
        if (!$updateUser){
            return response()->json([
                'massage'=>'can not found user ',
            ]);
        }

          $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('users', 'public');
            $imagePath = asset('http://127.0.0.1:8000/storage/' . $path); // full URL
        }

         $user =$updateUser->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'image'=>$imagePath            
        ]);

        if (!$user){
            return response()->json([
                'message'=>'can not delete data',

            ]);
        }

        return response()->json([
            'message'=>'update data success ',$user
        ]);
    }
    


}
