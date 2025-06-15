<?php

namespace Modules\Blog\App\Services\Question;

use Modules\Blog\App\Models\PostQuestion;
use Modules\Blog\App\Repositories\PostQuestionRepository;
use Modules\User\App\Models\User;


class CreateQuestionService
{
    protected PostQuestionRepository $questionRepository;

    public function __construct(PostQuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }


    public function execute(array $data, object $user): PostQuestion
    {
        return $this->questionRepository->createQuestion([
            'post_id' => $data['post_id'],
            'parent_id' => $data['parent_id'] ?? null,
            'user_id' => $user->id,
            'author_name' => $data['author_name'] ?? $user->email,
            'author_email' => $data['author_email'] ?? $user->email,
            'author_url' => $data['author_url'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'] ?? 'pending',
        ]);
    }
}
