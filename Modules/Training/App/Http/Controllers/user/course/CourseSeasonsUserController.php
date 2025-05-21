<?php

namespace Modules\Training\App\Http\Controllers\user\course;

use App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\Content\CourseSeason;
use Illuminate\Http\Request;

class CourseSeasonsUserController extends Controller
{
    // Get all CourseSeason
    public function index(): \Illuminate\Http\JsonResponse
    {
        $CourseSeason = CourseSeason::all();
        $SeasonCount = $CourseSeason->count();
        return response()->json([
            'data' => [
                "seasons" => $CourseSeason,
                "SeasonsCount" => $SeasonCount
            ]

        ], 200);
    }
}
