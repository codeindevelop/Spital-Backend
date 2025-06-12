<?php

namespace Modules\Training\App\Http\Controllers\admin\student;

use Modules\Files\App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\CourseCategory;
use Modules\Training\App\Models\Student\StudentGroup;
use Modules\Training\App\Models\Student\StudentLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentLevelAdminController extends Controller
{
    // Get all  levels
    public function index(): \Illuminate\Http\JsonResponse
    {
        $StudentLevels = StudentLevel::all();
        $levelsCount = $StudentLevels->count();
        return response()->json([
            'data' => [
                "StudentLevel" => $StudentLevels,
                "levelCount" => $levelsCount
            ]

        ], 200);
    }

    // Store new level
    public function createStudentLevel(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new student level
        if ($user->hasPermissionTo('student_group:create')) {

            $validator = Validator::make($request->all(), [
                'level_name' => ['required', 'string', 'max:255', 'unique:student_levels'],

            ]);

            if ($validator->fails()) {

                $level_name = StudentLevel::where('level_name', $request->level_name)->first();

                // If user has Existed
                if ($level_name) {
                    // If user Email Exist
                    return response()->json([
                        'error' => 'level name has Exist'
                    ], 401);
                }
            }

            $level = new StudentLevel([
                'level_name' => $request->level_name,
                'level_image' => $request->level_image,

            ]);
            $level->save();

            return response()->json([
                'data' => [
                    'message' => 'student level has ben create!'
                ]

            ], 201);

            // If user has not permission
        } else {
            return response()->json([
                'data' => [
                    'message' => 'You dont have permission  '
                ]

            ], 400);
        }
    }


    // Get level by id
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $StudentLevel = StudentLevel::findOrFail($id);

        return response()->json([
            'data' => [
                'Level' => $StudentLevel
            ]

        ], 200);
    }


    // Update and Edit StudentLevel
    public function updateStudentLevel(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update student Level
        if ($user->hasPermissionTo('student_group:edit')) {


            $StudentLevel = StudentLevel::findOrFail($id);

            $StudentLevel->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Student level has ben Updated!',
                ]

            ], 202);

            // If user has not permission to edit StudentLevel
        } else {

            return response()->json([
                'message' => 'You dont have permission '
            ], 400);
        }
    }

    // Remove or Delete StudentGroup
    public function deleteStudentLevel($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        // Check user permission to update student Level
        if ($user->hasPermissionTo('student_group:delete')) {

            $level = StudentLevel::findOrFail($id);
            $level->delete();

            return response()->json([
                'data' => [
                    'message' => 'level has ben Deleted'
                ]

            ], 200);

            // If user has not permission to Delete level
        } else {

            return response()->json([
                'data' => [
                    'message' => 'You dont have permission  '
                ]

            ], 400);
        }
    }
}
