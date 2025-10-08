<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240'
        ]);

        if ($request->user()->role !== 'dosen') {
            return response()->json(['message' => 'Hanya dosen yang bisa upload materi'], 403);
        }

        $filePath = $request->file('file')->store('materials', 'public');

        $material = Material::create([
            'course_id' => $request->course_id,
            'lecturer_id' => $request->user()->id,
            'title' => $request->title,
            'file_path' => $filePath,
        ]);

        return response()->json([
            'message' => 'Materi berhasil diupload',
            'data' => $material
        ], 201);
    }

    public function download($id)
    {
        $material = Material::findOrFail($id);

        $filePath = storage_path('app/public/' . $material->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return response()->download($filePath, basename($filePath));
    }

    public function index($courseId)
    {
        $materials = Material::where('course_id', $courseId)->get();
        return response()->json($materials);
    }
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->json([
            'message' => 'Material moved to trash (soft deleted).'
        ]);
    }

    public function trash()
    {
        $trashed = Material::onlyTrashed()
            ->with('course:id,name')
            ->get();

        return response()->json($trashed);
    }

    public function restore($id)
    {
        $material = Material::onlyTrashed()->findOrFail($id);
        $material->restore();

        return response()->json([
            'message' => 'Material restored successfully.',
            'data' => $material
        ]);
    }

    public function forceDelete($id)
    {
        $material = Material::onlyTrashed()->findOrFail($id);
        $material->forceDelete();

        return response()->json([
            'message' => 'Material permanently deleted.'
        ]);
    }
}
