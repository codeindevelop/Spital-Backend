<?php

namespace Modules\Blog\App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Blog\App\Services\Posts\LikePostService;
use Symfony\Component\HttpFoundation\Response;

class LikePostController extends Controller
{
    protected LikePostService $postService;

    public function __construct(LikePostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Like a post for the authenticated user.
     *
     * @param  Request  $request
     * @param  string  $postId
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $postId): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        try {
            $this->postService->execute($postId, $user->id);
            return response()->json([
                'data' => ['message' => 'پست با موفقیت لایک شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
