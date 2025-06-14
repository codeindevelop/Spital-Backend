<?php

namespace Modules\Seo\App\Models\post;

use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\App\Models\Post;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

/**
 * @method static create(array|string[] $array_merge)
 * @method static where(string $string, string $postId)
 */
class PostSchema extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'post_id',
        'type',
        'title',
        'slug',
        'content',
        'description',
        'data',
        'schema_json',
        'status',
        'visibility',
        'language',
        'author',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
        'schema_json' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Uuid::uuid4()->toString();
            }
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = SlugHelper::generatePersianSlug($model->title, self::class, 'slug');
            }
        });
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
