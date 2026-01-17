<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;


class OrderController extends Controller
{   
    public function read ($id){
       $myorder = OrderModel::with('user')->find($id);

       if (!$myorder){
        return response()->json(['Message'=>'can not find order in id =',$id]);
       }

       return response()->json(['Message'=>'Success','Data'=>$myorder]);
       
    }
}
