<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'content' => 'required|string|max:1000',
        ]);

        $data['user_id'] = auth()->id();

        Comment::create($data);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment, Authenticatable $user)
    {
        //
        $this->authorize('update', $comment);
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $comment->update($data);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment,Authenticatable $user)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return redirect()->back();
    }
}
