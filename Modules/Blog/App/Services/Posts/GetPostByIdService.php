<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Repositories\Posts\GetPostByIdRepository;

class GetPostByIdService
{
    protected GetPostByIdRepository $postRepository;

    public function __construct(GetPostByIdRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Retrieve a post by its ID.
     *
     * @param  string  $id
     * @return Builder|array|Collection|Model
     */
    public function execute(string $id): Builder|array|Collection|Model
    {
        return $this->postRepository->execute($id);
    }
}
