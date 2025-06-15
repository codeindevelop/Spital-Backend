<?php

namespace Modules\Blog\App\Http\Controllers\User\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Blog\App\Services\Posts\UnbookmarkPostService;
use Symfony\Component\HttpFoundation\Response;

class UnbookmarkPostController extends Controller
{
    protected UnbookmarkPostService $postService;

    public function __construct(UnbookmarkPostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Remove a bookmark from a post for the authenticated user.
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
                'data' => ['message' => 'بوکمارک پست با موفقیت حذف شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
