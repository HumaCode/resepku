<?php

namespace App\Services\MasterData;

use App\Models\MasterData\Tag;
use App\Repositories\MasterData\TagRepositoryInterface;
use Illuminate\Support\Collection;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags(): Collection
    {
        return $this->tagRepository->getAllTags();
    }

    public function getPaginatedTags(array $filters, int $perPage = 10)
    {
        return $this->tagRepository->getPaginatedTags($filters, $perPage);
    }

    public function createTag(array $data): Tag
    {
        return $this->tagRepository->createTag($data);
    }

    public function updateTag(Tag $tag, array $data): Tag
    {
        return $this->tagRepository->updateTag($tag, $data);
    }

    public function deleteTag(Tag $tag)
    {
        return $this->tagRepository->deleteTag($tag);
    }

    public function toggleTagStatus(Tag $tag): Tag
    {
        return $this->tagRepository->toggleTagStatus($tag);
    }
}
