<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\BaseResource;
use Illuminate\Support\Carbon;

class PageResource extends BaseResource
{
   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
   public function toArray($request)
   {
      return [
        'id' => $this->id,
        'title' => $this->title,
        'summary' => $this->summary,
        'description' => $this->description,
        'slug' => $this->slug,
        'template' => $this->template,
        'banners' => getImage($this->banners),
        'banners_alt' => getAltImage($this->banners),
        'sections'=> $this->sections ? SectionResource::collection($this->sections)->resolve() : null,
        'meta'=> $this->meta,
        'published_at'=> $this->published_at ? Carbon::parse($this->published_at)->format('d/m/Y') : null,
      ];
   }
}
