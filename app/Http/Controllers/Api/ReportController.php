<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function coursesReport()
    {
        $report = Course::withCount('students')
            ->with('lecturer:id,name')
            ->get(['id', 'name', 'lecturer_id']);

        return response()->json($report);
    }

    public function assignmentsReport()
    {
        $report = Assignment::withCount([
            'submissions as graded_count' => function ($q) {
                $q->whereNotNull('score');
            },
            'submissions as ungraded_count' => function ($q) {
                $q->whereNull('score');
            },
        ])
        ->with('course:id,name')
        ->get(['id', 'title', 'course_id']);

        return response()->json($report);
    }

    public function studentReport($id)
    {
        $student = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        $submissions = Submission::where('student_id', $id)
            ->with(['assignment:id,title,course_id', 'assignment.course:id,name'])
            ->select('id', 'assignment_id', 'score', 'created_at')
            ->get();

        $summary = [
            'student' => $student->only(['id', 'name', 'email']),
            'total_submissions' => $submissions->count(),
            'graded_count' => $submissions->whereNotNull('score')->count(),
            'ungraded_count' => $submissions->whereNull('score')->count(),
            'average_score' => round($submissions->whereNotNull('score')->avg('score'), 2),
            'submissions' => $submissions,
        ];

        return response()->json($summary);
    }
}