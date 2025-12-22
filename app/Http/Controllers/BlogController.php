<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display public listing of blog posts
     */
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9);
            
        return view('blog.index', compact('posts'));
    }

    /**
     * Display single blog post publicly
     */
    public function show(BlogPost $post)
    {
        // Ensure only published posts are visible to non-admins
        if (!$post->is_published && !Auth::user()?->isAdmin()) {
            abort(404);
        }
        
        // Also check published_at for consistency
        if (!$post->published_at && !Auth::user()?->isAdmin()) {
            abort(404);
        }
        
        return view('blog.show', compact('post'));
    }

    /**
     * Display admin listing of all blog posts
     */
    public function adminIndex()
    {
        $posts = BlogPost::latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show form to create new blog post
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store new blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog-images', 'public');
        }

        $post = BlogPost::create([
            'title' => strip_tags($validated['title']),
            'slug' => $validated['slug'] ?? \Str::slug($validated['title']),
            'description' => strip_tags($validated['description']),
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'is_published' => $validated['is_published'] ?? false,
            'published_at' => ($validated['is_published'] ?? false) ? now() : null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post ' . ($post->is_published ? 'published' : 'saved as draft') . ' successfully!');
    }

    /**
     * Show form to edit blog post
     */
    public function edit(BlogPost $post)
    {
        return view('admin.blog.edit', compact('post'));
    }

    /**
     * Update blog post
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('blog-images', 'public');
            $validated['featured_image'] = $imagePath;
        } else {
            // Keep existing image
            $validated['featured_image'] = $post->featured_image;
        }

        // Handle publish status
        $isPublishing = ($validated['is_published'] ?? false) && !$post->is_published;
        $isUnpublishing = !($validated['is_published'] ?? false) && $post->is_published;

        $updateData = [
            'title' => strip_tags($validated['title']),
            'slug' => $validated['slug'] ?? $post->slug,
            'description' => strip_tags($validated['description']),
            'content' => $validated['content'],
            'featured_image' => $validated['featured_image'],
            'is_published' => $validated['is_published'] ?? false,
        ];

        // Update published_at based on status changes
        if ($isPublishing) {
            $updateData['published_at'] = now();
        } elseif ($isUnpublishing) {
            $updateData['published_at'] = null;
        } else {
            // Keep existing published_at if already published
            $updateData['published_at'] = $post->published_at;
        }

        $post->update($updateData);

        $statusMessage = $isPublishing ? 'published' : ($isUnpublishing ? 'unpublished and saved as draft' : 'updated');
        return redirect()->route('admin.blog.index')
            ->with('success', "Blog post {$statusMessage} successfully!");
    }

    /**
     * Delete blog post
     */
    public function destroy(BlogPost $post)
    {
        // Delete image if exists
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();
        
        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}