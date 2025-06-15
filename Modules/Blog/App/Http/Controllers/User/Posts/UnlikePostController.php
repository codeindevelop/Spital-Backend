<?php

namespace Modules\Blog\App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Blog\App\Services\Posts\UnlikePostService;
use Symfony\Component\HttpFoundation\Response;

class UnlikePostController extends Controller
{
    protected UnlikePostService $postService;

    public function __construct(UnlikePostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Unlike a post for the authenticated user.
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
                'data' => ['message' => 'لایک پست با موفقیت حذف شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
