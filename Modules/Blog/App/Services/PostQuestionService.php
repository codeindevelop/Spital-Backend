<?php

namespace Modules\Blog\App\Services;


use Modules\Blog\App\Models\PostQuestion;
use Modules\Blog\App\Repositories\PostQuestionRepository;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Log;

class PostQuestionService
{
    protected PostQuestionRepository $questionRepository;

    public function __construct(PostQuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function createQuestion(array $data, $user): PostQuestion
    {
        try {
            $questionData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $data['post_id'],
                'parent_id' => $data['parent_id'] ?? null,
                'author_name' => $data['author_name'] ?? null,
                'author_email' => $data['author_email'] ?? null,
                'author_url' => $data['author_url'] ?? null,
                'author_ip' => request()->ip(),
                'content' => $data['content'],
                'status' => $data['status'] ?? 'pending',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];

            return $this->questionRepository->createQuestion($questionData);
        } catch (\Exception $e) {
            Log::error('Error creating question: '.$e->getMessage(), [
                'data' => $data,
                'user' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
