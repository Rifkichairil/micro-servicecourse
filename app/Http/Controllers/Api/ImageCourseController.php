<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ImageCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    //
    public function create(Request $request)
    {
        # code...
        $rules = [
            'course_id'     => 'required|integer',
            'image'         => 'required|url'
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

        $courseId   = $request->course_id;
        $course     = Course::find($courseId);

        if (!$course) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'course not  found'
            ], 404);
        }

        $imageCourse = ImageCourse::create($data);
        return response()->json([
            'staus'     => 'success',
            'data'      => $imageCourse
        ], 200);

    }

    public function delete($id)
    {
        # code...
        $imageId = ImageCourse::find($id);

        if (!$imageId) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'image course not  found'
            ], 404);
        }

        $imageId->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'image course deleted'
        ], 200);
    }
}
