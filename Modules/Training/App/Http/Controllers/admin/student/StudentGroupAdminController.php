<?php

namespace Modules\Training\App\Http\Controllers\admin\student;

use Modules\Files\App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\CourseCategory;
use Modules\Training\App\Models\Student\StudentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentGroupAdminController extends Controller
{
    // Get all  group
    public function index(): \Illuminate\Http\JsonResponse
    {
        $groups = StudentGroup::all();
        $groupCount = $groups->count();
        return response()->json([
            'data' => [
                "groups" => $groups,
                "groupsCount" => $groupCount
            ]

        ], 200);
    }

    // Store new Group
    public function createStudentGroup(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new student Group
        if ($user->hasPermissionTo('student_group:create')) {

            $validator = Validator::make($request->all(), [
                'group_name' => ['required', 'string', 'max:255', 'unique:student_groups'],
                'active' => ['required'],

            ]);

            if ($validator->fails()) {

                $group_name = StudentGroup::where('group_name', $request->group_name)->first();

                // If user has Existed
                if ($group_name) {
                    // If user Email Exist
                    return response()->json([
                        'error' => 'group name has Exist'
                    ], 401);
                }
            }

            $group = new StudentGroup([
                'group_name' => $request->group_name,
                'description' => $request->description,
                'active' => $request->active,
            ]);
            $group->save();

            return response()->json([
                'data' => [
                    'message' => 'student group has ben create!'
                ]

            ], 201);

            // If user has not permission
        } else {
            return response()->json([
                'data' => [
                    'message' => 'You dont have permission to create categories'
                ]

            ], 400);
        }
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $StudentGroup = StudentGroup::findOrFail($id);

        return response()->json([
            'data' => [
                'Group' => $StudentGroup
            ]

        ], 200);
    }


    // Update and Edit StudentGroup
    public function updateStudentGroup(Request $request, $id)
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update student group
        if ($user->hasPermissionTo('student_group:edit')) {


            $StudentGroup = StudentGroup::findOrFail($id);

            $StudentGroup->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Student Group has ben Updated!',
                    'category' => $StudentGroup
                ]

            ], 202);

            // If user has no permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission to edit categories'
            ], 400);
        }
    }

    // Remove or Delete StudentGroup
    public function deleteStudentGroup($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        // Check user permission to update student group
        if ($user->hasPermissionTo('student_group:delete')) {

            $group = StudentGroup::findOrFail($id);
            $group->delete();

            return response()->json([
                'data' => [
                    'message' => 'group has ben Deleted'
                ]

            ], 200);

            // If user has no permission to Delete StudentGroup
        } else {

            return response()->json([
                'data' => [
                    'message' => 'You dont have permission  '
                ]

            ], 400);
        }
    }
}
