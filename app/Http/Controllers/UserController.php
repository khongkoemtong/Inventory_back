<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
              $path = $request->file('image')->store('users', 'public');
            $imagePath = asset('http://127.0.0.1:8000/storage/' . $path); // full URL
        }
        $user = UserModel::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'status' => $request->status,
            'image' => $imagePath,

        ]);

        if (!$user) {
            return response()->json(["message" => "can not insert data"]);
        }
        return response()->json(['message' => 'Insert data success']);
    }
    public function read()
    {
        $user = UserModel::all();
        if (!$user) {
            return response()->json(['message' => 'can not find data']);
        }
        return response()->json(
            [
                'message' => $user
            ]
        );
        
    }
    public function readone($id)
    {
        $user = UserModel::findOrFail($id);

        if (!$user) {
            return response()->json([
                'message' => 'can not find data in id =',
                $id
            ]);
        }
        return response()->json([
            "message" => $user
        ]);
    }
    public function delete($id)
    {

        $delete = UserModel::destroy($id);
        if (!$delete) {
            return response()->json([
                'message' => 'can not delete user ',
                $id
            ]);
        }

        return  response()->json([
            'message' => 'delete successfully ',
        ]);
    }
    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'can not find id = ',
                $id
            ]);
        }



        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('users', 'public');
            $imagePath = asset('http://127.0.0.1:8000/storage/' . $path); // full URL
        }
        $update = $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'full_name' => $request->full_name,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        if (!$update) {
            return response()->json([
                'message' => 'can not update data !',

            ]);
        }

        return response()->json([
            'message' => 'update data successfully',
        ]);
    }

    public function show($id)
    {
        $FindUser = UserModel::with('role')->find($id);
        if (!$FindUser) {
            return  response()->json(['message' => 'can not find data in id = ', $id]);
        }
        return response()->json(['message' => 'successfullt ', 'result' => $FindUser]);
    }

    public function login (Request $request){

      $login = $request->validate([
        'username'=>'required|string',
        'email'=>'required|string|email',
        'password'=>'required|string|min:4'
      ]);

     $user =UserModel::where('email',$login['email'])->first();

     if (!$user || !Hash::check($login['password'],$user->password)){
        return response()->json([
            'message'=>'wrong email and password  '
        ],401);
     }

     $token =$user ->createToken('myapptoken')->plainTextToken;

     return response()->json([
        'user'=>$user,
        'token'=>$token,
     ]);

    
    }
}
