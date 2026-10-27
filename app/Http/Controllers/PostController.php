<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('post_index', ['posts' => $posts]);
    }

    public function show($id)
    {
        $post = Post::select('id', 'user_id', 'title', 'content', 'image', 'created_at', 'updated_at')
            ->where('id', $id)->firstOrFail();
        return view('post_show', ['post' => $post]);
    }

    public function create()
    {
        return view('post_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/posts', 'public');
        }

        $post = new Post();
        $post->user_id = auth()->id();
        $post->slug = Str::slug($request->title);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->image = $imagePath;
        $post->save();

        return redirect()->route('post.index')->with('success', 'Tạo bài viết thành công!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        return view('post_edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('uploads/posts', 'public');
        }

        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->save();

        return redirect()->route('post.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return redirect()->route('post.index')->with('success', 'Xóa bài viết thành công!');
    }
}
