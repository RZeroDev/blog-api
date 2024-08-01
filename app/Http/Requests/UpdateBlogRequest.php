<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $slug = $this->route('slug');
        $blogId = Blog::where('slug', $slug)->first()->id ?? null;

        return [
            'title' => 'required|string|max:255|unique:blogs,title,' . $blogId,
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
