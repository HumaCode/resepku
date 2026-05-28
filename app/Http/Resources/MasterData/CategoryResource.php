<?php

namespace App\Http\Resources\MasterData;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'parent' => $this->parent ? [
                'id' => $this->parent->id,
                'name' => $this->parent->name,
            ] : null,
            'is_active' => $this->is_active,
            'orders' => $this->orders,
            'views' => $this->views,
            'children_count' => $this->children_count ?? 0,
            'image_url' => $this->getFirstMediaUrl('image') ?: null,
        ];
    }
}
