<?php

namespace App\Http\Controllers\v1\admin\skill;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Skill\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminSkillCategoryController extends Controller
{
    // get all skill category
    public function getAllSkillsCategory(): \Illuminate\Http\JsonResponse
    {
        $categories = SkillCategory::all();
        $categoriesCount = $categories->count();
        return response()->json([
            'data' => [
                "categories" => $categories,
                "totalCount" => $categoriesCount
            ]

        ]);
    }

    // create new skill category
    public function createSkillCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user can create new skill
        if ($user->can('skill:create')) {

            $validator = Validator::make($request->all(), [
                'category_name' => ['required', 'string', 'max:255', 'unique:skill_categories'],
                'active' => ['required', 'boolean'],

            ]);

            if ($validator->fails()) {
                $category_name = SkillCategory::where('category_name', $request->category_name)->first();

                // If user has Existed
                if ($category_name) {
                    // If user Email Exist
                    return response()->json([
                        'error' => 'category name has Exist'
                    ], 401);
                }
            }
        }
        $category = new SkillCategory([
            "category_name" => $request->category_name,
            "description" => $request->description,
            "active" => $request->active,
        ]);

        $category->save();

        return response()->json([
            'data' => [
                "message" => "category has ben create successfully"
            ]

        ], 201);
    }

    // update skill category
    public function editSkillCategory(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user can create new skill
        if ($user->can('skill:edit')) {


            $category = SkillCategory::where('id', $request->id)->first();

            $category->update($request->all());
            return response()->json([
                'data' => [
                    "message" => "category has ben edit successfully"
                ]

            ], 201);
        } else {
            return response()->json([
                'data' => [
                    "error" => "you dont have permission"
                ]

            ], 402);
        }
    }
}
