<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[Fillable(['name', 'guard_name', 'is_active'])]
class Permission extends SpatiePermission
{
    use HasUlids;

    /**
     * Get the menus associated with the permission.
     */
    public function menus()
    {
        return $this->belongsToMany(Konfigurasi\Menu::class, 'menu_has_permissions', 'permission_id', 'menu_id');
    }

    /**
     * Scope a query to search permissions by name or guard name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('guard_name', 'like', "%{$search}%");
        });
    }
}
