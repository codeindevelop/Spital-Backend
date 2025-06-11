<?php

namespace Modules\Page\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Page\App\Services\PageService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

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
            'status' => ['nullable', 'in:draft,published,archived'],
            'visibility' => ['nullable', 'in:public,private,unlisted'],
            'search' => ['nullable', 'string', 'max:255'],
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
        if (!$path) {
            return response()->json(['error' => 'مسیر صفحه مشخص نشده است.'], 400);
        }

        $slugs = explode('/', trim($path, '/'));

        if (empty($slugs)) {
            return response()->json(['error' => 'مسیر نامعتبر است.'], 400);
        }

        $validator = Validator::make(['slug' => end($slugs)], [
            'slug' => ['required', 'string', 'exists:pages,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        try {
            $page = $this->pageService->getPageByPath($slugs);

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
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'template' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'visibility' => ['nullable', 'in:public,private,unlisted'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'custom_css' => ['nullable', 'string'],
            'custom_js' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'uuid', 'exists:pages,id'],
            'seo' => ['nullable', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:255'],
            'seo.meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo.meta_description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'schema.type' => [
                'required_with:schema', 'string',
                'in:Website,Article,Product,FAQPage,Event,LocalBusiness,Organization,Review,Recipe,VideoObject,BreadcrumbList,Custom'
            ],
            'schema.title' => ['nullable', 'string', 'max:255'],
            'schema.slug' => ['nullable', 'string', 'max:255', 'unique:page_schemas,slug'],
            'schema.content' => ['nullable', 'string'],
            'schema.data' => ['nullable', 'array'],
            'schema.data.name' => [
                'required_if:schema.type,Website,Article,Product,LocalBusiness,Organization,Review,Recipe,VideoObject',
                'string', 'max:255'
            ],
            'schema.data.description' => ['nullable', 'string'],
            'schema.data.price' => ['required_if:schema.type,Product', 'numeric', 'min:0'],
            'schema.data.sku' => ['required_if:schema.type,Product', 'string', 'max:255'],
            'schema.data.faq' => ['required_if:schema.type,FAQPage', 'array'],
            'schema.data.faq.*.question' => ['string', 'max:255'],
            'schema.data.faq.*.answer' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $page = $this->pageService->createPage($request->all(), Auth::id());
            return response()->json([
                'data' => [
                    'page' => $page,
                    'message' => 'صفحه با موفقیت ایجاد شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Failed to create page: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد صفحه رخ داد.'], 500);
        }
    }

    public function updatePage(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (!Auth::user()->can('page:edit')) {
            return response()->json(['error' => 'شما اجازه ویرایش صفحه را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:pages,id'],
            'title' => ['string', 'max:255', 'unique:pages,title,'.$id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug,'.$id],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'template' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'visibility' => ['nullable', 'in:public,private,unlisted'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'custom_css' => ['nullable', 'string'],
            'custom_js' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'uuid', 'exists:pages,id'],
            'seo' => ['nullable', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:255'],
            'seo.meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo.meta_description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'schema.type' => [
                'required_with:schema', 'string',
                'in:Website,Article,Product,FAQPage,Event,LocalBusiness,Organization,Review,Recipe,VideoObject,BreadcrumbList,Custom'
            ],
            'schema.title' => ['nullable', 'string', 'max:255'],
            'schema.slug' => ['nullable', 'string', 'max:255'],
            'schema.content' => ['nullable', 'string'],
            'schema.data' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $page = $this->pageService->updatePage($id, $request->all(), Auth::id());
            return response()->json([
                'data' => [
                    'page' => $page,
                    'message' => 'صفحه با موفقیت به‌روزرسانی شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to update page: '.$e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی صفحه رخ داد.'], 500);
        }
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

        try {
            $this->pageService->deletePage($id);
            return response()->json([
                'data' => [
                    'message' => 'صفحه با موفقیت حذف شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to delete page: '.$e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در حذف صفحه رخ داد.'], 500);
        }
    }
}
