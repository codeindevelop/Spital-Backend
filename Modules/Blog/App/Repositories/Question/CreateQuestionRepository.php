<?php

namespace Modules\Blog\App\Repositories\Question;

use Modules\Blog\App\Models\PostQuestion;

class CreateQuestionRepository
{
    /**
     * Create a new question for a post.
     *
     * @param  array  $data
     * @return PostQuestion
     */
    public function execute(array $data): PostQuestion
    {
        return PostQuestion::create($data);
    }
}
