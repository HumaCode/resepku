<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Services\MasterData\TagService;
use App\Http\Requests\MasterData\StoreTagRequest;
use App\Http\Requests\MasterData\UpdateTagRequest;
use App\Http\Resources\MasterData\TagResource;
use App\Helpers\ResponseHelper;
use App\Models\MasterData\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tags = $this->tagService->getAllTags();

        $statistics = [
            'total' => $tags->count(),
            'active' => $tags->where('is_active', '1')->count(),
            'hot' => $tags->where('is_hot', '1')->count(),
            'new' => $tags->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('pages.master-data.tag.index', compact('tags', 'statistics'));
    }

    /**
     * Get paginated tags via AJAX.
     */
    public function getTags(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'hot' => $request->query('hot'),
            'sort_by' => $request->query('sort_by'),
            'sort_order' => $request->query('sort_order'),
        ];
        $perPage = $request->query('per_page', 10);

        $paginated = $this->tagService->getPaginatedTags($filters, $perPage);
        $data = new \App\Http\Resources\PaginateResource($paginated, TagResource::class);

        // Fetch absolute stats to keep pill counters synchronized
        $allTags = $this->tagService->getAllTags();
        $statistics = [
            'total' => $allTags->count(),
            'active' => $allTags->where('is_active', '1')->count(),
            'hot' => $allTags->where('is_hot', '1')->count(),
            'new' => $allTags->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => __('master-data/tag.messages.fetch_success'),
            'data' => $data->resolve(),
            'statistics' => $statistics,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $validated = $request->validated();
        // Default values for boolean/checkbox fields if not sent
        $validated['is_hot'] = $request->input('is_hot', '0') === '1' ? '1' : '0';
        $validated['is_active'] = $request->input('is_active', '0') === '1' ? '1' : '0';

        $tag = $this->tagService->createTag($validated);
        $data = new TagResource($tag);

        return ResponseHelper::jsonResponse(true, __('master-data/tag.messages.store_success'), $data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_hot'] = $request->input('is_hot', '0') === '1' ? '1' : '0';
        $validated['is_active'] = $request->input('is_active', '0') === '1' ? '1' : '0';

        $updatedTag = $this->tagService->updateTag($tag, $validated);
        $data = new TagResource($updatedTag);

        return ResponseHelper::jsonResponse(true, __('master-data/tag.messages.update_success'), $data, 200);
    }

    /**
     * Toggle status active of tag.
     */
    public function toggleActive(Tag $tag): JsonResponse
    {
        $updatedTag = $this->tagService->toggleTagStatus($tag);
        $data = new TagResource($updatedTag);

        $statusMsg = $updatedTag->is_active === '1' 
            ? __('master-data/tag.messages.toggle_active') 
            : __('master-data/tag.messages.toggle_inactive');

        return ResponseHelper::jsonResponse(true, $statusMsg, $data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $this->tagService->deleteTag($tag);

        return ResponseHelper::jsonResponse(true, __('master-data/tag.messages.delete_success'), null, 200);
    }
}
