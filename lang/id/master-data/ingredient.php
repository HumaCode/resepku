<?php

return [
    'title' => 'Bahan Makanan',
    'desc' => 'Kelola master data bahan makanan yang digunakan dalam resep di platform.',

    'statistics' => [
        'total' => 'Total',
        'active' => 'Aktif',
        'categories' => 'Kategori',
        'inactive' => 'Nonaktif',
    ],

    'toolbar' => [
        'search_placeholder' => 'Cari nama bahan...',
        'filter_all_categories' => 'Semua Kategori',
        'filter_all_status' => 'Semua Status',
        'filter_active' => 'Aktif',
        'filter_inactive' => 'Nonaktif',
        'btn_reset' => 'Reset',
        'btn_add' => 'Tambah Bahan',
        'view_grid' => 'Grid View',
        'view_table' => 'Table View',
    ],

    'table' => [
        'emoji' => 'Emoji',
        'name' => 'Nama Bahan',
        'slug' => 'Slug',
        'category' => 'Kategori',
        'default_unit' => 'Satuan Default',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    'modal' => [
        'title_add' => 'Tambah Bahan Makanan',
        'title_edit' => 'Edit Bahan Makanan',
        
        'label_emoji' => 'Emoji Bahan',
        'hint_emoji' => 'Klik emoji untuk memilih. Emoji tampil di card bahan.',
        
        'label_name' => 'Nama Bahan',
        'placeholder_name' => 'cth: Bawang Merah',
        'hint_name' => 'Nama akan menjadi referensi di formulir resep.',
        
        'label_slug' => 'Slug',
        'placeholder_slug' => 'cth: bawang-merah',
        'hint_slug' => 'Huruf kecil, tanpa spasi, gunakan tanda hubung.',
        
        'label_category' => 'Kategori Bahan',
        'placeholder_category' => '-- Pilih Kategori --',
        
        'label_unit' => 'Satuan Default',
        'placeholder_unit' => 'cth: gram, siung, butir, ml',
        'hint_unit' => 'Pisahkan dengan koma jika lebih dari satu, cth: gram, kg',
        
        'label_description' => 'Deskripsi',
        'placeholder_description' => 'Deskripsi singkat tentang bahan ini...',
        'hint_description' => 'Deskripsi opsional untuk detail bahan makanan.',
        
        'label_additional' => 'Opsi Tambahan',
        'label_toggle_status' => 'Aktifkan Bahan',
        'hint_toggle_status' => 'Bahan aktif tersedia di formulir resep',
        
        'btn_cancel' => 'Batal',
        'btn_save' => 'Simpan Bahan',
    ],

    'messages' => [
        'fetch_success' => 'Data bahan makanan berhasil diambil.',
        'store_success' => 'Bahan makanan baru berhasil ditambahkan.',
        'update_success' => 'Bahan makanan berhasil diperbarui.',
        'delete_success' => 'Bahan makanan berhasil dihapus.',
        'status_toggled' => 'Status aktif bahan makanan berhasil diubah.',
    ],
];
