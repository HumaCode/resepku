<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Tag;
use Illuminate\Support\Collection;

class TagRepository implements TagRepositoryInterface
{
    /**
     * Get all tags.
     *
     * @return Collection
     */
    public function getAllTags(): Collection
    {
        return Tag::orderBy('name')->get();
    }

    /**
     * Get paginated tags with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedTags(array $filters, int $perPage = 10)
    {
        $query = Tag::query();

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $status = $filters['status'] === 'active' ? '1' : '0';
            $query->where('is_active', $status);
        }

        if (isset($filters['hot']) && $filters['hot'] !== 'all') {
            $hot = $filters['hot'] === 'hot' ? '1' : '0';
            $query->where('is_hot', $hot);
        }

        // Apply dynamic sorting if passed, but default to name asc
        $sortBy = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        
        if (in_array($sortBy, ['name', 'slug', 'views'])) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name', 'asc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new tag.
     *
     * @param array $data
     * @return Tag
     */
    public function createTag(array $data): Tag
    {
        return Tag::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'color' => $data['color'] ?? '#ef4444',
            'is_hot' => $data['is_hot'] ?? '0',
            'is_active' => $data['is_active'] ?? '1',
            'views' => $data['views'] ?? 0,
        ]);
    }

    /**
     * Update an existing tag.
     *
     * @param Tag $tag
     * @param array $data
     * @return Tag
     */
    public function updateTag(Tag $tag, array $data): Tag
    {
        $tag->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'color' => $data['color'] ?? $tag->color,
            'is_hot' => $data['is_hot'] ?? $tag->is_hot,
            'is_active' => $data['is_active'] ?? $tag->is_active,
        ]);

        return $tag;
    }

    /**
     * Delete a tag.
     *
     * @param Tag $tag
     * @return bool|null
     */
    public function deleteTag(Tag $tag)
    {
        return $tag->delete();
    }

    /**
     * Toggle the active status of a tag.
     *
     * @param Tag $tag
     * @return Tag
     */
    public function toggleTagStatus(Tag $tag): Tag
    {
        $tag->is_active = $tag->is_active === '1' ? '0' : '1';
        $tag->save();
        return $tag;
    }
}
