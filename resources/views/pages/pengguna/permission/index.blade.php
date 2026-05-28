<x-app-layout>
    @section('title', __('pengguna/permission.title'))
    @section('page-title', __('pengguna/permission.title'))

    @push('styles')
        @vite(['resources/css/backend/permission.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/permission.js'])
    @endpush

    <!-- Breadcrumb Bar -->
    <x-breadcrumb-bar 
        :title="__('pengguna/permission.title')"
        icon="bi-key"
        :desc="__('pengguna/permission.desc')"
        :items="[
            'Home' => route('dashboard'),
            'Pengguna' => '#',
            'Hak Akses' => null
        ]"
    />

    <!-- Stat Pills -->
    <div class="cat-stat-pills" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-pill pill-all">
            <i class="bi bi-key-fill"></i>
            <span>{{ __('pengguna/permission.statistics.total') }}: <strong>{{ $statistics['total'] }}</strong></span>
        </div>
        <div class="stat-pill pill-active">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ __('pengguna/permission.statistics.active') }}: <strong>{{ $statistics['active'] }}</strong></span>
        </div>
        <div class="stat-pill pill-inactive">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ __('pengguna/permission.statistics.inactive') }}: <strong>{{ $statistics['inactive'] }}</strong></span>
        </div>
        <div class="stat-pill pill-guards">
            <i class="bi bi-shield-lock-fill"></i>
            <span>{{ __('pengguna/permission.statistics.guards') }}: <strong>{{ $statistics['guards'] }}</strong></span>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="cat-toolbar" data-aos="fade-up" data-aos-delay="100">
        <!-- Search -->
        <div class="cat-search-wrap">
            <i class="bi bi-search"></i>
            <x-form-input type="text" class="cat-search" id="permSearch" :placeholder="__('pengguna/permission.toolbar.search_placeholder')"/>
        </div>

        <!-- Filter Status -->
        <x-form-select class="cat-filter" id="permStatusFilter">
            <option value="all">{{ __('pengguna/permission.toolbar.filter_all_status') }}</option>
            <option value="active">{{ __('pengguna/permission.toolbar.filter_active') }}</option>
            <option value="inactive">{{ __('pengguna/permission.toolbar.filter_inactive') }}</option>
        </x-form-select>

        <!-- Reset Button -->
        <button type="button" class="btn-reset" onclick="resetFilters()" title="{{ __('pengguna/permission.toolbar.btn_reset') }}">
            <i class="bi bi-arrow-clockwise"></i>
            <span class="d-none d-sm-inline">{{ __('pengguna/permission.toolbar.btn_reset') }}</span>
        </button>

        <!-- View Mode Toggle -->
        <div class="view-toggle ms-auto">
            <button class="view-btn active" id="btnGrid" onclick="setView('grid')" title="{{ __('pengguna/permission.toolbar.view_grid') }}"><i class="bi bi-grid-3x3-gap"></i></button>
            <button class="view-btn" id="btnTable" onclick="setView('table')" title="{{ __('pengguna/permission.toolbar.view_table') }}"><i class="bi bi-table"></i></button>
        </div>

        <!-- Add Button -->
        <button class="btn-tambah" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('pengguna/permission.toolbar.btn_add') }}</span>
            <span class="d-inline d-sm-none">Tambah</span>
        </button>
    </div>

    <!-- Grid View -->
    <div id="permGridView" data-aos="fade-up" data-aos-delay="150">
        <div class="perm-grid" id="permGrid">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>

    <!-- Table View -->
    <div class="cat-table-wrap" id="permTableWrap" data-aos="fade-up" data-aos-delay="150">
        <div class="cat-table-responsive">
            <table class="cat-table">
                <thead>
                    <tr>
                        <th class="sortable" onclick="sortTable('name')">
                            {{ __('pengguna/permission.table.name') }} <i class="bi bi-chevron-expand ms-1"></i>
                        </th>
                        <th class="sortable" onclick="sortTable('guard_name')">
                            {{ __('pengguna/permission.table.guard') }} <i class="bi bi-chevron-expand ms-1"></i>
                        </th>
                        <th>{{ __('pengguna/permission.table.status') }}</th>
                        <th style="width:100px; text-align:center;">{{ __('pengguna/permission.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="permTbody">
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

    @include('pages.pengguna.permission.partials.modals')
</x-app-layout>
