<?php

use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ImageCourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\MycourseController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Mentor
Route::get('mentors', [MentorController::class, 'index']);
Route::get('mentors/{id}', [MentorController::class, 'detail']);
Route::post('mentors', [MentorController::class, 'create']);
Route::put('mentors/{id}', [MentorController::class, 'update']);
Route::delete('mentors/{id}', [MentorController::class, 'delete']);

// Course
Route::get('courses', [CourseController::class, 'index']);
Route::post('courses', [CourseController::class, 'create']);
Route::put('courses/{id}', [CourseController::class, 'update']);
Route::delete('courses/{id}', [CourseController::class, 'delete']);

// Detail Course
Route::get('courses/{id}', [CourseController::class, 'detail']);


// Image Course
Route::post('image-courses', [ImageCourseController::class, 'create']);
Route::delete('image-courses/{id}', [ImageCourseController::class, 'delete']);

// Chapter
Route::get('chapters', [ChapterController::class, 'index']);
Route::get('chapters/{id}', [ChapterController::class, 'detail']);
Route::post('chapters', [ChapterController::class, 'create']);
Route::put('chapters/{id}', [ChapterController::class, 'update']);
Route::delete('chapters/{id}', [ChapterController::class, 'delete']);

// Lesson
Route::get('lessons', [LessonController::class, 'index']);
Route::get('lessons/{id}', [LessonController::class, 'detail']);
Route::post('lessons', [LessonController::class, 'create']);
Route::put('lessons/{id}', [LessonController::class, 'update']);
Route::delete('lessons/{id}', [LessonController::class, 'delete']);

// My Courses
Route::get('my-courses', [MycourseController::class, 'index']);
Route::post('my-courses', [MycourseController::class, 'create']);
Route::post('my-courses/premium', [MycourseController::class, 'createPremiumAccess']);

// Review
Route::post('reviews', [ReviewController::class, 'create']);
Route::put('reviews/{id}', [ReviewController::class, 'update']);
Route::delete('reviews/{id}', [ReviewController::class, 'delete']);






