<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;

class ReplyController extends Controller
{
    public function index($discussionId)
    {
        $replies = Reply::where('discussion_id', $discussionId)
            ->with('user:id,name')
            ->get();

        return response()->json($replies);
    }

    public function store(Request $request)
    {
        $request->validate([
            'discussion_id' => 'required|exists:discussions,id',
            'content' => 'required|string',
        ]);

        $reply = Reply::create([
            'discussion_id' => $request->discussion_id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Komentar ditambahkan', 'reply' => $reply], 201);
    }
}
