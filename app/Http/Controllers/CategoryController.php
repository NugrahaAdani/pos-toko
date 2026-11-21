<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return response()->view('categories.index', compact('categories'));
    }

    public function create(): Response
    {
        return response()->view('categories.create');
    }

    public function store(StoreCategoryRequest $request): Response
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Category $category): Response
    {
        $category->load('products');
        $totalStok = $category->products->sum('stock');

        return response()->view('categories.show', compact('category', 'totalStok'));
    }

    public function edit(Category $category): Response
    {
        return response()->view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): Response
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): Response
    {
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
