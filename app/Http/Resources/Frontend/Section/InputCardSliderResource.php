<?php

namespace App\Http\Resources\Frontend\Section;

use App\Http\Resources\BaseResource;

class InputCardSliderResource extends BaseResource
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
           'cards' => CardResource::collection($data['cards']),
        ];
    }
}
