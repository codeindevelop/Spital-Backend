<?php

namespace Modules\Blog\App\Http\Controllers\User\Questions;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Modules\Blog\App\Services\Question\CreateQuestionService;
use Symfony\Component\HttpFoundation\Response;

class CreateQuestionController extends Controller
{
    protected CreateQuestionService $questionService;

    public function __construct(CreateQuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Create a new question for a post.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'content' => ['required', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_questions,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'author_url' => ['nullable', 'url', 'max:255'],
//            'status' => ['nullable', 'in:pending,approved,spam'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $question = $this->questionService->execute($request->all(), $user);
            return response()->json([
                'data' => [
                    'question' => $question,
                    'message' => 'سوال با موفقیت ثبت شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Failed to create question: '.$e->getMessage(), [
                'request' => $request->all(),
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ثبت سوال رخ داد.'], 500);
        }
    }
}
