<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    public function index(Request $request)
    {
        $course = Course::query();

        $q = $request->query('q');
        $status = $request->query('status');

        // query q
        $course->when($q, function($query) use ($q) {
            return $query->whereRaw("name LIKE '%". strtolower($q) ."%'");
        });

        $course->when($status, function($query) use ($status) {
            return $query->where('status', $status);
        });

        return response()->json([
            'status'    => 'success',
            'data'      => $course->paginate(10)
        ]);
    }

    public function detail($id)
    {
        # code...
        $course = Course::with('chapters.lesson', 'mentor', 'images')->find($id);
        if (!$course) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'course not found'
            ], 404);
        }

        // review
        $reviews = Review::where('course_id', $id)->get()->toArray();
        if (count($reviews) > 0) {
            # code...
            $userIds = array_column($reviews, 'user_id');
            $users = getUserById($userIds);
            // echo "<pre>". print_r($users,1)."</pre>";

            if ($users['status'] === 'error') {
                # code...
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                    # code...
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }

        // Count student
        $totalStudent   = MyCourse::where('course_id', $id)->count();
        $totalVideos    = Chapter::where('course_id', $id)->withCount('lesson')->get()->toArray();
        $finalVideos    = array_sum(array_column($totalVideos, 'lesson_count'));
        // echo "<pre>". print_r($finalVideos,1)."</pre>";

        $course['reviews']      = $reviews;
        $course['totalStudent'] = $totalStudent;
        $course['totalVideos']  = $finalVideos;

        return response()->json([
            'status'    => 'success',
            'data'      => $course
        ]);
    }

    public function create(Request $request)
    {
        # code...
        $rules = [
            'name'          => 'required|string',
            'certificate'   => 'required|boolean',
            'thumbnail'     => 'string|url',
            'type'          => 'required|in:free,premium',
            'status'        => 'required|in:draft,published',
            'price'         => 'integer',
            'level'         => 'required|in:all-level,beginner,intermediate,advance',
            'mentor_id'     => 'required|integer',
            'description'   => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data,$rules);

        if ($validator->fails()) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentorId = $request->mentor_id;
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'mentor not found'
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
            'status'    => 'success',
            'data'      => $course
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'          => 'string',
            'certificate'   => 'boolean',
            'thumbnail'     => 'string|url',
            'type'          => 'in:free,premium',
            'status'        => 'in:draft,publish',
            'price'         => 'integer',
            'level'         => 'in:all-level,beginner,intermediate,advance',
            'mentor_id'     => 'integer',
            'description'   => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors()
            ], 400);
        }

        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'course not found'
            ], 404);
        }

        $mentorId = $request->mentor_id;
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'mentor not found'
                ], 404);
            }
        }

        // update data
        $course->fill($data);
        $course->save();

        return response()->json([
            'status'    => 'success',
            'data'      => $course
        ], 201);

    }

    public function delete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status'    => 'success',
            'message'   => 'course deleted'
        ], 200);
    }
}
