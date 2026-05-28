<?php

namespace App\Services\MasterData;

use App\Models\MasterData\Category;
use App\Repositories\MasterData\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getPaginatedCategories(array $filters, int $perPage = 6)
    {
        return $this->categoryRepository->getPaginatedCategories($filters, $perPage);
    }

    public function getParentCategories(): Collection
    {
        return $this->categoryRepository->getParentCategories();
    }

    public function createCategory(array $data): Category
    {
        return $this->categoryRepository->createCategory($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        return $this->categoryRepository->updateCategory($category, $data);
    }

    public function deleteCategory(Category $category)
    {
        return $this->categoryRepository->deleteCategory($category);
    }

    public function toggleCategoryStatus(Category $category): Category
    {
        return $this->categoryRepository->toggleCategoryStatus($category);
    }
}
