<?php

namespace App\Http\Resources\Frontend\Section;

use App\Http\Resources\BaseResource;

class InputBannersResource extends BaseResource
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
           'subtitle' => $data['subtitle'] ?? null,
           'summary' => $data['summary'] ?? null,
           'btn_text' => $data['btn_text'] ?? null,
           'btn_url' => $data['btn_url'] ?? null,
           'desktop' => getImage($data['desktop']),
           'mobile' => getImage($data['mobile']),
           'desktop_alt' => getAltImage($data['desktop']),
           'mobile_alt' => getAltImage($data['mobile']),
           'is_desktop_mp4' => getExt($data['desktop']) == 'mp4' ? true : false,
           'is_mobile_mp4' => getExt($data['mobile']) == 'mp4' ? true : false,
        ];
    }
}
