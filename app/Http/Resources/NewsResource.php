<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ([
            'id'=>$this->id,
            'name'=>$this->title,
            'content'=>$this->description,
            'img_path'=>$this->imgNewsPath,
            'created_at'=> $this->created_at,
            'author'=>new UserResource($this->whenLoaded('user'))
        ]);
    }
}
