<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;

class Media extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $table = 'medias';
    protected $dates = ['deleted_at'];
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = ['name',
                           'slug',
                           'thumbnail',
                           'extension',
                           'filename',
                           'size',
                           'path',
                           'url'];


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug');
    }


    public static function paginateWithFilters($limit)
    {
        return app(Pipeline::class)
            ->send(Media::query())
            ->through([
                \App\QueryFilters\UserId::class,
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
            ->send(Media::query())
            ->through([
                \App\QueryFilters\UserId::class,
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
