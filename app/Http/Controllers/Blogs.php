<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogLike;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class Blogs extends Controller
{
    function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:2',
            'content' => 'required|string|min:8|max:256'
        ]);

        $blog = Blog::create([...$data, 'user_id' => $request->user()->id]);

        return $blog;
    }

    function findOne($id)
    {
        return  Blog::find($id) ??
            response()->json(['error' => 'blog not found'], 404);
    }

    function findAll()
    {
        return Blog::withCount('comments')->get();
    }

    function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'string|min:2',
            'content' => 'string|min:8|max:256'
        ]);


        return Blog::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update($data);
    }

    function delete(Request $request, $id)
    {
        return Blog::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->delete();
    }
    function toggleLike(Request $request, $blog_id)
    {
        $userId = $request->user()->id;
        // check user if like blog
        $userIsLikePost = BlogLike::where(['user_id' => $userId, 'blog_id' => $blog_id])->first();



        $blog = $this->findOne($blog_id);
        if (!$userIsLikePost) {
            // add like
            BlogLike::create([
                "user_id" => $userId,
                "blog_id" => $blog_id
            ]);

            Blog::where('id', $blog_id)->update(['likes' => $blog->likes + 1]);
            return response()->json(['status' => true, 'msg' => 'like is added']);
        } else {
            // remove like
            BlogLike::where('id', $userIsLikePost->id)->delete();
            Blog::where('id', $blog_id)->update(['likes' => $blog['likes'] - 1]);
            return response()->json(['status' => true, 'msg' => 'like is removed']);
        }
    }
}
