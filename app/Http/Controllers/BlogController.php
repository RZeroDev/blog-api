<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function index(): JsonResponse
    {
        $blogs = Blog::all();
        return response()->json($blogs);
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
        }

        $blog = Blog::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $path,
        ]);

        return response()->json($blog, 201);
    }

    public function show(string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['error' => 'Blog post not found'], 404);
        }

        return response()->json($blog);
    }

    public function update(UpdateBlogRequest $request, string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        if ($blog->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }

            $path = $request->file('image')->store('images', 'public');
            $blog->image = $path;
        }

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $blog->image,
        ]);

        return response()->json($blog);
    }

    public function destroy(string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        if ($blog->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json(null, 204);
    }
}
