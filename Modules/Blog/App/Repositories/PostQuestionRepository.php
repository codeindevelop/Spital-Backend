<?php

namespace Modules\Blog\App\Repositories;


use Modules\Blog\App\Models\PostQuestion;

class PostQuestionRepository
{
    public function createQuestion(array $data): PostQuestion
    {
        return PostQuestion::create($data);
    }


}
