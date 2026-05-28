<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Services\MasterData\CategoryService;
use App\Http\Requests\MasterData\StoreCategoryRequest;
use App\Http\Requests\MasterData\UpdateCategoryRequest;
use App\Http\Resources\MasterData\CategoryResource;
use App\Helpers\ResponseHelper;
use App\Models\MasterData\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->categoryService->getAllCategories();
        $parentCategories = $this->categoryService->getParentCategories();

        $statistics = [
            'total' => $categories->count(),
            'active' => $categories->where('is_active', '1')->count(),
            'inactive' => $categories->where('is_active', '0')->count(),
            'sub' => $categories->whereNotNull('parent_id')->count(),
        ];

        return view('pages.master-data.kategori.index', compact('categories', 'parentCategories', 'statistics'));
    }

    /**
     * Get paginated categories via AJAX.
     */
    public function getCategories(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'type' => $request->query('type'),
        ];
        $perPage = $request->query('per_page', 6);

        $paginated = $this->categoryService->getPaginatedCategories($filters, $perPage);
        $data = new \App\Http\Resources\PaginateResource($paginated, CategoryResource::class);

        // Fetch absolute stats to keep pill counters synchronized
        $allCategories = $this->categoryService->getAllCategories();
        $statistics = [
            'total' => $allCategories->count(),
            'active' => $allCategories->where('is_active', '1')->count(),
            'inactive' => $allCategories->where('is_active', '0')->count(),
            'sub' => $allCategories->whereNotNull('parent_id')->count(),
        ];

        $parents = $this->categoryService->getParentCategories();

        return response()->json([
            'success' => true,
            'message' => __('master-data/category.messages.fetch_success'),
            'data' => $data->resolve(),
            'statistics' => $statistics,
            'parents' => CategoryResource::collection($parents),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());
        $data = new CategoryResource($category);

        return ResponseHelper::jsonResponse(true, __('master-data/category.messages.store_success'), $data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $updatedCategory = $this->categoryService->updateCategory($category, $request->validated());
        $data = new CategoryResource($updatedCategory);

        return ResponseHelper::jsonResponse(true, __('master-data/category.messages.update_success'), $data, 200);
    }

    /**
     * Toggle status active of category.
     */
    public function toggleActive(Category $category): JsonResponse
    {
        $updatedCategory = $this->categoryService->toggleCategoryStatus($category);
        $data = new CategoryResource($updatedCategory);

        $statusMsg = $updatedCategory->is_active === '1' 
            ? __('master-data/category.messages.toggle_active') 
            : __('master-data/category.messages.toggle_inactive');

        return ResponseHelper::jsonResponse(true, $statusMsg, $data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);

        return ResponseHelper::jsonResponse(true, __('master-data/category.messages.delete_success'), null, 200);
    }
}
