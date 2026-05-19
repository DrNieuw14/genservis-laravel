<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    // 📋 List Categories
    public function index()
    {
        $categories = Category::withCount('materials')
            ->latest()
            ->get();

        return view('supervisor.categories.index', compact('categories'));
    }

    // ➕ Show Create Form
    public function create()
    {
        return view('supervisor.categories.create');
    }

    // 💾 Store Category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category added successfully!');
    }

    // ✏️ Edit Form
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('supervisor.categories.edit', compact('category'));
    }

    // 💾 Update Category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    // 🗑 Delete Category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // ❌ Prevent delete if category has materials
        if ($category->materials()->count() > 0) {
            return back()->with(
                'error',
                'Cannot delete category with existing materials.'
            );
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}