<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
