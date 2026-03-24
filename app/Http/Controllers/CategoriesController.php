<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * 1. Create Category
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store relative path: "categories/filename.jpg"
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = CategoryModel::create([
            'name'        => $request->name,
            'description' => $request->description ?? "",
            'image'       => $imagePath ? '/storage/' . $imagePath : null, // Store with /storage/ prefix
            'seller_id'   => $request->user()->id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Category created!',
            'data'    => $category
        ], 201);
    }

    /**
     * 2. Read All
     */
    public function read(Request $request)
    {
        $user = $request->user();

        // Use role name check instead of ID for better readability
        $query = CategoryModel::with('products');

        if ($user->role->name !== 'SuperAdmin') {
            $query->where('seller_id', $user->id);
        }

        $categories = $query->get();

        return response()->json($categories);
    }

    /**
     * 3. Update Category
     */
    public function update(Request $request, $id)
    {
        $category = CategoryModel::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        if ($category->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'No permission'], 403);
        }

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            // Delete old file
            if ($category->image) {
                // Clean the path to get only the filename for Storage::delete
                $oldPath = str_replace('/storage/', '', $category->image);
                Storage::disk('public')->delete($oldPath);
            }

            // Store new file
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $category->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Update success',
            'data'    => $category
        ]);
    }

    /**
     * 4. Delete Category
     */
    public function delete(Request $request, $id)
    {
        $category = CategoryModel::find($id);
        if (!$category) return response()->json(['message' => 'Not found'], 404);

        if ($category->seller_id !== $request->user()->id && $request->user()->role->name !== 'SuperAdmin') {
            return response()->json(['message' => 'No Permission'], 403);
        }

        // Delete the image file from physical storage
        if ($category->image) {
            $path = str_replace('/storage/', '', $category->image);
            Storage::disk('public')->delete($path);
        }

        $category->delete();
        return response()->json(['message' => 'លុបជោគជ័យ!']);
    }
}