<?php

namespace Modules\Training\App\Http\Controllers\admin\course;

use Modules\Files\App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Training\App\Models\Course\CourseFile;

class CourseFilesAdminController extends Controller
{
    // Get all Course Files
    public function index(): \Illuminate\Http\JsonResponse
    {
        $CourseFile = CourseFile::all();
        $CourseFilesCount = $CourseFile->count();
        return response()->json([
            'data' => [
                "files" => $CourseFile,
                "filesCount" => $CourseFilesCount
            ]

        ], 200);
    }


    // Store new course file
    public function createCourseFile(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new Course file
        if ($user->hasPermissionTo('course:create')) {

            $validator = Validator::make($request->all(), [
                'course_id' => ['required'],
                'file_name' => ['required'],
                'active' => ['required'],

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'data' => [
                        'errors' => $validator->errors(),
                    ]

                ], 422);
            }


            $CourseFile = new CourseFile([
                'course_id' => $request->course_id,
                'uploader_id' => $user->id,
                'file_name' => $request->file_name,
                'file_title' => $request->file_title,
                'file_type' => $request->file_type,
                'file_url' => $request->file_url,
                'file_size' => $request->file_size,
                'duration' => $request->duration,
                'file_tag' => $request->file_tag,
                'active' => $request->active,
            ]);
            $CourseFile->save();

            return response()->json([
                'data' => [
                    'message' => 'Course file has ben submit!'
                ]

            ], 201);

            // If user has not permission
        } else {
            return response()->json([
                'data' => [
                    'message' => 'You dont have permission'
                ]

            ], 400);
        }
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $CourseFile = CourseFile::where('id', $id)->first();
        // $episode = CourseEpisode::where('episode_id', $Course->id)->with('episode')->get();


        return response()->json([
            'data' => [
                'file' => $CourseFile,
            ]

        ], 200);
    }


    // Update  Course file
    public function editCourseFile(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update Course
        if ($user->hasPermissionTo('course:create')) {


            $CourseFile = CourseFile::findOrFail($id);

            $CourseFile->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Course file has ben Updated!',
                ]

            ], 202);

            // If user has no permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission'
            ], 400);
        }
    }
}
