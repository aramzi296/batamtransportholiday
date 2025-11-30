<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'author'])
            ->when(request('search'), function($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                      ->orWhere('content', 'like', '%' . request('search') . '%');
            })
            ->when(request('category'), function($query) {
                $query->where('category_id', request('category'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(10);
            
        $categories = ArticleCategory::where('is_active', true)->get();
        
        return view('admin.articles.index', compact('articles', 'categories'));
    }
    
    public function create()
    {
        $categories = ArticleCategory::where('is_active', true)->get();
        return view('admin.articles.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:article_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|url',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
        ]);
        
        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . uniqid()),
            'content' => $request->content,
            'excerpt' => $request->excerpt ?? Str::limit(strip_tags($request->content), 200),
            'category_id' => $request->category_id,
            'author_id' => auth()->id(),
            'featured_image' => $request->featured_image,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured', false),
            'tags' => $request->tags ?? [],
            'published_at' => $request->status === 'published' ? now() : null,
        ]);
        
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat!');
    }
    
    public function show(Article $article)
    {
        $article->load(['category', 'author']);
        return view('admin.articles.show', compact('article'));
    }
    
    public function edit(Article $article)
    {
        $categories = ArticleCategory::where('is_active', true)->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }
    
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:article_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|url',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
        ]);
        
        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . $article->id),
            'content' => $request->content,
            'excerpt' => $request->excerpt ?? Str::limit(strip_tags($request->content), 200),
            'category_id' => $request->category_id,
            'featured_image' => $request->featured_image,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured', false),
            'tags' => $request->tags ?? [],
            'published_at' => $request->status === 'published' && !$article->published_at ? now() : $article->published_at,
        ]);
        
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }
    
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus!');
    }
}