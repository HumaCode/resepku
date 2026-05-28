<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreIngredientRequest;
use App\Http\Requests\MasterData\UpdateIngredientRequest;
use App\Http\Resources\MasterData\IngredientResource;
use App\Models\MasterData\Ingredient;
use App\Services\MasterData\IngredientService;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    protected $ingredientService;

    public function __construct(IngredientService $ingredientService)
    {
        $this->ingredientService = $ingredientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $all = $this->ingredientService->getAllIngredients();
        
        $statistics = [
            'total' => $all->count(),
            'active' => $all->where('is_active', '1')->count(),
            'categories' => $all->pluck('category')->unique()->count(),
            'inactive' => $all->where('is_active', '0')->count(),
        ];

        return view('pages.master-data.bahan.index', [
            'ingredients' => $all,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Fetch JSON list of resource (AJAX).
     */
    public function list(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'category' => $request->query('category'),
            'status' => $request->query('status'),
            'sort_by' => $request->query('sort_by'),
            'sort_order' => $request->query('sort_order'),
        ];
        $perPage = $request->query('per_page', 12);

        $paginated = $this->ingredientService->getPaginatedIngredients($filters, $perPage);
        $data = new \App\Http\Resources\PaginateResource($paginated, IngredientResource::class);

        // Fetch absolute stats for synchronization
        $all = $this->ingredientService->getAllIngredients();
        $statistics = [
            'total' => $all->count(),
            'active' => $all->where('is_active', '1')->count(),
            'categories' => $all->pluck('category')->unique()->count(),
            'inactive' => $all->where('is_active', '0')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => __('master-data/ingredient.messages.fetch_success'),
            'data' => $data->resolve(),
            'statistics' => $statistics,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIngredientRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->input('is_active', '0') === '1' ? '1' : '0';

        $ingredient = $this->ingredientService->createIngredient($validated);
        $data = new IngredientResource($ingredient);

        return ResponseHelper::jsonResponse(true, __('master-data/ingredient.messages.store_success'), $data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->input('is_active', '0') === '1' ? '1' : '0';

        $updated = $this->ingredientService->updateIngredient($ingredient, $validated);
        $data = new IngredientResource($updated);

        return ResponseHelper::jsonResponse(true, __('master-data/ingredient.messages.update_success'), $data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient): JsonResponse
    {
        $this->ingredientService->deleteIngredient($ingredient);
        return ResponseHelper::jsonResponse(true, __('master-data/ingredient.messages.delete_success'), null, 200);
    }

    /**
     * Toggle active status of ingredient.
     */
    public function toggleActive(Ingredient $ingredient): JsonResponse
    {
        $updated = $this->ingredientService->toggleActiveIngredient($ingredient);
        $data = new IngredientResource($updated);

        return ResponseHelper::jsonResponse(true, __('master-data/ingredient.messages.status_toggled'), $data, 200);
    }
}
