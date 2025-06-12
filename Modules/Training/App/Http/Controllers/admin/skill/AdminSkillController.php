<?php

namespace Modules\Files\App\Http\Controllers\v1\admin\skill;

use Modules\Files\App\Http\Controllers\Controller;
use Modules\Training\App\Models\Skill\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminSkillController extends Controller
{
    // get all skills
    public function getAllSkills(): \Illuminate\Http\JsonResponse
    {
        $skills = Skill::all();
        $skillsCount = $skills->count();
        return response()->json([
            'data' => [
                "skills" => $skills,
                "totalCount" => $skillsCount
            ]

        ]);
    }

    // create new skill
    public function createSkill(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user can create new skill
        if ($user->can('skill:create')) {

            $validator = Validator::make($request->all(), [
                'category_id' => ['required', 'string', 'max:255'],
                'skill_name' => ['required', 'unique:skills'],
                'active' => ['required', 'boolean'],

            ]);

            if ($validator->fails()) {
                $skill_name = Skill::where('skill_name', $request->skill_name)->first();

                // If skill_name has Existed
                if ($skill_name) {
                    // If skill_name Email Exist
                    return response()->json([
                        'error' => 'skill name has Exist'
                    ], 401);
                }
            }
        }
        $Skill = new Skill([

            'category_id' => $request->category_id,
            'skill_name' => $request->skill_name,
            'requirement_skills' => $request->requirement_skills,
            'job_level' => $request->job_level,
            'description' => $request->description,
            'day_need_to_learn' => $request->day_need_to_learn,
            'minimum_salary' => $request->minimum_salary,
            'migration_level' => $request->migration_level,
            'image' => $request->image,
            'cover_image' => $request->cover_image,
            'software_requirement' => $request->software_requirement,
            'used_platform' => $request->used_platform,
            'active' => $request->active,

        ]);

        $Skill->save();

        return response()->json([
            'data' => [
                "message" => "skill has ben create successfully"
            ]

        ], 201);
    }

    // update skill
    public function editSkill(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // If user can create new skill
        if ($user->can('skill:edit')) {


            $skill = Skill::where('id', $id)->first();

            $skill->update($request->all());
            return response()->json([
                'data' => [
                    "message" => "skill has ben edit successfully"
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
