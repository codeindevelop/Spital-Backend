<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Repositories\Posts\GetPostBySlugRepository;

class GetPostBySlugService
{
    protected GetPostBySlugRepository $postRepository;

    public function __construct(GetPostBySlugRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Retrieve a post by its slug.
     *
     * @param  string  $slug
     * @return Builder|Model
     */
    public function execute(string $slug): Builder|Model
    {
        return $this->postRepository->execute($slug);
    }
}
