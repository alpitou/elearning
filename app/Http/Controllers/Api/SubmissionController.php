<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:10240'
        ]);

        if ($request->user()->role !== 'mahasiswa') {
            return response()->json(['message' => 'Hanya mahasiswa yang dapat mengumpulkan tugas'], 403);
        }

        $filePath = $request->file('file')->store('submissions', 'public');

        $submission = Submission::create([
            'assignment_id' => $request->assignment_id,
            'student_id' => $request->user()->id,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Tugas berhasil dikumpulkan', 'submission' => $submission], 201);
    }

    public function grade(Request $request, $id)
    {
        $request->validate(['grade' => 'required|integer|min:0|max:100']);

        $submission = Submission::findOrFail($id);

        if ($request->user()->role !== 'dosen') {
            return response()->json(['message' => 'Hanya dosen yang dapat memberi nilai'], 403);
        }

        $submission->update(['grade' => $request->grade]);

        return response()->json(['message' => 'Nilai berhasil diberikan', 'submission' => $submission]);
    }
}