<?php

namespace Modules\Training\App\Http\Controllers\admin\instructor;

use Modules\Files\App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Training\App\Models\Instructor\Instructor;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class InstructorController extends Controller
{
    // Get all  Instructors
    public function GetAllInstructor(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user->can('instructor:view')) {

            $Instructor = Instructor::with('userData')->get();
            $InstructorCount = $Instructor->count();
            return response()->json([
                'data' => [
                    "Instructors" => $Instructor,
                    "totalCount" => $InstructorCount
                ]

            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'You dont hve permission to this !',

                ]
            ], 401);
        }
    }


    // Get instructor by id
    public function getById(string $id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user->can('instructor:view')) {

            $instructor = Instructor::where('id', $id)->with('userData')->first();
        } else {
            return response()->json([
                'data' => [
                    "error" => 'you dont have permission'

                ]

            ], 402);
        }

        // if instructor has existed
        if ($instructor) {

            return response()->json([
                'data' => [
                    "Instructor" => $instructor,
                ]

            ], 200);
        } else {
            return response()->json(
                [
                    'data' => [
                        "error" => 'cant find instructor by this id',
                    ]
                ],
                404
            );
        }
    }


    public function RegisterInstructor(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if (!$user->can('instructor:create')) {
            return response()->json([
                'data' => [
                    'message' => 'You dont hve permission to this !',
                ]
            ], 203);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'unique:instructors'],
            'instructor_slug' => ['required', 'unique:instructors'],
        ]);

        // Check Validator Error
        if ($validator->fails()) {
            // check user has registered or not by Instructor slug
            $checkInstructorSlug = Instructor::where('instructor_slug', $request->instructor_slug)->first();

            // If user has Existed
            if ($checkInstructorSlug) {
                // If user Email Exist
                return response()->json([
                    'error' => 'instructor slug has Exist'
                ], 401);
            }
            // check user has registered or not by mobile number
            $checkUserId = Instructor::where('user_id', $request->user_id)->first();

            // If user has Existed
            if ($checkUserId) {
                // If user Email Exist
                return response()->json([
                    'error' => 'user Id has Exist'
                ], 401);
            }
        }


        // Create instructor with Requests
        $instructor = new Instructor([
            'user_id' => $request->user_id,
            'instructor_slug' => $request->instructor_slug,
            'link_instagram' => $request->link_instagram,
            'link_facebook' => $request->link_facebook,
            'link_linkedin' => $request->link_linkedin,
            'link_twitter' => $request->link_twitter,
            'link_github' => $request->link_github,
            'link_youtube' => $request->link_youtube,
            'link_website' => $request->link_website,
            'skills' => $request->skils,
            'short_biography' => $request->short_biography,
            'biography' => $request->biography,
            'instructor_image' => $request->instructor_image,

        ]);
        $instructor->save();

        // Call Dispatch User Signup Actions
        // ProcessRegisterInstructor::dispatchAfterResponse($user);
        return response()->json([
            'data' => [
                'message' => 'instructor submit successful',
            ]
        ], 201);
    }

}
