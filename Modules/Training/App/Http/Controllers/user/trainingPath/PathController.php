<?php

namespace Modules\Training\App\Http\Controllers\user\trainingPath;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Path\TrainingPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PathController extends Controller
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
    }
}
