<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['recipe_id', 'video_url', 'video_provider', 'orders'])]
class RecipeVideo extends Model
{
    use HasUlids;

    /**
     * Get the recipe that owns the video.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
