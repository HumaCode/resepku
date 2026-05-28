<x-app-layout>
    @section('title', __('master-data/category.title'))
    @section('page-title', __('master-data/category.title'))

    @push('styles')
        @vite(['resources/css/backend/kategori.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/kategori.js'])
    @endpush

    <!-- Breadcrumb -->
    <x-breadcrumb-bar 
        title="{{ __('master-data/category.title') }}"
        icon="bi-tag-fill"
        desc="{{ __('master-data/category.desc') }}"
        :items="[
            'Home' => route('dashboard'),
            'Master Data' => '#',
            __('master-data/category.title') => null
        ]"
    />

    <!-- Stat Pills -->
    <div class="cat-stat-pills" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-pill pill-all">
            <i class="bi bi-grid-fill"></i>
            <span>{{ __('master-data/category.statistics.total') }}: <strong id="stat-total">{{ $statistics['total'] }}</strong></span>
        </div>
        <div class="stat-pill pill-active">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ __('master-data/category.statistics.active') }}: <strong id="stat-active">{{ $statistics['active'] }}</strong></span>
        </div>
        <div class="stat-pill pill-inactive">
            <i class="bi bi-x-circle-fill"></i>
            <span>{{ __('master-data/category.statistics.inactive') }}: <strong id="stat-inactive">{{ $statistics['inactive'] }}</strong></span>
        </div>
        <div class="stat-pill pill-parent">
            <i class="bi bi-diagram-2-fill"></i>
            <span>{{ __('master-data/category.statistics.sub') }}: <strong id="stat-sub">{{ $statistics['sub'] }}</strong></span>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="cat-toolbar" data-aos="fade-up" data-aos-delay="100">
        <!-- Search -->
        <div class="cat-search-wrap">
            <i class="bi bi-search"></i>
            <x-form-input type="text" class="cat-search" id="catSearch" :placeholder="__('master-data/category.toolbar.search_placeholder')" />
        </div>
        
        <!-- Filter status -->
        <x-form-select class="cat-filter" id="catFilter">
            <option value="all">{{ __('master-data/category.toolbar.filter_all_status') }}</option>
            <option value="active">{{ __('master-data/category.toolbar.filter_active') }}</option>
            <option value="inactive">{{ __('master-data/category.toolbar.filter_inactive') }}</option>
        </x-form-select>
        
        <!-- Filter parent -->
        <x-form-select class="cat-filter d-none d-sm-block" id="catParentFilter">
            <option value="all">{{ __('master-data/category.toolbar.filter_all_types') }}</option>
            <option value="parent">{{ __('master-data/category.toolbar.filter_parent') }}</option>
            <option value="child">{{ __('master-data/category.toolbar.filter_child') }}</option>
        </x-form-select>
        
        <!-- Reset Button -->
        <button class="btn-reset" id="btnResetFilters" onclick="resetFilters()" title="{{ __('master-data/category.toolbar.btn_reset') }}">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span class="d-none d-sm-inline">{{ __('master-data/category.toolbar.btn_reset') }}</span>
        </button>
        
        <!-- View toggle -->
        <div class="view-toggle ms-auto">
            <button class="view-btn active" id="btnGrid" onclick="setView('grid')" title="Grid View">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>
            <button class="view-btn" id="btnTable" onclick="setView('table')" title="Table View">
                <i class="bi bi-table"></i>
            </button>
        </div>
        
        <!-- Tambah -->
        <button class="btn-tambah" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('master-data/category.toolbar.btn_add') }}</span>
            <span class="d-inline d-sm-none">{{ __('master-data/category.toolbar.btn_add') }}</span>
        </button>
    </div>

    <!-- ── GRID VIEW ── -->
    <div class="cat-grid" id="catGrid">
        <!-- Will be loaded dynamically via AJAX -->
        <div class="cat-empty" id="gridEmpty" style="display:none">
            <i class="bi bi-folder2-open"></i>
            <p>{{ __('master-data/category.empty.title') }}</p>
        </div>
    </div>

    <!-- ── TABLE VIEW ── -->
    <div class="cat-table-wrap" id="catTableWrap">
        <div class="cat-table-responsive">
            <table class="cat-table">
                <thead>
                    <tr>
                        <th style="width:52px">{{ __('master-data/category.table.icon') }}</th>
                        <th>{{ __('master-data/category.table.name') }}</th>
                        <th style="width:180px">{{ __('master-data/category.table.parent') }}</th>
                        <th style="width:100px;text-align:center">{{ __('master-data/category.table.views') }}</th>
                        <th style="width:100px">{{ __('master-data/category.table.status') }}</th>
                        <th style="width:80px;text-align:center">{{ __('master-data/category.table.orders') }}</th>
                        <th style="width:100px">{{ __('master-data/category.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="catTableBody">
                    <!-- Will be loaded dynamically via AJAX -->
                </tbody>
            </table>
        </div>
        <div class="cat-empty" id="tableEmpty" style="display:none">
            <i class="bi bi-folder2-open"></i>
            <p>{{ __('master-data/category.empty.title') }}</p>
        </div>
    </div>

    <!-- Pagination -->
    <div class="cat-pagination" id="catPagination" style="display:none">
        <div class="pag-info" id="paginationInfo"></div>
        <div class="pag-btns" id="paginationButtons"></div>
    </div>

    <!-- Modals -->
    @include('pages.master-data.kategori.partials.modals')

</x-app-layout>
