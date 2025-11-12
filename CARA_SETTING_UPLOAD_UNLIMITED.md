# Cara Menghilangkan Batasan Upload File

## 1. Laravel (SUDAH DIPERBAIKI ✅)
Batasan 5MB di `FotoController.php` sudah dihapus.

## 2. PHP Configuration (PERLU DIUBAH)

### Lokasi File php.ini:
- XAMPP Windows: `C:\xampp\php\php.ini`
- XAMPP Mac/Linux: `/opt/lampp/etc/php.ini`

### Edit php.ini:
Cari dan ubah setting berikut:

```ini
; Maksimal ukuran file upload (ubah sesuai kebutuhan)
upload_max_filesize = 50M

; Maksimal ukuran POST data (harus lebih besar dari upload_max_filesize)
post_max_size = 55M

; Maksimal waktu eksekusi script (untuk file besar butuh waktu lama)
max_execution_time = 300

; Maksimal waktu input
max_input_time = 300

; Maksimal memory yang bisa digunakan PHP
memory_limit = 256M
```

### Langkah-langkah:

1. **Buka php.ini:**
   - Buka XAMPP Control Panel
   - Klik tombol "Config" di Apache
   - Pilih "PHP (php.ini)"

2. **Cari setting (tekan Ctrl+F):**
   - Cari: `upload_max_filesize`
   - Ubah dari `2M` menjadi `50M` (atau lebih)

3. **Cari dan ubah:**
   - Cari: `post_max_size`
   - Ubah dari `8M` menjadi `55M` (atau lebih)

4. **Restart Apache:**
   - Klik "Stop" pada Apache di XAMPP
   - Tunggu beberapa detik
   - Klik "Start"

5. **Verifikasi:**
   - Buka: http://localhost/syifa/public/phpinfo.php (buat file ini dulu)
   - Cari `upload_max_filesize` dan `post_max_size`
   - Pastikan sudah berubah

## 3. Nginx (Jika Pakai Nginx, bukan Apache)

Edit file nginx config:

```nginx
client_max_body_size 50M;
```

## Testing

Setelah restart Apache, coba upload file besar di admin panel:
1. Login ke admin
2. Buka menu Galeri
3. Upload foto besar (10MB+)
4. Seharusnya berhasil tanpa error!

## Rekomendasi

Untuk production:
- Jangan set terlalu besar (50MB cukup)
- Gunakan image compression
- Upload ke cloud storage (AWS S3, Cloudinary)
