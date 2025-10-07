<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'dosen') {
            $summary = [
                'total_courses' => Course::where('lecturer_id', $user->id)->count(),
                'total_assignments' => Assignment::where('lecturer_id', $user->id)->count(),
                'total_submissions' => Submission::whereHas('assignment', function ($q) use ($user) {
                    $q->where('lecturer_id', $user->id);
                })->count(),
            ];
        } else {
            $summary = [
                'total_enrolled_courses' => $user->courses()->count(),
                'total_submissions' => Submission::where('student_id', $user->id)->count(),
                'average_grade' => Submission::where('student_id', $user->id)->avg('grade'),
            ];
        }

        return response()->json([
            'user' => $user->only('id', 'name', 'role'),
            'summary' => $summary
        ]);
    }
}