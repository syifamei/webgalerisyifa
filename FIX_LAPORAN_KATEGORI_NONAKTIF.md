# Perbaikan Laporan Galeri - Filter Kategori Nonaktif

## Deskripsi Masalah
Pada laporan galeri (PDF dan halaman index), data dari kategori yang berstatus **Nonaktif** masih ikut ditampilkan dan dihitung dalam **Total Keseluruhan**. Hal ini menyebabkan:
- Kategori nonaktif muncul di tabel "Detail Like & Download per Kategori"
- Foto dari kategori nonaktif ikut dihitung dalam Total Foto
- Like dan download dari kategori nonaktif ikut dihitung dalam Total Like dan Total Download

## Solusi yang Diterapkan

### 1. GalleryReportController - Method index()
**File:** `app/Http/Controllers/Admin/GalleryReportController.php`
**Method:** `index()` - Untuk halaman laporan di admin panel

**Perubahan:** (Baris 19-27)
```php
// SEBELUM:
$query = Foto::with([
    'kategori', 
    'likes',
    'downloadLogs'
])->where('status','Aktif');

// SESUDAH:
$query = Foto::with([
    'kategori', 
    'likes',
    'downloadLogs'
])->where('status','Aktif')
->whereHas('kategori', function($q) {
    $q->where('status', 'Aktif');
});
```

**Dampak:**
- Hanya foto dari kategori aktif yang diambil untuk laporan
- Total foto, like, dan download hanya dari kategori aktif
- Data lebih akurat dan sesuai dengan ekspektasi

---

### 2. GalleryReportController - Method generate()
**File:** `app/Http/Controllers/Admin/GalleryReportController.php`
**Method:** `generate()` - Untuk generate PDF laporan

**Perubahan:** (Baris 172-178)
```php
// SEBELUM:
$query = Foto::with(['kategori', 'likes', 'downloadLogs'])
    ->where('status', 'Aktif')
    ->orderBy('created_at', 'desc');

// SESUDAH:
$query = Foto::with(['kategori', 'likes', 'downloadLogs'])
    ->where('status', 'Aktif')
    ->whereHas('kategori', function($q) {
        $q->where('status', 'Aktif');
    })
    ->orderBy('created_at', 'desc');
```

**Dampak:**
- PDF laporan hanya menampilkan foto dari kategori aktif
- Tabel "Detail Like & Download per Kategori" hanya menampilkan kategori aktif
- Total Keseluruhan dihitung hanya dari kategori aktif

---

### 3. GalleryReportController - Method getPeriodStats()
**File:** `app/Http/Controllers/Admin/GalleryReportController.php`
**Method:** `getPeriodStats()` - Helper method untuk statistik periode

**Perubahan:** (Baris 138-142)
```php
// SEBELUM:
$query = Foto::with(['likes', 'downloadLogs'])->where('status','Aktif');

// SESUDAH:
$query = Foto::with(['likes', 'downloadLogs'])
    ->where('status','Aktif')
    ->whereHas('kategori', function($q) {
        $q->where('status', 'Aktif');
    });
```

**Dampak:**
- Statistik mingguan dan bulanan konsisten dengan filter kategori aktif
- Data perbandingan periode lebih akurat

---

## Struktur Tabel di PDF Laporan

### Tabel: Detail Like & Download per Kategori

| No | Nama Kategori | Jumlah Foto | Total Like | Total Download |
|----|---------------|-------------|------------|----------------|
| 1  | Kategori A    | 5           | 120        | 30             |
| 2  | Kategori B    | 8           | 200        | 45             |
| 3  | Kategori C    | 3           | 80         | 15             |
| **TOTAL KESELURUHAN** |    | **16 foto** | **400** | **90** |

**Catatan Penting:**
- ✅ Hanya kategori dengan status "Aktif" yang ditampilkan
- ✅ Foto dari kategori nonaktif tidak dihitung
- ✅ Total Keseluruhan HANYA dari kategori aktif
- ✅ Kategori nonaktif tidak muncul di tabel sama sekali

---

## Testing Manual

### Scenario 1: Generate PDF dengan Kategori Nonaktif

**Setup:**
1. Buat 3 kategori: A (Aktif), B (Aktif), C (Nonaktif)
2. Upload foto ke semua kategori dengan status "Aktif"
3. Pastikan ada like dan download di semua foto

