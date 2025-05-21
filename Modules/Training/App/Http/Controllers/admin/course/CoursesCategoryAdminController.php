<?php

namespace Modules\Training\App\Http\Controllers\admin\course;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoursesCategoryAdminController extends Controller
{
    // Get all Post Categories
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = CourseCategory::all();
        $categoryCount = $categories->count();
        return response()->json([
            'data' => [
                "categories" => $categories,
                "totalCount" => $categoryCount
            ]

        ], 200);
    }

    // Store new Category
    public function createCourseCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user Have Permission to create new CourseCategory
        if ($user->hasPermissionTo('course_category:create')) {

            $validator = Validator::make($request->all(), [
                'category_name' => ['required', 'string', 'max:255', 'unique:course_categories'],
                'slug' => ['required', 'max:255', 'unique:course_categories'],
                'active' => ['required'],

            ]);

            if ($validator->fails()) {
                // check user has registered or not by Instructor slug
                $category_name = CourseCategory::where('category_name', $request->category_name)->first();

                // If user has Existed
                if ($category_name) {
                    // If user Email Exist
                    return response()->json([
                        'error' => 'category name has Exist'
                    ], 401);
                }
                $category_slug = CourseCategory::where('slug', $request->slug)->first();

                // If user has Existed
                if ($category_slug) {
                    // If user Email Exist
                    return response()->json([
                        'error' => 'category slug has Exist'
                    ], 401);
                }
            }

            $category = new CourseCategory([
                'category_name' => $request->category_name,
                'slug' => $request->slug,
                'description' => $request->description,
                'active' => $request->active,
            ]);
            $category->save();

            return response()->json([
                'data' => [
                    'message' => 'Category has ben create!'
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
        $category = CourseCategory::findOrFail($id);

        return response()->json([
            'data' => [
                'category' => $category
            ]
        ], 200);
    }


    // Update and Edit post categories
    public function updateCourseCategory(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update category
        if ($user->hasPermissionTo('update course category')) {

            $validator = Validator::make($request->all(), [
                'category_name' => ['required', 'string', 'max:255'],
                'active' => ['required', 'boolean'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->message()
                ]);
            } else {

                $category = CourseCategory::findOrFail($id);

                $category->update($request->all());

                return response()->json([
                    'data' => [
                        'message' => 'Category has ben Updated!',
                        'category' => $category
                    ]

                ], 202);
            }
            // If user has not permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission to edit categories'
            ], 400);
        }
    }

    // Remove or Delete post Category
    public function deleteCourseCategory($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        // Check user permission to update category
        if ($user->hasPermissionTo('course_category:delete')) {

            $category = CourseCategory::findOrFail($id);
            $category->delete();

            return response()->json([
                'data' => [
                    'message' => 'Category has ben Deleted'
                ]

            ], 200);

            // If user has not permission to Delete category
        } else {

            return response()->json([
                'data' => [
                    'message' => 'You dont have permission to Delete post categories'
                ]

            ], 400);
        }
    }
}
