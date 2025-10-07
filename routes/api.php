<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\DiscussionController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AUTH ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// COURSES ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);
});

// MATERIALS ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses/{courseId}/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('/materials/{id}/download', [MaterialController::class, 'download']);
    Route::get('/materials/trash', [MaterialController::class, 'trash']);
    Route::put('/materials/{id}/restore', [MaterialController::class, 'restore']);
    Route::delete('/materials/{id}/force', [MaterialController::class, 'forceDelete']);
});

Route::middleware('auth:sanctum')->group(function () {
    // DISCUSSION ROUTES
    Route::get('/courses/{courseId}/discussions', [DiscussionController::class, 'index']);
    Route::post('/discussions', [DiscussionController::class, 'store']);
    Route::delete('/discussions/{id}', [DiscussionController::class, 'destroy']);
    Route::get('/discussions/trash', [DiscussionController::class, 'trash']);
    Route::put('/discussions/{id}/restore', [DiscussionController::class, 'restore']);
    Route::delete('/discussions/{id}/force', [DiscussionController::class, 'forceDelete']);

    // REPLY ROUTES
    Route::get('/discussions/{discussionId}/replies', [ReplyController::class, 'index']);
    Route::post('/replies', [ReplyController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    // ASSGINMENT ROUTESD
    Route::get('/courses/{courseId}/assignments', [AssignmentController::class, 'index']);
    Route::post('/assignments', [AssignmentController::class, 'store']);
    Route::get('/assignments/trash', [AssignmentController::class, 'trash']);
    Route::put('/assignments/{id}/restore', [AssignmentController::class, 'restore']);
    Route::delete('/assignments/{id}/force', [AssignmentController::class, 'forceDelete']);

    // SUBMISSION ROUTES
    Route::post('/submissions', [SubmissionController::class, 'store']);
    Route::put('/submissions/{id}/score', [SubmissionController::class, 'score']);
});

Route::middleware('auth:sanctum')->group(function () {
    // NOTIFICATION ROUTES
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // DASHBOARD ROUTES
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// REPORT ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reports/courses', [ReportController::class, 'coursesReport']);
    Route::get('/reports/assignments', [ReportController::class, 'assignmentsReport']);
    Route::get('/reports/students/{id}', [ReportController::class, 'studentReport']);
});