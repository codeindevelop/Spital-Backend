<?php

namespace Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Page\App\Models\Page;
use Modules\User\App\Models\User;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $pageId)
 */
class PageSchema extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'page_schemas';

    protected $fillable = [
        'page_id', 'created_by', 'title', 'slug', 'content', 'description', 'category',
        'tags', 'status', 'visibility', 'access_level', 'type', 'version', 'order',
        'keywords', 'language', 'region', 'timezone', 'icon', 'featured_image',
        'thumbnail_image', 'banner_image', 'video_url', 'audio_url', 'external_link',
        'template', 'layout', 'theme', 'author', 'publisher', 'date_published',
        'date_modified', 'schema_data', 'schema_markup', 'schema_json', 'schema_rich_snippet',
        'schema_microdata', 'schema_opengraph', 'schema_twitter_card', 'schema_json_ld',
        'schema_rdfa', 'schema_knowledge_graph', 'schema_breadcrumb', 'schema_faq',
        'schema_event', 'schema_product', 'schema_article', 'schema_local_business',
        'schema_person', 'schema_organization', 'schema_website', 'schema_video',
        'schema_audio', 'schema_image', 'schema_review', 'schema_recipe', 'schema_job_posting',
        'schema_aggregate_rating', 'schema_software_app', 'schema_service',
        'schema_corporate_contact', 'schema_contact_point', 'schema_sitelinks_searchbox',
        'schema_sitelinks', 'schema_sitemap', 'schema_sitemap_index', 'schema_sitemap_image',
        'schema_sitemap_video', 'schema_sitemap_news', 'schema_sitemap_mobile',
        'schema_sitemap_web', 'schema_sitemap_other', 'schema_custom', 'schema_custom_json',
        'schema_custom_html', 'schema_custom_rdfa', 'schema_custom_microdata',
        'schema_custom_opengraph', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
