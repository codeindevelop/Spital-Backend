<?php

namespace Modules\Blog\App\Http\Controllers\Public\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Blog\App\Services\Posts\GetPostByShortLinkService;

class RedirectShortLinkController extends Controller
{
    protected GetPostByShortLinkService $postService;

    public function __construct(GetPostByShortLinkService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Redirect to a post using its short link code.
     *
     * @param  string  $code
     * @return RedirectResponse
     */
    public function __invoke(string $code): RedirectResponse
    {
        $post = $this->postService->execute(config('app.url').'/s/'.$code);
        if (!$post) {
            abort(404, 'پست یافت نشد.');
        }

        return redirect()->to("/posts/{$post->slug}");
    }
}
