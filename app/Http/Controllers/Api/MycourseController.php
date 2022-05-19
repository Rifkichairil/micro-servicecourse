<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class MycourseController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $mycourses = MyCourse::query()->with('course');

        $userId = $request->query('user_id');

        $mycourses->when($userId, function($q) use($userId){
            return $q->where('user_id', $userId);
        });

        return response()->json([
            'status'    => 'success',
            'data'      => $mycourses->get()
        ]);
    }

    public function create(Request $request)
    {
        # code...
        $rules = [
            'course_id' => 'required|integer',
            'user_id'   => 'required|integer'
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

        $courseId = $request->course_id;
        $course = Course::find($courseId);

        if (!$course) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'course not found'
            ], 404);
        }

        // Call helper
        $userId = $request->user_id;
        $user = getUser($userId);

        if ($user['status'] === 'error') {
            # code...
            return response()->json([
                'status'    => $user['status'],
                'message'   => $user['message']
            ], $user['http_code']);
        }

        $isExistMyCourse = MyCourse::where('course_id', $courseId)
                                    ->where('user_id', $userId)
                                    ->exists();

        if ($isExistMyCourse) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'user already take this course'
            ], 409);
        }

        if ($course->type === 'premium') {
            # code...

            // disini masuk
            if ($course->price === 0) {
                # code...
                return response()->json([
                    'status'        => 'error',
                    'message'       => 'price cannot be 0'
                ], 405);
            }
            $order = postOrder([
                'user'      => $user['data'],
                'course'    => $course->toArray()
            ]);

            // echo "<pre>".print_r($order,1)."</pre>";
            if ($order['status'] === 'error') {
                # code...
                return response()->json([
                    'status'    => $order['status'],
                    'message'   => $order['message'],
                ], $order['http-code']);
            }

            return response()->json([
                'status'    => $order['status'],
                'data'      => $order['data']
            ]);
        } else {
            $myCourse = MyCourse::create($data);

            return response()->json([
                'status'    => 'success',
                'data'      => $myCourse
            ]);
        }
    }

    public function createPremiumAccess(Request $request)
    {
        # code...
        $data       = $request->all();
        $myCourse   = MyCourse::create($data);

        return response()->json([
            'status'        => 'success',
            'data'          => $myCourse
        ]);
    }
}
