<?php

namespace App\Http\Resources\Konten;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'difficulty' => $this->difficulty,
            'prep_time' => $this->prep_time,
            'cook_time' => $this->cook_time,
            'servings' => $this->servings,
            'rating' => $this->rating ?? 0.0,
            'views' => $this->views ?? 0,
            'is_featured' => $this->is_featured,
            'cover_url' => $this->getFirstMediaUrl('cover'),
            'category' => [
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],
            'author' => [
                'name' => $this->user?->name,
            ],
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'videos' => $this->videos,
            'tags' => $this->tags->pluck('name'),
        ];
    }
}
