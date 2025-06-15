<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GenerateDemoPostsController extends Controller
{
    /**
     * Generate demo posts using the seeder.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:create')) {
            return response()->json(['error' => 'شما اجازه ایجاد پست را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'count' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $count = $request->input('count');
            Artisan::call('db:seed', [
                '--class' => 'PostSeeder',
                '--count' => $count,
            ]);

            return response()->json([
                'data' => [
                    'message' => "ایجاد $count پست دمو با موفقیت انجام شد.",
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to generate demo posts: '.$e->getMessage(), [
                'count' => $request->input('count'),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد پست‌های دمو رخ داد.'], 500);
        }
    }
}
