<?php

namespace Modules\Blog\App\Services\Posts;

use Modules\Blog\App\Repositories\Posts\DeletePostRepository;

class DeletePostService
{
    protected DeletePostRepository $postRepository;

    public function __construct(DeletePostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Delete a post by its ID.
     *
     * @param  string  $id
     * @return void
     */
    public function execute(string $id): void
    {
        $this->postRepository->execute($id);
    }
}
