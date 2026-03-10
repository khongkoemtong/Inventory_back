<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    /**
     * ១. បង្កើត Category ថ្មី (ប្អូនខ្វះកន្លែងនេះមុនហ្នឹង)
     */
   /**
     * ១. បង្កើត Category ថ្មី (Fixed for Image Upload)
     */
   // កន្លែងបង្កើត (Create)
public function create(Request $request)
{
    $user = $request->user();

    $category = CategoryModel::create([
        'name'      => $request->name,
        'seller_id' => $user->id, // បញ្ជាក់ថា Category នេះជារបស់ Admin ណា
    ]);

    return response()->json(['message' => 'Category created!', 'data' => $category], 201);
}

public function read(Request $request)
{
    $user = $request->user();

    if ($user->role_id == 4) {
        $categories = CategoryModel::with('products')->get();
    } else {
        $categories = CategoryModel::with('products')
            ->where('seller_id', $user->id)
            ->get();
    }

    return response()->json($categories);
}
    
    public function fetchone($id)
    {
        $category = CategoryModel::with('products')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Data not found !'], 404);
        }

        return response()->json($category);
    }

    /**
     * ៤. កែប្រែ Category
     */
    public function update(Request $request, $id)
    {
        $category = CategoryModel::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // ឆែកសិទ្ធិ៖ បើមិនមែនម្ចាស់ ហើយមិនមែន SuperAdmin គឺហាមកែ
        if ($category->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'No permission to update this category'], 403);
        }

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            // លុបរូបចាស់ចេញបើមាន
            if ($category->image) {
                $oldPath = str_replace(asset('storage/'), '', $category->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = asset('storage/' . $path);
        }

        $category->update($data);

        return response()->json([
            'message' => 'Update success',
            'data' => $category
        ]);
    }

    /**
     * ៥. លុប Category
     */
    public function delete(Request $request, $id)
    {
        $category = CategoryModel::find($id);
        if (!$category) return response()->json(['message' => 'រកមិនឃើញ'], 404);

        // ឆែកសិទ្ធិ៖ បើមិនមែនម្ចាស់ ហើយមិនមែន SuperAdmin គឺហាមលុប
        if ($category->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'គ្មានសិទ្ធិលុបប្រភេទផលិតផលអ្នកដទៃទេ'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'លុបជោគជ័យ!']);
    }
}