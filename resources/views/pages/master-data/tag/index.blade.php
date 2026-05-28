<x-app-layout>
    @section('title', __('master-data/tag.title'))
    @section('page-title', __('master-data/tag.title'))

    @push('styles')
        @vite(['resources/css/backend/tag.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/tag.js'])
    @endpush

    <!-- Breadcrumb -->
    <x-breadcrumb-bar 
        title="{{ __('master-data/tag.title') }}"
        icon="bi-hash"
        desc="{{ __('master-data/tag.desc') }}"
        :items="[
            'Home' => route('dashboard'),
            'Master Data' => '#',
            __('master-data/tag.title') => null
        ]"
    />

    <!-- Stat Pills -->
    <div class="cat-stat-pills" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-pill pill-all">
            <i class="bi bi-hash"></i>
            <span>{{ __('master-data/tag.statistics.total') }}: <strong id="stat-total">{{ $statistics['total'] }}</strong></span>
        </div>
        <div class="stat-pill pill-active">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ __('master-data/tag.statistics.active') }}: <strong id="stat-active">{{ $statistics['active'] }}</strong></span>
        </div>
        <div class="stat-pill pill-hot">
            <i class="bi bi-fire"></i>
            <span>{{ __('master-data/tag.statistics.hot') }}: <strong id="stat-hot">{{ $statistics['hot'] }}</strong></span>
        </div>
        <div class="stat-pill pill-new">
            <i class="bi bi-stars"></i>
            <span>{{ __('master-data/tag.statistics.new') }}: <strong id="stat-new">{{ $statistics['new'] }}</strong></span>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="cat-toolbar" data-aos="fade-up" data-aos-delay="100">
        <!-- Search -->
        <div class="cat-search-wrap">
            <i class="bi bi-search"></i>
            <x-form-input type="text" class="cat-search" id="tagSearch" :placeholder="__('master-data/tag.toolbar.search_placeholder')" />
        </div>
        
        <!-- Filter Status -->
        <x-form-select class="cat-filter" id="tagStatusFilter">
            <option value="all">{{ __('master-data/tag.toolbar.filter_all_status') }}</option>
            <option value="active">{{ __('master-data/tag.toolbar.filter_active') }}</option>
            <option value="inactive">{{ __('master-data/tag.toolbar.filter_inactive') }}</option>
        </x-form-select>
        
        <!-- Filter Hot -->
        <x-form-select class="cat-filter d-none d-sm-block" id="tagHotFilter">
            <option value="all">{{ __('master-data/tag.toolbar.filter_all') }}</option>
            <option value="hot">{{ __('master-data/tag.toolbar.filter_hot') }}</option>
        </x-form-select>

        <!-- Reset Button -->
        <button class="btn-reset" onclick="resetFilters()" title="{{ __('master-data/tag.toolbar.btn_reset') }}">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span class="d-none d-sm-inline">{{ __('master-data/tag.toolbar.btn_reset') }}</span>
        </button>
        
        <!-- Tambah Button -->
        <button class="btn-tambah ms-auto" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('master-data/tag.toolbar.btn_add') }}</span>
            <span class="d-inline d-sm-none">{{ __('master-data/tag.toolbar.btn_add') }}</span>
        </button>
    </div>

    <!-- Table View -->
    <div class="tag-table-wrap" data-aos="fade-up" data-aos-delay="150">
        <div class="tag-table-responsive">
            <table class="tag-table">
                <thead>
                    <tr>
                        <th style="width:46px"></th><!-- dot warna -->
                        <th class="sortable" onclick="sortTable('name')">{{ __('master-data/tag.table.name') }} <i class="bi bi-chevron-expand"></i></th>
                        <th class="sortable d-none d-md-table-cell" style="width:130px" onclick="sortTable('slug')">{{ __('master-data/tag.table.slug') }} <i class="bi bi-chevron-expand"></i></th>
                        <th class="sortable" style="width:80px;text-align:center" onclick="sortTable('views')">{{ __('master-data/tag.table.views') }} <i class="bi bi-chevron-expand"></i></th>
                        <th style="width:95px">{{ __('master-data/tag.table.status') }}</th>
                        <th style="width:70px;text-align:center">{{ __('master-data/tag.table.hot') }}</th>
                        <th style="width:90px;text-align:center">{{ __('master-data/tag.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="tagTbody">
                    <!-- Will be loaded dynamically via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="tag-pagination" style="display:none">
        <div class="pag-info" id="paginationInfo"></div>
        <div class="pag-btns" id="paginationButtons"></div>
    </div>

    @include('pages.master-data.tag.partials.modals')
</x-app-layout>
