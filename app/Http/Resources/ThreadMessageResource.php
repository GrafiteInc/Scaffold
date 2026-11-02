<?php

namespace App\Http\Resources;

use App\Models\ThreadMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ThreadMessage
 */
class ThreadMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'thread_id' => $this->resource->thread_id,
            'role' => $this->resource->role,
            'channel' => $this->resource->channel,
            'content' => $this->resource->content,
            'tool_calls' => collect($this->resource->toolCalls())->pluck('name')->all(),
            'failed' => (bool) ($this->resource->meta['fallback'] ?? false),
            'created_at' => $this->resource->created_at,
        ];
    }
}
