<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\RoleModel; // ប្រើតែ RoleModel ឱ្យស្របតាម database
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller 
{ 
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string|unique:users,username',
            'full_name' => 'required|string',
            'email'     => 'required|string|email|unique:users,email',
            'password'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserModel::create([
            'username'  => $request->username,
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => 2, // Default ជា User
            'status'    => 1,
        ]);
        
        return response()->json(['message' => 'Register Success', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserModel::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid username or password.'], 401);
        }

        if ($user->status != 1) {
            return response()->json(['message' => 'Your account is disabled.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login Success',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user->load('role') // ត្រូវប្រាកដថាមាន function role() ក្នុង UserModel
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ចាកចេញពីប្រព័ន្ធជោគជ័យ']);
    }
}