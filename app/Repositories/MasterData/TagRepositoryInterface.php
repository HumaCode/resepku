<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Tag;
use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
    /**
     * Get all tags.
     *
     * @return Collection
     */
    public function getAllTags(): Collection;

    /**
     * Get paginated tags with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedTags(array $filters, int $perPage = 10);

    /**
     * Create a new tag.
     *
     * @param array $data
     * @return Tag
     */
    public function createTag(array $data): Tag;

    /**
     * Update an existing tag.
     *
     * @param Tag $tag
     * @param array $data
     * @return Tag
     */
    public function updateTag(Tag $tag, array $data): Tag;

    /**
     * Delete a tag.
     *
     * @param Tag $tag
     * @return bool|null
     */
    public function deleteTag(Tag $tag);

    /**
     * Toggle the active status of a tag.
     *
     * @param Tag $tag
     * @return Tag
     */
    public function toggleTagStatus(Tag $tag): Tag;
}
