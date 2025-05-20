<?php

namespace App\Http\Resources\Frontend\Section;

use App\Http\Resources\BaseResource;

class CardResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource;
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }
        return [
           'title' => $data['title'] ?? null,
           'summary' => $data['summary'] ?? null,
           'image' => getImage($data['image'] ?? null),
           'image_alt' => getAltImage($data['image'] ?? null),
           'icon' => getImage($data['icon'] ?? null),
           'icon_alt' => getAltImage($data['image'] ?? null),
           'text_color' => $data['text_color'] ?? null,
           'bg_color' => $data['bg_color'] ?? null,
           'url' => $data['url'] ?? null,
        ];
    }
}
