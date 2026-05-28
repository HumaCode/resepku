<x-app-layout>
    @section('title', __('master-data/ingredient.title'))
    @section('page-title', __('master-data/ingredient.title'))

    @push('styles')
        @vite(['resources/css/backend/ingredient.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/ingredient.js'])
    @endpush

    <!-- Breadcrumb Bar -->
    <x-breadcrumb-bar 
        title="{{ __('master-data/ingredient.title') }}"
        icon="bi-egg-fried"
        desc="{{ __('master-data/ingredient.desc') }}"
        :items="[
            'Home' => route('dashboard'),
            'Master Data' => '#',
            __('master-data/ingredient.title') => null
        ]"
    />

    <!-- Stat Pills -->
    <div class="cat-stat-pills" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-pill pill-all">
            <i class="bi bi-basket"></i>
            <span>{{ __('master-data/ingredient.statistics.total') }}: <strong>{{ $statistics['total'] }}</strong></span>
        </div>
        <div class="stat-pill pill-active">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ __('master-data/ingredient.statistics.active') }}: <strong>{{ $statistics['active'] }}</strong></span>
        </div>
        <div class="stat-pill pill-cat">
            <i class="bi bi-grid-3x3-gap"></i>
            <span>{{ __('master-data/ingredient.statistics.categories') }}: <strong>{{ $statistics['categories'] }}</strong></span>
        </div>
        <div class="stat-pill pill-warn">
            <i class="bi bi-exclamation-triangle"></i>
            <span>{{ __('master-data/ingredient.statistics.inactive') }}: <strong>{{ $statistics['inactive'] }}</strong></span>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="cat-toolbar" data-aos="fade-up" data-aos-delay="100">
        <!-- Search -->
        <div class="cat-search-wrap">
            <i class="bi bi-search"></i>
            <x-form-input type="text" class="cat-search" id="bahanSearch" :placeholder="__('master-data/ingredient.toolbar.search_placeholder')"/>
        </div>
        
        <!-- Filter Category -->
        <x-form-select class="cat-filter" id="bahanKatFilter">
            <option value="all">{{ __('master-data/ingredient.toolbar.filter_all_categories') }}</option>
            <option value="sayuran">🥦 Sayuran</option>
            <option value="daging">🥩 Daging</option>
            <option value="bumbu">🌶️ Bumbu & Rempah</option>
            <option value="karbohidrat">🌾 Karbohidrat</option>
            <option value="seafood">🦐 Seafood</option>
            <option value="susu">🥛 Susu & Telur</option>
            <option value="buah">🍎 Buah</option>
            <option value="lainnya">📦 Lainnya</option>
        </x-form-select>

        <!-- Filter Status -->
        <x-form-select class="cat-filter d-none d-sm-block" id="bahanStatusFilter">
            <option value="all">{{ __('master-data/ingredient.toolbar.filter_all_status') }}</option>
            <option value="active">{{ __('master-data/ingredient.toolbar.filter_active') }}</option>
            <option value="inactive">{{ __('master-data/ingredient.toolbar.filter_inactive') }}</option>
        </x-form-select>

        <!-- Reset Button -->
        <button type="button" class="btn-reset" onclick="resetFilters()" title="{{ __('master-data/ingredient.toolbar.btn_reset') }}">
            <i class="bi bi-arrow-clockwise"></i>
            <span class="d-none d-sm-inline">{{ __('master-data/ingredient.toolbar.btn_reset') }}</span>
        </button>

        <!-- View Mode Toggle -->
        <div class="view-toggle ms-auto">
            <button class="view-btn active" id="btnGrid" onclick="setView('grid')" title="{{ __('master-data/ingredient.toolbar.view_grid') }}"><i class="bi bi-grid-3x3-gap"></i></button>
            <button class="view-btn" id="btnTable" onclick="setView('table')" title="{{ __('master-data/ingredient.toolbar.view_table') }}"><i class="bi bi-table"></i></button>
        </div>

        <!-- Add Button -->
        <button class="btn-tambah" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('master-data/ingredient.toolbar.btn_add') }}</span>
            <span class="d-inline d-sm-none">Tambah</span>
        </button>
    </div>

    <!-- Grid View -->
    <div id="bahanGridView" data-aos="fade-up" data-aos-delay="150">
        <div class="bahan-grid" id="bahanGrid">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>

    <!-- Table View -->
    <div class="cat-table-wrap" id="bahanTableWrap" data-aos="fade-up" data-aos-delay="150">
        <div class="cat-table-responsive">
            <table class="cat-table">
                <thead>
                    <tr>
                        <th style="width:46px">{{ __('master-data/ingredient.table.emoji') }}</th>
                        <th class="sortable" onclick="sortTable('name')">
                            {{ __('master-data/ingredient.table.name') }} <i class="bi bi-chevron-expand ms-1"></i>
                        </th>
                        <th class="sortable d-none d-md-table-cell" onclick="sortTable('slug')">
                            {{ __('master-data/ingredient.table.slug') }} <i class="bi bi-chevron-expand ms-1"></i>
                        </th>
                        <th class="sortable" onclick="sortTable('category')">
                            {{ __('master-data/ingredient.table.category') }} <i class="bi bi-chevron-expand ms-1"></i>
                        </th>
                        <th style="width:90px">{{ __('master-data/ingredient.table.status') }}</th>
                        <th style="width:100px; text-align:center;">{{ __('master-data/ingredient.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="bahanTbody">
                    <!-- Loaded dynamically via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="cat-pagination" data-aos="fade-up" data-aos-delay="100">
        <div class="pag-info" id="paginationInfo">
            <!-- Loaded dynamically via AJAX -->
        </div>
        <div class="pag-btns" id="paginationButtons">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>

    @include('pages.master-data.bahan.partials.modals')
</x-app-layout>
