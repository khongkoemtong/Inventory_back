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
            'message' => 'Cannot find ID = ' . $id
        ], 404);
    }

    // ១. បង្កើត Array សម្រាប់ផ្ទុកទិន្នន័យដែលចង់ Update
    // fill() នឹងយកតែទិន្នន័យណាដែលមានផ្ញើមកពី Request (Ignore តម្លៃដែលអត់មាន)
    $data = $request->only([
        'username', 'email', 'full_name', 'role_id', 'phone', 'status'
    ]);

    // ២. ត្រួតពិនិត្យ Password (Update តែពេលមានផ្ញើមកថ្មីប៉ុណ្ណោះ)
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    // ៣. ត្រួតពិនិត្យរូបភាព
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('users', 'public');
        // ប្តូរមកប្រើ storage_url បែបនេះវិញស្រួលជាង
        $data['image'] = url('storage/' . $path); 
    }

    // ៤. ធ្វើការ Update តែ Column ណាដែលមានក្នុង $data
    $update = $user->update($data);

    if (!$update) {
        return response()->json([
            'message' => 'Cannot update data!',
        ], 500);
    }

    return response()->json([
        'message' => 'Update data successfully',
        'user' => $user // បញ្ជូន data ថ្មីទៅឱ្យ Frontend វិញ
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
