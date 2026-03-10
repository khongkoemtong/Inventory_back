<?php

namespace App\Http\Controllers;

use App\Models\SupplyerModel;
use Illuminate\Http\Request;

class SuppyerController extends Controller
{
    // ១. កន្លែងបង្កើត (Create) - ថែម Field ដែលខ្វះ
    public function create(Request $request)
    {
        $user = $request->user();

        // គួរថែម Validation ដើម្បីកុំឱ្យបុក Error SQL ទៀត
        $request->validate([
            'name' => 'required',
            'contact_name' => 'required', // ថែមនេះដើម្បីកុំឱ្យជាប់ error 1364
            'phone' => 'required',
        ]);

        $supplier = SupplyerModel::create([
            'name'         => $request->name,
            'contact_name' => $request->contact_name, // បញ្ចូលឈ្មោះអ្នកទំនាក់ទំនង
            'email'        => $request->email,        // បញ្ចូល email
            'phone'        => $request->phone,
            'address'      => $request->address,
            'seller_id'    => $user->id, 
        ]);

        return response()->json(['message' => 'Supplier created!', 'data' => $supplier], 201);
    }

    // ២. កន្លែងទាញយកទិន្នន័យ (Read)
    public function read(Request $request)
    {
        $user = $request->user();

        if ($user->role_id == 4) {
            $suppliers = SupplyerModel::all();
        } else {
            // Admin ឃើញតែ Supplier របស់ខ្លួនឯង
            $suppliers = SupplyerModel::where('seller_id', $user->id)->get();
        }

        return response()->json($suppliers);
    }

    // ៣. កន្លែងកែប្រែ (Update)
    public function update(Request $request, $id)
    {
        $supplier = SupplyerModel::find($id);

        if (!$supplier) {
            return response()->json(['message' => 'Cannot find supplier'], 404);
        }

        $supplier->update([
            'name'         => $request->name,
            'contact_name' => $request->contact_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            // មិនបាច់ update seller_id ទេ ព្រោះម្ចាស់នៅដដែល
        ]);

        return response()->json(['message' => 'Update data success']);
    }

    // ៤. កន្លែងលុប (Delete)
    public function delete($id)
    {
        $deleteData = SupplyerModel::destroy($id);

        if (!$deleteData) {
            return response()->json(['message' => 'Cannot delete data'], 400);
        }

        return response()->json(['message' => 'Delete data success']);
    }

    // ៥. ទាញយកទិន្នន័យតែមួយ (Fetch One)
    public function fetchone($id)
    {
        $fetchData = SupplyerModel::find($id);

        if (!$fetchData) {
            return response()->json(['message' => 'Cannot find data'], 404);
        }

        return response()->json(['data' => $fetchData]);
    }
}