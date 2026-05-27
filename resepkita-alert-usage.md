# Panduan Penggunaan Resepkita Alert (`resepkita-alert.js`)

Pustaka kustom ini mengekspos objek global bernama `PA` yang dapat digunakan di seluruh aplikasi untuk menampilkan notifikasi, dialog konfirmasi, layar pemrosesan, dan pesan inline.

---

## 1. Toast Notification (`PA.toast`)
Digunakan untuk menampilkan notifikasi melayang singkat di sudut layar.

```javascript
PA.toast({
  type: 'success',           // Pilihan: 'success', 'danger', 'warning', 'info'
  title: 'Sukses',
  message: 'Data berhasil disimpan.',
  duration: 4000,            // Durasi tampilan dalam ms (default: 4000)
  position: 'bottom-center'  // Pilihan: 'top-right', 'top-left', 'bottom-right', 'bottom-left', 'bottom-center'
});
```

---

## 2. Dialog Konfirmasi (`PA.dialog`)
Menampilkan kotak dialog konfirmasi kustom dengan overlay. Metode ini mengembalikan *Promise* (dapat menggunakan `.then()` atau `async/await`).

```javascript
// Menggunakan Promise .then()
PA.dialog({
  type: 'warning',          // Pilihan: 'success', 'danger', 'warning', 'info', 'question'
  title: 'Hapus Peran?',
  message: 'Apakah Anda yakin ingin menghapus peran ini? Tindakan ini tidak dapat dibatalkan.',
  confirm: { 
    text: 'Ya, Hapus', 
    cls: 'pa-btn-danger'    // Pilihan class tombol: 'pa-btn-success', 'pa-btn-danger', 'pa-btn-warning', 'pa-btn-info'
  },
  cancel: { 
    text: 'Batal' 
  }
}).then((result) => {
  if (result) {
    // Jalankan logika jika tombol konfirmasi diklik (Ya)
    console.log("Aksi dilanjutkan...");
  } else {
    // Jalankan logika jika dibatalkan
    console.log("Aksi dibatalkan.");
  }
});
```

---

## 3. Layar Loading (`PA.loading`)
Digunakan untuk memblokir layar selama proses sinkronisasi atau pemrosesan latar belakang yang membutuhkan waktu lama.

```javascript
// Membuka layar loading
PA.loading({
  title: 'Harap Tunggu',
  message: 'Sedang memproses permintaan Anda...',
  dots: true // Animasi tiga titik berjalan
});

// Menutup layar loading setelah selesai
PA.close();
```

---

## 4. Alert Inline (`PA.alert`)
Merender pesan alert statis di dalam kontainer HTML yang ditentukan.

```javascript
PA.alert({
  type: 'danger',
  title: 'Gagal Memuat',
  message: 'Koneksi ke server terputus.',
  closable: true // Menampilkan tombol silang (close)
}, '#container-id'); // Target selector kontainer HTML
```

---

## 5. Notifikasi Sistem (`PA.notify`)
Menampilkan pemberitahuan/notifikasi slide-in di pojok atas layar.

```javascript
PA.notify({
  type: 'info',
  title: 'Notifikasi Baru',
  message: 'Resep baru telah dikirimkan oleh penulis.',
  duration: 5000
});
```

---

## 6. Menutup Komponen (`PA.close` & `PA.closeAll`)
- `PA.close()`: Menutup dialog atau status loading yang paling baru dibuka.
- `PA.closeAll()`: Menutup seluruh elemen dialog, toast, alert, dan status loading aktif secara instan.
