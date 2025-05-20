<?php

namespace App\Http\Resources\Frontend\Section;

use App\Http\Resources\BaseResource;

class InputCallToActionResource extends BaseResource
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
           'btn_text' => $data['btn_text'] ?? null,
           'btn_url' => $data['btn_url'] ?? null,
           'image' => getImage($data['image']),
           'image_mobile' => getImage($data['image_mobile']),
           'image_alt' => getAltImage($data['image']),
           'image_mobile_alt' => getAltImage($data['image_mobile']),
           'is_image_mp4' => getExt($data['image']) == 'mp4' ? true : false,
           'is_mobile_mp4' => getExt($data['image_mobile']) == 'mp4' ? true : false,
        ];
    }
}
