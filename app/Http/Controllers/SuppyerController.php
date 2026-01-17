<?php

namespace App\Http\Controllers;

use App\Models\SupplyerModel;
use Illuminate\Http\Request;

class SuppyerController extends Controller
{
    public function create(Request $request){
        $supplyer = SupplyerModel::create([
            'name'=>$request->name,
            'contact_name'=>$request->contact_name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'address'=>$request->address,
        ]);

        if (!$supplyer){
            return response()->json([
                'message'=>'can not create data ',
            ]);
        }

        return response()->json([
            'message'=>'creatae table success ',

        ]);


        

    }

    public function read(){
        $user = SupplyerModel::all();


        if (!$user){
            return response ()->json([
                'message'=>' Supplyer not found ',
            ]);
        }

        return response()->json([
            'message'=>$user
        ]);

    }

    public function update(Request $request ,$id){
        $user = SupplyerModel::find($id);

        if (!$user){
            return response()->json([
                'message'=>'can not find user ',
            ]);
        }

        $userUpdate = $user->update([
            'name'=>$request->name,
            'contact_name'=>$request->contact_name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'address'=>$request->address,
        ]);

        if (!$userUpdate){
            return response()->json([
                'message'=>'can not update data ',
            ]);
        }

        return response()->json([
            'message'=>'update data success',
        ]);


    }

    public function delete($id){
        $deleteData = SupplyerModel::destroy($id);

        if (!$deleteData){
            return response()->json([
                'message'=>'can not delete data'
            ]);
        }

        return response()->json([
            'message'=>'update data success',
        ]);
    }
    public function fetchone ($id){
        $fetchData = SupplyerModel::find($id);

        if (!$fetchData){
            return response()->json([
                'message'=>'can not find data',
            ]);
        }

        return response()->json([
            'Message'=>$fetchData

        ]);

    }
}
