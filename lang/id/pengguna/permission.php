<?php

return [
    'title' => 'Hak Akses (Permissions)',
    'desc' => 'Kelola hak akses/permission sistem Spatie secara terpusat untuk otorisasi modul.',

    'statistics' => [
        'total' => 'Total Permission',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'guards' => 'Guard',
    ],

    'toolbar' => [
        'search_placeholder' => 'Cari permission...',
        'filter_all_status' => 'Semua Status',
        'filter_active' => 'Aktif',
        'filter_inactive' => 'Nonaktif',
        'btn_reset' => 'Reset',
        'btn_add' => 'Tambah Permission',
        'view_grid' => 'Tampilan Grid',
        'view_table' => 'Tampilan Tabel',
    ],

    'table' => [
        'name' => 'Nama Permission',
        'guard' => 'Guard',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    'modal' => [
        'title_add' => 'Tambah Permission Baru',
        'title_edit' => 'Edit Permission',

        'label_name' => 'Nama Permission',
        'placeholder_name' => 'Contoh: create-recipes',
        'hint_name' => 'Gunakan format slug/kebab-case (huruf kecil dan tanda hubung).',

        'label_guard' => 'Guard Name',
        'placeholder_guard' => 'Contoh: web, api',

        'label_additional' => 'Tambahan',
        'label_toggle_status' => 'Status Keaktifan',
        'hint_toggle_status' => 'Tentukan apakah permission ini aktif atau dinonaktifkan sementara.',

        'btn_cancel' => 'Batal',
        'btn_save' => 'Simpan',
    ],

    'delete' => [
        'title' => 'Hapus Permission',
        'confirm' => 'Apakah Anda yakin ingin menghapus permission :name? Tindakan ini tidak dapat dibatalkan.',
        'btn_cancel' => 'Batal',
        'btn_delete' => 'Hapus',
    ],

    'messages' => [
        'fetch_success' => 'Data permission berhasil dimuat.',
        'store_success' => 'Permission baru berhasil disimpan.',
        'update_success' => 'Permission berhasil diperbarui.',
        'delete_success' => 'Permission berhasil dihapus.',
        'status_toggled' => 'Status permission berhasil diubah.',
    ],
];
