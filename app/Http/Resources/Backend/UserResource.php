<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'company' => $this->company,
            'phone' => $this->phone,
            'pic' => $this->pic,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'slug' => $this->slug,
         ];
    }
}
