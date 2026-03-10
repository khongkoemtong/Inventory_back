<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function store(Request $request) {
        // មានតែ Super Admin ទេដែលចូលមកដល់កន្លែងនេះបាន
        UserModel::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 2, // កំណត់ឱ្យទៅជា Admin តែម្ដង
            'status' => 1
        ]);
        return back()->with('success', 'បង្កើត Admin ថ្មីជោគជ័យ!');
    }
}
