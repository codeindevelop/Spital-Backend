<?php

namespace Modules\Blog\App\Helpers;

use Illuminate\Support\Str;
use Modules\Blog\App\Models\Post;


class SlugHelper
{
    public static function generatePersianSlug(
        string $text,
        string $model = Post::class,
        string $column = 'slug'
    ): string {
        // نرمال‌سازی متن: حذف کاراکترهای غیرمجاز و جایگزینی فاصله با خط تیره
        $slug = preg_replace('/\s+/u', '-', trim($text)); // جایگزینی فاصله‌ها با -
        $slug = preg_replace('/[^آ-ی۰-۹a-zA-Z\-]/u', '', $slug); // حذف کاراکترهای غیرمجاز
        $slug = Str::lower($slug); // تبدیل به حروف کوچک (برای یکنواختی)

        // بررسی یکتایی slug
        $originalSlug = $slug;
        $counter = 1;
        while ($model::where($column, $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }
}
