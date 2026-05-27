<?php

namespace App\Http\Resources\Pengguna;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'slug' => $this->slug,
            'type_role' => $this->type_role,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'users_count' => $this->users_count ?? 0,
            'permissions_count' => $this->permissions_count ?? 0,
        ];
    }
}
