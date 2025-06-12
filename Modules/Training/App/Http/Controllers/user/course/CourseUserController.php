<?php

namespace Modules\Training\App\Http\Controllers\user\course;

use Modules\Files\App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\Content\CourseVideo;
use Modules\Training\App\Models\Course\CourceRequirement;
use Modules\Training\App\Models\Course\Course;
use Modules\Training\App\Models\Instructor\Instructor;
use Illuminate\Http\Request;

class CourseUserController extends Controller
{
    // Get all Courses
    public function index(): \Illuminate\Http\JsonResponse
    {
        $courses = Course::with('category')->with('seasons')->get();


        return response()->json([
            'data' => [
                "courses" => $courses,
                "totalCount" => $courses->count()
            ]

        ], 200);
    }


    public function show($id)
    {
        $Course = Course::where('id', $id)->first();
        $need_requirement = Course::where('id', $id)->value('need_requirement');
        $instructor = Instructor::where('id', $Course->instructor_id)->first();
        $videos = CourseVideo::where('course_id', $id)->get();

        // if course need requrment to learn
        if ($need_requirement) {
            $requirements = CourceRequirement::where('course_id', $id)->first();

            return response()->json([
                'data' => [
                    'course' => $Course,
                    'requirements' => $requirements,
                    'instructor' => $instructor,
                    'videos' => $videos,
                ]

            ], 200);
        } else {

            return response()->json([
                'data' => [
                    'category' => $Course,
                    'instructor' => $instructor,
                    'videos' => $videos,
                ]

            ], 200);
        }
    }

    public function getCategoryCourses($category_id)
    {
        $Course = Course::where('category_id', $category_id)->get();
        // $need_requirement = Course::where('id', $id)->value('need_requirement');

        return response()->json([
            'data' => [
                'category' => $Course
            ]

        ], 200);
    }
}
