# Perbaikan Fitur Kelola Kategori - Filter Kategori Nonaktif

## Deskripsi Masalah
Sebelum perbaikan, galeri dari kategori yang berstatus **Nonaktif** masih muncul di halaman galeri user, khususnya pada filter "Semua Kategori". Hal ini tidak sesuai dengan ekspektasi bahwa kategori nonaktif seharusnya tidak menampilkan galeri/foto apapun di sisi user.

## Solusi yang Diterapkan

### 1. GaleriController.php (Halaman Galeri Utama)
**File:** `app/Http/Controllers/GaleriController.php`
**Method:** `index()`

**Perubahan:**
```php
// Ditambahkan filter whereHas untuk memastikan hanya foto dari kategori aktif
->whereHas('kategori', function($q) {
    $q->where('status', 'Aktif');
})
```

**Lokasi:** Baris 28-31

**Dampak:** 
- Foto dari kategori nonaktif tidak akan muncul di halaman galeri
- Berlaku untuk semua filter, termasuk "Semua Kategori" dan filter kategori spesifik

---

### 2. HomeController.php - Method index() (Homepage)
**File:** `app/Http/Controllers/HomeController.php`
**Method:** `index()`

**Perubahan:**
```php
// Ditambahkan filter pada query foto terbaru di homepage
->whereHas('kategori', function($q) {
    $q->where('status', 'Aktif');
})
```

**Lokasi:** Baris 24-27

**Dampak:**
- Foto terbaru yang ditampilkan di homepage hanya dari kategori aktif
- Foto dari kategori nonaktif tidak akan muncul di section galeri homepage

---

### 3. HomeController.php - Method galeri() (Alternative Galeri Route)
**File:** `app/Http/Controllers/HomeController.php`
**Method:** `galeri()`

**Perubahan:**
```php
// Ditambahkan filter whereHas pada query galeri
->whereHas('kategori', function($q) {
    $q->where('status', 'Aktif');
})
```

**Lokasi:** Baris 57-60

**Dampak:**
- Memastikan konsistensi jika route galeri alternatif digunakan
- Foto dari kategori nonaktif tidak akan muncul

---

## Area yang TIDAK Diubah (By Design)

### Admin Controllers
File berikut **TIDAK DIUBAH** karena admin harus bisa melihat semua data untuk keperluan manajemen:

1. **Admin\GalleryReportController.php**
   - Admin perlu melihat semua foto termasuk dari kategori nonaktif untuk laporan lengkap
   - Query tetap menggunakan `where('status', 'Aktif')` hanya untuk foto, bukan kategori

2. **Admin\FotoController.php**
   - Admin perlu melihat dan mengelola semua foto tanpa filter kategori
   - Untuk keperluan manajemen data

### API Controllers
File berikut **TIDAK DIUBAH** karena hanya menangani operasi pada foto yang sudah ada:

1. **Api\LikeController.php**
   - Hanya menangani like/unlike untuk foto yang sudah di-load
   - Tidak melakukan query daftar foto

2. **Api\CommentController.php**
   - Hanya menangani comment untuk foto spesifik
   - Tidak melakukan query daftar foto

## Testing Manual

Untuk memverifikasi perbaikan:

1. **Setup:**
   - Pastikan ada beberapa kategori dengan status "Aktif"
   - Pastikan ada minimal 1 kategori dengan status "Nonaktif"
   - Upload foto ke kategori nonaktif tersebut dengan status "Aktif"

2. **Test Case 1: Halaman Galeri - Filter "Semua Kategori"**
   - Buka halaman `/galeri` tanpa parameter kategori
   - **Expected:** Foto dari kategori nonaktif TIDAK muncul
   - **Expected:** Hanya foto dari kategori aktif yang ditampilkan

3. **Test Case 2: Halaman Galeri - Filter Kategori Spesifik**
   - Buka halaman `/galeri?kategori=mountour` (jika Mountour = nonaktif)
   - **Expected:** Tidak ada foto yang muncul (atau redirect/empty)
   - **Expected:** Filter kategori nonaktif tidak menampilkan foto apapun

4. **Test Case 3: Homepage - Section Foto Terbaru**
   - Buka halaman `/` (homepage)
   - Scroll ke section galeri/foto terbaru
   - **Expected:** Foto dari kategori nonaktif TIDAK muncul
   - **Expected:** Maksimal 9 foto terbaru dari kategori aktif

5. **Test Case 4: Admin Panel - Laporan Galeri**
   - Login sebagai admin
   - Buka halaman laporan galeri
   - **Expected:** Admin TETAP bisa melihat semua foto termasuk dari kategori nonaktif
   - **Expected:** Data lengkap untuk keperluan analisis

## Query SQL yang Digunakan

Sebelum perbaikan:
```sql
SELECT * FROM foto 
WHERE status = 'Aktif' 
ORDER BY created_at DESC
```

Setelah perbaikan:
```sql
SELECT * FROM foto 
WHERE status = 'Aktif' 
AND EXISTS (
    SELECT 1 FROM kategori 
    WHERE kategori.id = foto.kategori_id 
    AND kategori.status = 'Aktif'
)
ORDER BY created_at DESC
```

## Kesimpulan

Perbaikan ini memastikan bahwa:
- ✅ Foto dari kategori nonaktif **TIDAK AKAN PERNAH** muncul di halaman user
- ✅ Berlaku untuk semua view: galeri utama, filter "Semua Kategori", homepage
- ✅ Admin tetap bisa melihat semua data untuk keperluan manajemen
- ✅ Tidak ada breaking changes pada API atau fungsi lainnya
- ✅ Konsisten dengan business logic: kategori nonaktif = tidak ditampilkan ke user

## Catatan Penting

Jika di kemudian hari ada halaman baru yang menampilkan foto, pastikan untuk menambahkan filter yang sama:

```php
->whereHas('kategori', function($q) {
    $q->where('status', 'Aktif');
})
```

Filter ini harus diterapkan pada semua query `Foto` yang ditujukan untuk tampilan publik/user.
