# Panduan Penggunaan ResepKita Alert

`resepkita-alert` adalah library kustom berbasis Vanilla JS dan CSS modern untuk menampilkan dialog konfirmasi, toast notifications, inline alerts, loading states, dan notification drawers. Library ini diintegrasikan secara global melalui objek **`PA`**.

---

## 🛠️ API & Cara Penggunaan

### 1. Toast Notification (`PA.toast`)
Digunakan untuk notifikasi pop-up otomatis di sudut layar yang dilengkapi dengan progress bar penunjuk sisa durasi.

```javascript
PA.toast({
  type: 'success', // 'success' | 'danger' | 'warning' | 'info' | 'question'
  title: 'Berhasil',
  message: 'Resep baru berhasil disimpan!',
  duration: 4000,
  position: 'top-right' // 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left'
});
```

### 2. Dialog Konfirmasi (`PA.dialog`)
Menampilkan modal popup konfirmasi yang mengembalikan **Promise `<boolean>`** (menggunakan async/await). Cocok untuk tombol hapus atau tindakan berbahaya.

```javascript
PA.dialog({
  type: 'danger',
  title: 'Hapus Bahan',
  message: 'Apakah Anda yakin ingin menghapus bahan masakan ini?',
  confirm: 'Ya, Hapus!',
  cancel: 'Batal'
}).then((confirmed) => {
  if (confirmed) {
    // Jalankan aksi hapus
    console.log('Data dihapus');
  }
});
```

### 3. Inline Alert (`PA.alert`)
Menyisipkan box alert secara inline di dalam sebuah container (paling atas/terdepan).

```javascript
// Parameter: (options, targetElement)
PA.alert({
  type: 'warning',
  title: 'Perhatian!',
  message: 'Format file gambar tidak didukung.',
  closable: true
}, '#form-container');
```

### 4. Fullscreen Loader (`PA.loading`)
Menampilkan backdrop loading penuh untuk menghalangi interaksi saat proses sinkronisasi data sedang berlangsung.

```javascript
// Membuka loader
const loader = PA.loading({
  title: 'Mengunggah...',
  message: 'Sedang memproses gambar resep.',
  dots: false // true untuk dot loading, false untuk spinner melingkar
});

// Update pesan secara dinamis
loader.update('Hampir selesai...');

// Tutup loader setelah proses selesai
setTimeout(() => {
  loader.close(); // atau PA.close()
}, 3000);
```

### 5. Drawer Notification (`PA.notify`)
Menampilkan drawer notifikasi berukuran besar yang bergeser masuk dari sisi kanan layar.

```javascript
PA.notify({
  type: 'info',
  title: 'Pengumuman Baru',
  message: 'Fitur kolaborasi memasak kini tersedia untuk dicoba!',
  duration: 6000
});
```

---

## 🎨 Parameter Konfigurasi & Pilihan Tipe

Setiap elemen notifikasi mendukung parameter `type` dengan styling ikon yang disesuaikan otomatis:

| Type | Warna Tema | Ikon Default |
| :--- | :--- | :--- |
| `success` | Jingga Sukses / Hijau | `bi-check-circle-fill` |
| `danger` | Merah / Jingga Gelap | `bi-x-circle-fill` |
| `warning` | Kuning | `bi-exclamation-triangle-fill` |
| `info` | Biru Muda / Jingga Utama | `bi-info-circle-fill` |
| `question`| Ungu / Jingga Lembut | `bi-question-circle-fill` |

---

## 💡 Contoh Integrasi pada Halaman Login

Kita dapat mempercantik pengalaman interaksi AJAX di `login.js` dengan menggunakan `PA.toast` untuk menampilkan alert ketika terjadi error rate limiting/invalid credentials, atau `PA.loading` ketika memproses redirect:

```javascript
$.ajax({
  url: actionUrl,
  type: 'POST',
  data: formData,
  dataType: 'json',
  success: function(response) {
    if (response.success && response.redirect) {
      // 1. Tampilkan toast sukses sebelum redirect
      PA.toast({
        type: 'success',
        title: 'Login Berhasil',
        message: 'Mengarahkan Anda ke Dashboard...',
        duration: 2000
      });

      // 2. Redirect setelah jeda singkat
      setTimeout(() => {
        window.location.href = response.redirect;
      }, 1000);
    }
  },
  error: function(xhr) {
    if (xhr.status === 422) {
      // Tampilkan error di bawah field input
      const errors = xhr.responseJSON.errors;
      Object.keys(errors).forEach(function(key) {
        $(`#error-${key}`).text(errors[key][0]);
      });
    } else {
      // Tampilkan Toast error general
      const generalMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem.';
      PA.toast({
        type: 'danger',
        title: 'Autentikasi Gagal',
        message: generalMsg,
        duration: 5000
      });
    }
  }
});
```
