<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleCategoryController extends Controller
{
    public function index()
    {
        $categories = ArticleCategory::withCount('articles')->latest()->get();
        return view('admin.article-categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.article-categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:article_categories',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        ArticleCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori artikel berhasil ditambahkan!');
    }
    
    public function show(ArticleCategory $articleCategory)
    {
        $articleCategory->load(['articles']);
        $category = $articleCategory;
        return view('admin.article-categories.show', compact('category'));
    }
    
    public function edit(ArticleCategory $articleCategory)
    {
        $category = $articleCategory;
        return view('admin.article-categories.edit', compact('category'));
    }
    
    public function update(Request $request, ArticleCategory $articleCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:article_categories,name,' . $articleCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $articleCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', false),
        ]);
        
        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori artikel berhasil diperbarui!');
    }
    
    public function destroy(ArticleCategory $articleCategory)
    {
        if ($articleCategory->articles()->count() > 0) {
            return redirect()->route('admin.article-categories.index')->with('error', 'Tidak dapat menghapus kategori yang masih memiliki artikel!');
        }
        
        $articleCategory->delete();
        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori artikel berhasil dihapus!');
    }
}