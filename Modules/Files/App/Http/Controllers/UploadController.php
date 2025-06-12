<?php

namespace Modules\Files\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class UploadController extends Controller
{
    public function uploadSeoImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png|max:2048', // حداکثر 2MB
        ]);

        $pageId = Uuid::uuid4()->toString(); // موقت برای نام‌گذاری
        $file = $request->file('image');
        $filename = 'og_image_'.$pageId.'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("public/pages/seo/{$pageId}", $filename);

        $url = Storage::url($path);

        return response()->json([
            'url' => config('app.url').$url,
        ], 200);
    }
}
