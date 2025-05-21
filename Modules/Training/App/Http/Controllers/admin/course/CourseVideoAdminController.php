<?php

namespace Modules\Training\App\Http\Controllers\admin\course;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\Content\CourseEpisode;
use Modules\Training\App\Models\Course\Content\CourseVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseVideoAdminController extends Controller
{
    // Get all Course Videos
    public function index(): \Illuminate\Http\JsonResponse
    {
        $CourseVideo = CourseVideo::all();
        $CourseVideosCount = $CourseVideo->count();
        return response()->json([
            'data' => [
                "videos" => $CourseVideo,
                "VideosCount" => $CourseVideosCount
            ]

        ], 200);
    }


    // Store new Season
    public function createCourseVideo(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new CourseCategory
        if ($user->hasPermissionTo('course:create')) {

            $validator = Validator::make($request->all(), [
                'course_id' => ['required'],
                'course_season_id' => ['required'],
                'course_episode_id' => ['required'],
                'video_name' => ['required'],
                'active' => ['required'],

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'data' => [
                        'errors' => $validator->errors(),
                    ]

                ], 422);
            }


            $CourseVideo = new CourseVideo([
                'course_id' => $request->course_id,
                'course_season_id' => $request->course_season_id,
                'course_episode_id' => $request->course_episode_id,
                'video_name' => $request->video_name,
                'video_desc' => $request->video_desc,
                'video_link' => $request->video_link,
                'video_format' => $request->video_format,
                'duration' => $request->duration,
                'active' => $request->active,
            ]);
            $CourseVideo->save();

            return response()->json([
                'data' => [
                    'message' => 'Course video has ben submit!'
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
        $CourseVideo = CourseVideo::where('id', $id)->first();
        // $episode = CourseEpisode::where('episode_id', $Course->id)->with('episode')->get();

        return response()->json([
            'data' => [
                'video' => $CourseVideo,
            ]
        ], 200);
    }


    // Update  CourseVideo
    public function editCourseVideo(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update Course
        if ($user->hasPermissionTo('course:create')) {


            $CourseVideo = CourseVideo::findOrFail($id);

            $CourseVideo->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Course Video has ben Updated!',
                ]

            ], 202);

            // If user has not permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission'
            ], 400);
        }
    }
}
