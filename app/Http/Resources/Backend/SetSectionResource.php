<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SetSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'data' => is_string($this['data']) ? json_decode($this['data']) : $this['data'],
            'title' => $this['title'],
            'type' => $this['type'],
            'show' => boolval($this['show']),

        ];
    }
}
