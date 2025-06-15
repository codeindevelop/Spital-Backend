<?php

namespace App\Helpers\blog;

use Illuminate\Support\Str;
use Modules\Blog\App\Models\Post;


class PostShortLinkHelper
{
    public static function generateShortLink(string $postId): string
    {
        $baseUrl = config('app.url').'/s/';
        do {
            $code = Str::random(6);
            $shortLink = $baseUrl.$code;
        } while (Post::where('short_link', $shortLink)->exists());

        return $shortLink;
    }
}
