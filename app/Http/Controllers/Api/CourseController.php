<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('lecturer:id,name,email')->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // pastikan hanya dosen yang bisa menambah
        if ($request->user()->role !== 'dosen') {
            return response()->json(['message' => 'Hanya dosen yang dapat menambahkan mata kuliah'], 403);
        }

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'lecturer_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Mata kuliah berhasil dibuat', 'course' => $course], 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if ($request->user()->id !== $course->lecturer_id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $course->update($request->only('name', 'description'));

        return response()->json(['message' => 'Mata kuliah diperbarui', 'course' => $course]);
    }

    public function destroy(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if ($request->user()->id !== $course->lecturer_id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $course->delete();

        return response()->json([
            'message' => 'Mata kuliah dipindahkan ke trash (soft deleted).'
        ]);
    }

    public function enroll(Request $request, $id)
    {
        if ($request->user()->role !== 'mahasiswa') {
            return response()->json(['message' => 'Hanya mahasiswa yang dapat mendaftar'], 403);
        }

        $course = Course::findOrFail($id);
        $course->students()->attach($request->user()->id);

        return response()->json(['message' => 'Berhasil mendaftar ke mata kuliah']);
    }

    public function trash()
    {
        $trashed = Course::onlyTrashed()
            ->with('lecturer:id,name')
            ->get();

        return response()->json($trashed);
    }

    public function restore($id)
    {
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->restore();

        return response()->json([
            'message' => 'Course restored successfully.',
            'data' => $course
        ]);
    }

    public function forceDelete($id)
    {
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->forceDelete();

        return response()->json([
            'message' => 'Course permanently deleted.'
        ]);
    }
}
