<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $lesson = Lesson::query();

        $chapterId = $request->query('chapter_id');

        $lesson->when($chapterId, function($query) use ($chapterId) {
            $query->where('chapter_id', $chapterId);
        });

        return response()->json([
            'status'    => 'success',
            'data'      => $lesson->get()
        ], 200);
    }

    public function detail($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            # code...
            return response()->json([
                'status'    => 'error',
                'data'      => 'lesson not found'
            ], 404);
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $lesson
        ], 200);

    }

    public function create(Request $request)
    {
        # code...
        $rules = [
            'name'      =>  'required|string',
            'video'     =>  'required|string',
            'chapter_id'=>  'required|integer',
        ];

        // get all data body
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors()
            ], 400);
        }

        //  chapter
        $chapterId = $request->chapter_id;
        $chapter = Chapter::find($chapterId);

        if (!$chapter) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'chapter not found'
            ], 404);
        }

        $lesson = Lesson::create($data);

        return response()->json([
            'status'    => 'success',
            'data'      => $lesson
        ], 200);
    }

    public function update(Request $request, $id)
    {
        # code...
        $rules = [
            'name'      =>  'string',
            'video'     =>  'string',
            'chapter_id'=>  'integer',
        ];

        // get all data body
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors()
            ], 400);
        }

        $lesson = Lesson::find($id);
        if (!$lesson) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'lesson not found'
            ], 404);
        }

        $chapterId = $request->chapter_id;
        if ($chapterId) {
            # code...
            $chapter = Chapter::find($chapterId);

            if (!$chapter) {
                # code...
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'chapter not found'
                ], 404);
            }
        }

        $lesson->fill($data);
        $lesson->save();

        return response()->json([
            'status'    => 'success',
            'message'   => 'lesson updated'
        ], 200);



    }

    public function delete($id)
    {
        # code...
        $lesson = Lesson::find($id);
        if (!$lesson) {
            # code...
            return response()->json([
                'status'    => 'error',
                'data'      => 'lesson not found'
            ], 404);
        }

        $lesson->delete();
        return response()->json([
            'status'    => 'success',
            'data'      => 'lesson deleted'
        ], 200);

    }
}
