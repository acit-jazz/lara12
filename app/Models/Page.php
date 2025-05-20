<?php

namespace App\Models;

use App\Traits\GetSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Page extends Model
{
    use SoftDeletes;
    use GetSet;
    use HasSlug;


    protected $table = 'pages';
    protected $dates = ['deleted_at', 'published_at'];
    protected $casts = [
        'deleted_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'slug',
        'type',
        'template',
        'summary',
        'description',
        'position',
        'banners',
        'sections',
        'meta',
        'views',
        'published_at',
        'deleted_at',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug');
    }

    public static function paginateWithFilters($limit)
    {
        return app(Pipeline::class)
            ->send(Page::query())
            ->through([
                \App\QueryFilters\SortBy::class,
                \App\QueryFilters\Type::class,
                \App\QueryFilters\Trash::class,
                \App\QueryFilters\Except::class,
                \App\QueryFilters\Published::class,
                \App\QueryFilters\SearchTitle::class,
                \App\QueryFilters\SearchTitleDesc::class,
            ])
            ->thenReturn()
            ->paginate($limit)->withQueryString();
    }

    public static function allWithFilters()
    {
        return app(Pipeline::class)
            ->send(Page::query())
            ->through([
                \App\QueryFilters\SortBy::class,
                \App\QueryFilters\Type::class,
                \App\QueryFilters\Trash::class,
                \App\QueryFilters\Except::class,
                \App\QueryFilters\Published::class,
                \App\QueryFilters\SearchTitle::class,
                \App\QueryFilters\SearchTitleDesc::class,
            ])
            ->thenReturn()
            ->get();
    }
}
