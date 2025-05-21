<?php

namespace Modules\Training\App\Http\Controllers\admin\course;

use App\Http\Controllers\Controller;

use Modules\Training\App\Models\Course\Content\CourseSeason;
use Modules\Training\App\Models\Course\Content\CourseVideo;
use Modules\Training\App\Models\Course\Course;
use Modules\Training\App\Models\Course\CourseRequirement;
use Modules\Training\App\Models\Instructor\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoursesAdminController extends Controller
{
    // Get all Courses
    public function index(): \Illuminate\Http\JsonResponse
    {
        $Courses = Course::all();
        $CoursesCount = $Courses->count();
        return response()->json([
            'data' => [
                "Courses" => $Courses,
                "CoursesCount" => $CoursesCount
            ]

        ], 200);
    }


    // Store new Course
    public function createNewCourse(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new CourseCategory
        if (!$user->hasPermissionTo('course:create')) {
            return response()->json([
                'data' => [
                    'message' => 'You dont have permission to create categories'
                ]

            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'string', 'max:255'],
            'instructor_id' => ['required', 'string', 'max:255'],
            'course_title' => ['required', 'string', 'max:255'],
            'course_slug' => ['required', 'string', 'max:255', 'unique:courses'],
            'price' => ['required'],
            'active' => ['required'],

        ]);

        if ($validator->fails()) {
            // check user has registered or not by Instructor slug
            $course_slug = Course::where('course_slug', $request->course_slug)->first();

            // If user has Existed
            if ($course_slug) {
                // If user Email Exist
                return response()->json([
                    'error' => 'course slug has Exist'
                ], 401);
            }
        }

        $Course = new Course([
            'category_id' => $request->category_id,
            'instructor_id' => $request->instructor_id,
            'course_title' => $request->course_title,
            'course_slug' => $request->course_slug,
            'short_desc' => $request->short_desc,
            'longdesc' => $request->longdesc,
            'keywords' => $request->keywords,
            'status' => $request->status,
            'course_image' => $request->course_image,
            'cover_image' => $request->cover_image,
            'course_intro_video' => $request->course_intro_video,
            'course_time' => $request->course_time,
            'need_requirement' => $request->need_requirement,
            'course_level' => $request->course_level,
            'is_special' => $request->is_special,
            'price' => $request->price,

            'active' => $request->active,
        ]);
        $Course->save();

        // of course need requirement
        if ($request->need_requirement) {

            $requirement = new CourseRequirement([
                'course_id' => $Course->id,
                'requirement_courses_id' => $request->requirement_courses_id,
                'description' => $request->requirement_courses_description,
                'active' => $request->requirement_courses_active
            ]);

            $requirement->save();
        }

        return response()->json([
            'data' => [
                'message' => 'Course has ben create!'
            ]
        ], 201);


    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $Course = Course::where('id', $id)->first();
        $seasons = CourseSeason::where('course_id', $Course->id)->with('episode')->get();

        // $episodes_id = $seasons->episode->id;
        $videos = CourseVideo::where('course_id', $Course->id)->get();


        $need_requirement = Course::where('id', $id)->value('need_requirement');
        $instructor = Instructor::where('id', $Course->instructor_id)->first();

        // if course need requirement to learn
        if ($need_requirement) {
            $requirements = CourseRequirement::where('course_id', $id)->first();

            return response()->json([
                'data' => [
                    'category' => $Course,
                    'requirements' => $requirements,
                    'seasons' => $seasons,
                    'videos' => $videos,
                    'instructor' => $instructor,
                    // 'episodes' => $episodes,
                ]

            ], 200);
        } else {

            return response()->json([
                'data' => [
                    'course' => $Course,
                    '$seasons' => $seasons,
                    'videos' => $videos,
                    'instructor' => $instructor,
                    // 'episodes' => $episodes
                ]

            ], 200);
        }
    }


    // Update and Edit Course
    public function updateCourse(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update Course
        if ($user->hasPermissionTo('course:create')) {

            $validator = Validator::make($request->all(), [
                'course_slug' => ['required', 'string'],

            ]);

            if ($validator->fails()) {


                // If user Email Exist
                return response()->json([
                    'error' => 'course slug has required'
                ], 401);
            } else {

                $Course = Course::findOrFail($id);

                $Course->update($request->all());

                return response()->json([
                    'data' => [
                        'message' => 'Course has ben Updated!',
                    ]

                ], 202);
            }
            // If user has no permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission to edit categories'
            ], 400);
        }
    }
}
