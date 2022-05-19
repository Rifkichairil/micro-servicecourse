<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    //
    public function create(Request $request)
    {
        $rules = [
            'user_id'   => 'required|integer',
            'course_id' => 'required|integer',
            'rating'    => 'required|min:1|max:5',
            'note'      => 'string'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors()
            ], 400);
        }

        // Chekc course ada atau tidak
        $courseId   = $request->course_id;
        $course     = Course::find($courseId);

        if (!$course) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'course not found'
            ]);
        }

        // Check user id
        $userId = $request->user_id;
        $user   = getUser($userId);

        if ($user['status'] === 'error') {
            # code...
            return response()->json([
                'status'    => $user['status'],
                'message'   => $user['message']
            ], $user['http_code']);
        }

        // Check duplikasi data
        $ifExistsReview = Review::where('course_id', $courseId)
                                ->where('user_id', $userId)
                                ->exists();

        if ($ifExistsReview) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'review already exists'
            ], 409);
        }


        // Cereate data revew
        $review = Review::create($data);

        return response()->json([
            'status'    => 'success',
            'message'   => $review
        ]);

    }

    public function update(Request $request, $id)
    {
        # code...
        $rules = [
            'rating'    => 'integer|min:1|max:5',
            'note'      => 'string'
        ];

        $data = $request->except('user_id', 'course_id');
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors()
            ], 400);
        }

        // Review id ada atau tidak
        $review = Review::find($id);
        if (!$review) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'review not found'
            ], 404);
        }

        $review->fill($data);
        $review->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'success updated'
        ]);
    }

    public function delete($id)
    {
        # code...
        $review = Review::find($id);
        if (!$review) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'review not found'
            ], 404);
        }

        $review->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'success deleted'
        ]);
    }
}


