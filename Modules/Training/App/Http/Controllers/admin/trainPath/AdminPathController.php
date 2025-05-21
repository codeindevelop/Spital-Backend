<?php

namespace Modules\Training\App\Http\Controllers\admin\trainPath;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Instructor\Instructor;
use Modules\Training\App\Models\Path\TrainingPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminPathController extends Controller
{

    // Get all paths
    public function getAllPaths(): \Illuminate\Http\JsonResponse
    {
        $paths = TrainingPath::all();

        return response()->json([
            'data' => [
                "paths" => $paths
            ]
        ]);
    }


    public function getPathById($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user->can('TraningPath:view')) {
            $path = TrainingPath::where('id', $id)->first();

            if (!$path) {

                return response()->json([
                    'data' => [
                        "error" => 'cant find path with this id'
                    ]

                ], 404);
            }


            return response()->json([
                'data' => [
                    "path" => $path,
                ]

            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "error" => 'you dont have permission'

                ]

            ], 402);
        }
    }


    public function editPathById(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user->can('TraningPath:edit')) {
            $path = TrainingPath::where('id', $id)->first();

            if (!$path) {

                return response()->json([
                    'data' => [
                        "error" => 'cant find path with this id'
                    ]

                ], 404);
            }


            $path->update($request->all());


            return response()->json([
                'data' => [
                    "path" => $path,
                ]

            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "error" => 'you dont have permission'

                ]

            ], 402);
        }
    }

    public function createPath(Request $request): \Illuminate\Http\JsonResponse
    {

        $user = Auth::user();

        if ($user->can('TraningPath:create')) {

            $validator = Validator::make($request->all(), [

                'path_name' => ['required', 'unique:training_paths'],


            ]);

            // Check Validator Error
            if ($validator->fails()) {
                // check path_name
                $path_name = TrainingPath::where('path_name', $request->path_name)->first();

                // If user has Existed
                if ($path_name) {
                    // If $path_name Exist
                    return response()->json([
                        'error' => 'path name has Exist'
                    ], 401);
                }
            }

            $path = new TrainingPath([
                'path_name' => $request->path_name,
                'path_target_tech' => $request->path_target_tech,
                'short_desc' => $request->short_desc,
                'long_desc' => $request->long_desc,
                'active' => $request->active
            ]);

            $path->save();


            return response()->json([
                'data' => [
                    'message' => 'traning path submite successful',
                ]
            ], 201);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'You dont hve permission to this !',

                ]
            ], 402);
        }
    }
}
