<?php

namespace Modules\Training\App\Http\Controllers\admin\course;

use App\Http\Controllers\Controller;

use Modules\Training\App\Models\Course\Content\CourseEpisode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseEpisodeAdminController extends Controller
{
    // Get all CourseEpisode
    public function index(): \Illuminate\Http\JsonResponse
    {
        $CourseEpisode = CourseEpisode::all();
        $CourseEpisodeCount = $CourseEpisode->count();
        return response()->json([
            'data' => [
                "Episodes" => $CourseEpisode,
                "EpisodeCount" => $CourseEpisodeCount
            ]

        ], 200);
    }


    // Store new episode
    public function createCourseEpisode(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new CourseCategory
        if ($user->hasPermissionTo('course:create')) {

            $validator = Validator::make($request->all(), [
                'course_season_id' => ['required'],
                'episode_order_number' => ['required'],
                'episode_name' => ['required'],
                'active' => ['required'],

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'data' => [
                        'errors' => $validator->errors(),
                    ]

                ], 422);
            }


            $episode = new CourseEpisode([
                'course_season_id' => $request->course_season_id,
                'episode_order_number' => $request->episode_order_number,
                'episode_name' => $request->episode_name,
                'episode_desc' => $request->episode_desc,
                'duration' => $request->duration,
                'active' => $request->active,
            ]);
            $episode->save();

            return response()->json([
                'data' => [
                    'message' => 'Course episode has ben create!'
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
        $CourseEpisode = CourseEpisode::where('id', $id)->first();
        // $need_requirement = Course::where('id', $id)->value('need_requirement');

        // if course need requirement to learn
        // if ($need_requirement) {
        //     $requirements = CourseRequirement::where('course_id', $id)->first();

        //     return response()->json([
        //         'data' => [
        //             'course' => $Course,
        //             'requirements' => $requirements,
        //         ]

        //     ], 200);
        // } else {

        return response()->json([
            'data' => [
                'CourseEpisode' => $CourseEpisode
            ]

        ], 200);
        // }
    }


    // Update  CourseEpisode
    public function editCourseEpisode(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update Course
        if ($user->hasPermissionTo('course:create')) {


            $CourseEpisode = CourseEpisode::findOrFail($id);

            $CourseEpisode->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Course Episode has ben Updated!',
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
