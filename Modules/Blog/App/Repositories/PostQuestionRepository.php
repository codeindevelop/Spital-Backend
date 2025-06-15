<?php

namespace Modules\Blog\App\Repositories;

use Modules\Settings\App\Models\Blog\PostQuestion;

class PostQuestionRepository
{
    public function createQuestion(array $data): PostQuestion
    {
        return PostQuestion::create($data);
    }


}
