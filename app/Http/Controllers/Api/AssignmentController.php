<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function index($courseId)
    {
        $assignments = Assignment::where('course_id', $courseId)
            ->with('lecturer:id,name')
            ->get();

        return response()->json($assignments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        if ($request->user()->role !== 'dosen') {
            return response()->json(['message' => 'Hanya dosen yang bisa membuat tugas'], 403);
        }

        $assignment = Assignment::create([
            'course_id' => $request->course_id,
            'lecturer_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return response()->json(['message' => 'Tugas berhasil dibuat', 'assignment' => $assignment], 201);
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();

        return response()->json([
            'message' => 'Assignment moved to trash (soft deleted).'
        ]);
    }

    public function trash()
    {
        $trashed = Assignment::onlyTrashed()
            ->with('course:id,name')
            ->get();

        return response()->json($trashed);
    }

    public function restore($id)
    {
        $assignment = Assignment::onlyTrashed()->findOrFail($id);
        $assignment->restore();

        return response()->json([
            'message' => 'Assignment restored successfully.',
            'data' => $assignment
        ]);
    }

    public function forceDelete($id)
    {
        $assignment = Assignment::onlyTrashed()->findOrFail($id);
        $assignment->forceDelete();

        return response()->json([
            'message' => 'Assignment permanently deleted.'
        ]);
    }
}