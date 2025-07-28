<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'description' => 'nullable|max:500',
            'color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'active' => 'boolean'
        ]);

        $validated['active'] = $request->has('active');

        Category::create($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|max:500',
            'color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'active' => 'boolean'
        ]);

        $validated['active'] = $request->has('active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.categories.index')
                            ->with('error', 'No se puede eliminar una categoría que tiene posts asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Categoría eliminada exitosamente.');
    }
}