**Test Steps:**
1. Login sebagai admin
2. Buka menu "Laporan Galeri"
3. Klik tombol "Generate PDF"

**Expected Result:**
- ✅ Tabel hanya menampilkan Kategori A dan B
- ✅ Kategori C (Nonaktif) tidak muncul di tabel
- ✅ Total Foto = jumlah foto dari Kategori A + B saja
- ✅ Total Like = jumlah like dari Kategori A + B saja
- ✅ Total Download = jumlah download dari Kategori A + B saja

### Scenario 2: Halaman Laporan Admin

**Test Steps:**
1. Login sebagai admin
2. Buka halaman `/admin/reports/gallery`
3. Pilih periode: Semua / Mingguan / Bulanan

**Expected Result:**
- ✅ Card statistik hanya menghitung data dari kategori aktif
- ✅ Tabel kategori hanya menampilkan kategori aktif
- ✅ Chart/grafik (jika ada) hanya dari kategori aktif

### Scenario 3: Nonaktifkan Kategori Populer

**Setup:**
1. Kategori "Event" memiliki 50 foto dengan total 500 likes
2. Nonaktifkan kategori "Event"

**Test Steps:**
1. Generate laporan PDF
2. Lihat total keseluruhan

**Expected Result:**
- ✅ Kategori "Event" tidak muncul di tabel
- ✅ Total Like berkurang 500 (tidak termasuk like dari Event)
- ✅ Total Foto berkurang 50 (tidak termasuk foto dari Event)
- ✅ Kategori terpopuler berubah (karena Event tidak dihitung)

---

## Query SQL yang Digunakan

### Sebelum Perbaikan:
```sql
SELECT * FROM foto 
WHERE status = 'Aktif' 
ORDER BY created_at DESC
```

### Setelah Perbaikan:
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

---

## Konsistensi dengan User View

Perbaikan ini memastikan konsistensi antara tampilan user dan laporan admin:

| Aspek | User View | Admin Report |
|-------|-----------|--------------|
| Galeri Aktif | ✅ Hanya kategori aktif | ✅ Hanya kategori aktif |
| Homepage | ✅ Hanya kategori aktif | ✅ Hanya kategori aktif |
| Laporan PDF | N/A | ✅ Hanya kategori aktif |
| Total Like | ✅ Hanya dari kategori aktif | ✅ Hanya dari kategori aktif |
| Total Download | ✅ Hanya dari kategori aktif | ✅ Hanya dari kategori aktif |

---

## Kesimpulan

Dengan perbaikan ini:

✅ **Kategori nonaktif tidak muncul** di laporan galeri PDF dan halaman index
✅ **Total Keseluruhan akurat** - hanya menghitung data dari kategori aktif
✅ **Konsisten** dengan tampilan user (galeri dan homepage)
✅ **Data lebih relevan** untuk analisis dan pengambilan keputusan
✅ **Tidak ada breaking changes** pada fitur lain

---

## Catatan untuk Developer

Jika di kemudian hari ada fitur laporan baru yang melibatkan foto/galeri, pastikan untuk menambahkan filter yang sama:

```php
->whereHas('kategori', function($q) {
    $q->where('status', 'Aktif');
})
```

Filter ini WAJIB diterapkan pada:
- ✅ Query foto untuk tampilan user
- ✅ Query foto untuk laporan/statistik
- ✅ Query foto untuk export data (CSV, Excel, PDF)
- ✅ API endpoint yang mengembalikan daftar foto untuk publik

**Pengecualian:** Admin panel untuk manajemen data (CRUD) tidak perlu filter ini, agar admin bisa melihat dan mengelola semua data termasuk kategori nonaktif.

---

## File yang Diubah

1. ✅ `app/Http/Controllers/Admin/GalleryReportController.php`
   - Method: `index()` (Baris 19-27)
   - Method: `generate()` (Baris 172-178)
   - Method: `getPeriodStats()` (Baris 138-142)

2. 📄 File view tidak perlu diubah
   - `resources/views/admin/reports/gallery.blade.php` - Tetap sama
   - `resources/views/admin/reports/gallery_index.blade.php` - Tetap sama

---

**Status:** ✅ SELESAI - Siap untuk testing dan production
