<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    use HasUlids;

    /**
     * Scope a query to search media by name, file name, mime type, or collection name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('file_name', 'like', "%{$search}%")
                ->orWhere('mime_type', 'like', "%{$search}%")
                ->orWhere('collection_name', 'like', "%{$search}%");
        });
    }
}
