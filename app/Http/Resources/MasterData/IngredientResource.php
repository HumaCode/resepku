<?php

namespace App\Http\Resources\MasterData;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
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
            'emoji' => $this->emoji,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'category_label' => $this->getCategoryLabel(),
            'default_unit' => $this->default_unit,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'views' => $this->views,
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }

    /**
     * Get human-readable label for category.
     */
    private function getCategoryLabel(): string
    {
        $labels = [
            'sayuran' => '🥦 Sayuran',
            'daging' => '🥩 Daging',
            'bumbu' => '🌶️ Bumbu & Rempah',
            'karbohidrat' => '🌾 Karbohidrat',
            'seafood' => '🦐 Seafood',
            'susu' => '🥛 Susu & Telur',
            'buah' => '🍎 Buah',
            'lainnya' => '📦 Lainnya',
        ];

        return $labels[$this->category] ?? '📦 Lainnya';
    }
}
