<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResponse extends JsonResource
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
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "avatar" => $this->resource->avatar,
            "email" => $this->resource->email,
            "dark_mode" => (bool) $this->resource->dark_mode,
            "email_verified_at" => $this->resource->email_verified_at,
            "created_at" => $this->resource->created_at,
            "updated_at" => $this->resource->updated_at,
            "avatar_url" => (string) $this->resource->avatar_url,
        ];
    }
}
