<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussion;

class DiscussionController extends Controller
{
    public function index($courseId)
    {
        $discussions = Discussion::where('course_id', $courseId)
            ->with('user:id,name')
            ->withCount('replies')
            ->get();

        return response()->json($discussions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $discussion = Discussion::create([
            'course_id' => $request->course_id,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Diskusi berhasil dibuat', 'discussion' => $discussion], 201);
    }

public function destroy($id)
    {
        $discussion = Discussion::findOrFail($id);
        $discussion->delete();
        
        if ($request->user()->id !== $discussion->user_id && $request->user()->role !== 'dosen') {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        return response()->json([
            'message' => 'Discussion moved to trash (soft deleted).'
        ]);
    }

    public function trash()
    {
        $trashed = Discussion::onlyTrashed()
            ->with(['course:id,name', 'user:id,name'])
            ->get();

        return response()->json($trashed);
    }

    public function restore($id)
    {
        $discussion = Discussion::onlyTrashed()->findOrFail($id);
        $discussion->restore();

        return response()->json([
            'message' => 'Discussion restored successfully.',
            'data' => $discussion
        ]);
    }

    public function forceDelete($id)
    {
        $discussion = Discussion::onlyTrashed()->findOrFail($id);
        $discussion->forceDelete();

        return response()->json([
            'message' => 'Discussion permanently deleted.'
        ]);
    }
}
