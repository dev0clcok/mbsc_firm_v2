<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $query = BlogPost::published()
            ->with('category', 'author')
            ->recent();

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('excerpt', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $posts = $query->paginate(12)->withQueryString();

        $categories = BlogCategory::withCount('posts')->get();

        return Inertia::render('public/Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'filters' => [
                'category' => $request->category,
                'search' => $request->search,
            ],
        ]);
    }

    public function show(BlogPost $post): Response
    {
        if (!$post->is_published || $post->published_at > now()) {
            abort(404);
        }

        // Increment views
        $post->incrementViews();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(3)
            ->get();

        return Inertia::render('public/Blog/Show', [
            'post' => $post->load('category', 'author'),
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
