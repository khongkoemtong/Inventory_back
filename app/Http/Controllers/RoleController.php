<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function create(Request $request){
        $user =RoleModel::create([
            'name'=>$request->name,
        ]);

        if (!$user){
            return response()->json([
                'message'=>'can not create role '
            ]);
        }

        return response()->json([
            'message'=>'create role success'
        ]);


    }

    public function read(){
        $role =RoleModel::all();
        if (!$role){
            return response()->json([
                'message'=>'can not find role'
            ]);
        }

        return response()->json([
            'message'=>$role
        ]);
    }

    public function update(Request $request,$id){
        $FindRole = RoleModel::find($id);

        if (!$FindRole){
            return response()->json([
                'message'=>'can not find role '
            ]);
        }

        $updateRole = $FindRole->update([
            'name'=>$request->name
        ]); 

        if (!$updateRole){
            return response()->json([
                'message'=>'can not update data'
            ]);
        }

        return response()->json([
            'message'=>'update data success'
        ]);
    }

    public function delete($id){
        $findRole = RoleModel::destroy($id);

        if (!$findRole){
            return response()->json([
                'message'=>'can not delete role '
            ]);
        }

        return response()->json([
            'message'=>'delete role success'
        ]);

        

    }
    public function fetchone ($id){
        $fetchRole = RoleModel::findOrFail($id);
    }
}
