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
        // មានតែ Super Admin ទេដែលត្រូវបានអនុញ្ញាតឱ្យចូលដល់ Route នេះ (តាមរយៈ Middleware)
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = UserModel::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2, // កំណត់ឱ្យទៅជា Admin ធម្មតា
            'status'   => 1,
        ]);

        return response()->json(['message' => 'បង្កើត Admin ថ្មីជោគជ័យ!'], 201);
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

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ១. ស្វែងរក User
        $user = UserModel::where('username', $request->username)->first();

        // ២. ផ្ទៀងផ្ទាត់ Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'ឈ្មោះអ្នកប្រើ ឬលេខសម្ងាត់មិនត្រឹមត្រូវ'], 401);
        }

        // ៣. បង្កើត Token (Sanctum)
        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'token' => $token,
            'user' => $user->load('role') // បង្ហាញ Role មកជាមួយ
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout Success']);
    }
}
