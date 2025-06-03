<?php

namespace Modules\Page\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Page\App\Services\PageService;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function getAllPages(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('page:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده صفحات را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'status' => ['in:draft,published,archived'],
            'visibility' => ['in:public,private,unlisted'],
            'search' => ['string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $pages = $this->pageService->getAllPages(
            $request->input('per_page', 15),
            $request->input('status'),
            $request->input('visibility'),
            $request->input('search')
        );

        return response()->json([
            'data' => [
                'pages' => $pages,
            ],
        ], Response::HTTP_OK);
    }

    public function getPageById($id): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('page:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده صفحات را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:pages,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $page = $this->pageService->getPageById($id);

        return response()->json([
            'data' => [
                'page' => $page,
            ],
        ], Response::HTTP_OK);
    }

    public function getPageBySlug($slug): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'exists:pages,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        $page = $this->pageService->getPageBySlug($slug);

        if ($page->visibility === 'private' && !Auth::check()) {
            return response()->json(['error' => 'این صفحه خصوصی است.'], 403);
        }

        return response()->json([
            'data' => [
                'page' => $page,
            ],
        ], Response::HTTP_OK);
    }

    public function getPageByPath($path = null): \Illuminate\Http\JsonResponse
    {
        // اگر path خالی باشه، خطا برگردون
        if (!$path) {
            return response()->json(['error' => 'مسیر صفحه مشخص نشده است.'], 400);
        }

        // مسیر رو به slugهای جداگانه بشکن
        $slugs = explode('/', trim($path, '/'));

        // حداقل باید یه slug داشته باشیم
        if (empty($slugs)) {
            return response()->json(['error' => 'مسیر نامعتبر است.'], 400);
        }

        // اعتبارسنجی slug آخر (صفحه فرزند)
        $validator = Validator::make(['slug' => end($slugs)], [
            'slug' => ['required', 'string', 'exists:pages,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        try {
            // پیدا کردن صفحه با مسیر کامل
            $page = $this->pageService->getPageByPath($slugs);

            // چک دسترسی به صفحه
            if ($page->visibility === 'private' && !Auth::check()) {
                return response()->json(['error' => 'این صفحه خصوصی است.'], 403);
            }

            return response()->json([
                'data' => [
                    'page' => $page,
                    'parent' => $page->parent ? [
                        'id' => $page->parent->id,
                        'title' => $page->parent->title,
                        'slug' => $page->parent->slug,
                        'seo' => $page->parent->seo,
                    ] : null,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function createPage(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('page:create')) {
            return response()->json(['error' => 'شما اجازه ایجاد صفحه را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'unique:pages,title'],
            'slug' => ['string', 'max:255', 'unique:pages,slug', 'nullable'],
            'description' => ['string', 'nullable'],
            'content' => ['string', 'nullable'],
            'order' => ['integer', 'min:0'],
            'template' => ['string', 'max:255', 'nullable'],
            'status' => ['in:draft,published,archived'],
            'visibility' => ['in:public,private,unlisted'],
            'custom_css' => ['string', 'nullable'],
            'custom_js' => ['string', 'nullable'],
            'published_at' => ['date', 'nullable'],
            'is_active' => ['boolean'],
            'parent_id' => ['uuid', 'exists:pages,id', 'nullable'],
            'seo' => ['array', 'nullable'],
            'seo.meta_title' => ['string', 'max:255', 'nullable'],
            'seo.meta_keywords' => ['string', 'max:255', 'nullable'],
            'seo.meta_description' => ['string', 'nullable'],
            'schema' => ['array', 'nullable'],
            'schema.title' => ['string', 'max:255', 'nullable'],
            'schema.slug' => ['string', 'max:255', 'unique:page_schemas,slug', 'nullable'],
            'schema.content' => ['string', 'nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $page = $this->pageService->createPage($request->all(), Auth::user()->id);

        return response()->json([
            'data' => [
                'page' => $page,
                'message' => 'صفحه با موفقیت ایجاد شد.',
            ],
        ], Response::HTTP_CREATED);
    }

    public function updatePage(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('page:edit')) {
            return response()->json(['error' => 'شما اجازه ویرایش صفحه را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:pages,id'],
            'title' => ['string', 'max:255', 'unique:pages,title,'.$id],
            'slug' => ['string', 'max:255', 'unique:pages,slug,'.$id, 'nullable'],
            'description' => ['string', 'nullable'],
            'content' => ['string', 'nullable'],
            'order' => ['integer', 'min:0'],
            'template' => ['string', 'max:255', 'nullable'],
            'status' => ['in:draft,published,archived'],
            'visibility' => ['in:public,private,unlisted'],
            'custom_css' => ['string', 'nullable'],
            'custom_js' => ['string', 'nullable'],
            'published_at' => ['date', 'nullable'],
            'is_active' => ['boolean'],
            'parent_id' => ['uuid', 'exists:pages,id', 'nullable'],
            'seo' => ['array', 'nullable'],
            'seo.meta_title' => ['string', 'max:255', 'nullable'],
            'seo.meta_keywords' => ['string', 'max:255', 'nullable'],
            'seo.meta_description' => ['string', 'nullable'],
            'schema' => ['array', 'nullable'],
            'schema.title' => ['string', 'max:255', 'nullable'],
            'schema.slug' => ['string', 'max:255', 'unique:page_schemas,slug,'.$id.',page_id', 'nullable'],
            'schema.content' => ['string', 'nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $page = $this->pageService->updatePage($id, $request->all(), Auth::user()->id);

        return response()->json([
            'data' => [
                'page' => $page,
                'message' => 'صفحه با موفقیت به‌روزرسانی شد.',
            ],
        ], Response::HTTP_OK);
    }

    public function deletePage($id): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('page:delete')) {
            return response()->json(['error' => 'شما اجازه حذف صفحه را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:pages,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $this->pageService->deletePage($id);

        return response()->json([
            'data' => [
                'message' => 'صفحه با موفقیت حذف شد.',
            ],
        ], Response::HTTP_OK);
    }
}
