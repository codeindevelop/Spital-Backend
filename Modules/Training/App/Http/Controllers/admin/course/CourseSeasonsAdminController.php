<?php

namespace Modules\Training\App\Http\Controllers\admin\course;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\Content\CourseSeason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseSeasonsAdminController extends Controller
{
    // Get all CourseSeason
    public function index(): \Illuminate\Http\JsonResponse
    {
        $CourseSeason = CourseSeason::all();
        $SeasonCount = $CourseSeason->count();
        return response()->json([
            'data' => [
                "seasons" => $CourseSeason,
                "SeasonsCount" => $SeasonCount
            ]

        ], 200);
    }


    // Store new Season
    public function createCourseSeason(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new CourseCategory
        if ($user->hasPermissionTo('course:create')) {

            $validator = Validator::make($request->all(), [
                'course_id' => ['required'],
                'season_order_number' => ['required'],
                'season_name' => ['required'],
                'active' => ['required'],

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'data' => [
                        'errors' => $validator->errors(),
                    ]

                ], 422);
            }


            $CourseSeason = new CourseSeason([
                'course_id' => $request->course_id,
                'season_order_number' => $request->season_order_number,
                'season_name' => $request->season_name,
                'season_desc' => $request->season_desc,
                'duration' => $request->duration,
                'active' => $request->active,
            ]);
            $CourseSeason->save();

            return response()->json([
                'data' => [
                    'message' => 'Course Season has ben create!'
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
        $CourseSeason = CourseSeason::where('id', $id)->first();
        // $need_requirement = Course::where('id', $id)->value('need_requirement');

        // if course need requrment to learn
        // if ($need_requirement) {
        //     $requirements = CourceRequirement::where('course_id', $id)->first();

        //     return response()->json([
        //         'data' => [
        //             'course' => $Course,
        //             'requirements' => $requirements,
        //         ]

        //     ], 200);
        // } else {

        return response()->json([
            'data' => [
                'CourseSeason' => $CourseSeason
            ]

        ], 200);
        // }
    }


    // Update and  editCourseSeason
    public function editCourseSeason(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update Course
        if ($user->hasPermissionTo('course:create')) {


            $CourseSeason = CourseSeason::findOrFail($id);

            $CourseSeason->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Course Season has ben Updated!',
                ]

            ], 202);

            // If user has no permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission to edit categories'
            ], 400);
        }
    }
}
