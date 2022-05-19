<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{

    public function index()
    {
        $mentors = Mentor::all();

        return response()->json([
            'status'   => 'success',
            'data'     => $mentors
        ]);
    }

    public function detail($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'status'   => 'error',
                'data'     => 'Mentor not found'
            ], 404);
        }

        return response()->json([
            'status'   => 'success',
            'data'     => $mentor
        ]);
    }

    public function create(Request $request)
    {
        # code...
        $rules = [
            'name'      =>  'required|string',
            'profile'   =>  'required|url',
            'profession'=>  'required|string',
            'email'     =>  'required|email'
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

        $mentor = Mentor::create($data);
        return response()->json([
            'status'    => 'success',
            'data'      => $mentor
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'      =>  'string',
            'profile'   =>  'url',
            'profession'=>  'string',
            'email'     =>  'email'
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

        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Mentor not found'
            ], 404);
        }

        $mentor->fill($data);
        $mentor->save();

        return response()->json([
            'status'    => 'success',
            'data'      => $mentor
        ]);
    }

    public function delete($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            # code...
            return response()->json([
                'status'    => 'error',
                'message'   => 'mentor not found'
            ], 404);

        }

        $mentor->delete();

        return response()->json([
            'status'    => 'success',
            'message'   => 'mentor berhasil dihapus'
        ]);
    }
}
