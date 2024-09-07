<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Comments extends Controller
{
    function create(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|min:8|max:256',
            'blog_id' => 'required|integer'
        ]);

        $comment = Comment::create([...$data, 'user_id' => $request->user()->id]);

        return $comment;
    }

    function findOne($id)
    {
        return  Comment::find($id) ??
            response()->json(['error' => 'Comment not found'], 404);
    }

    function findAll($blog_id)
    {
        return Comment::where('blog_id', $blog_id)->with('user')->get();
    }

    function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'string|min:2',
            'content' => 'string|min:8|max:256'
        ]);


        return Comment::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update($data);
    }

    function delete(Request $request, $id)
    {
        return Comment::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->delete();
    }
}
