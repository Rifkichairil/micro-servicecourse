<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $chapter = Chapter::query();

        $courseId = $request->query('course_id');

         // query q
        $chapter->when($courseId, function($query) use ($courseId) {
            return $query->where('course_id', $courseId);
        });

        return response()->json([
            'status'    => 'success',
            'data'      => $chapter->get()
        ]);
    }

    public function detail($id)
    {
        # code...
        $chapter = Chapter::find($id);

        if (!$chapter) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'chapter not found'
            ], 404);

        }

        return response()->json([
            'status'    => 'success',
            'data'      => $chapter
        ], 200);
    }

    public function create(Request $request)
    {
        # code...
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
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

        $chapter = Chapter::create($data);
        return response()->json([
            'status'    => 'error',
            'data'      => $chapter
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'course_id' => 'integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $chapter = Chapter::find($id);
        if (!$chapter) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $courseId = $request->course_id;
        if ($courseId) {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'course not found'
                ], 404);
            }
        }

        // udpate
        $chapter->fill($data);
        $chapter->save();

        return response()->json([
            'status'    => 'success',
            'data'      => $chapter
        ], 201);

    }

    public function delete($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'chapter not found'
            ], 404);
        }


        $chapter->delete();
        return response()->json([
            'status'    => 'success',
            'message'   => 'chapter deleted'
        ], 200);
    }
}
