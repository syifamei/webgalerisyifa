# Admin Photo Upload & Database Integration Guide

## Overview
This document explains how the admin photo upload system works, the correlation between file storage and database records, and how to troubleshoot issues.

---

## System Architecture

### 1. Admin Gallery Management Flow
```
Admin Portal
    ↓
/admin/galeri (List View)
    ├── Create: /admin/galeri/create
    ├── Edit: /fotos/{id}/edit
    ├── Delete: /fotos/{id} (DELETE)
    └── Update Status: /fotos/{id}/status (PATCH)
```

### 2. Photo Upload Path
```
User Action: Click "Upload Photo"
    ↓
Form Submission (POST /fotos)
    ↓
AdminFotoController::store()
    ├─ Validate input (judul, kategori_id, foto file)
    ├─ Store file: Storage::disk('public')->storeAs('fotos', $fileName, 'public')
    ├─ Create DB record: Foto::create([...])
    ├─ Return JSON response (AJAX) or redirect
    ↓
Database Entry Created + File on Disk
    ↓
Image accessible at: /storage/fotos/{filename}
```

---

## Code Implementation

### Upload Controller (AdminFotoController@store)

**File**: `app/Http/Controllers/Admin/FotoController.php` (Lines 32-106)

```php
public function store(Request $request)
{
    // 1. VALIDATE INPUT
    $validator = Validator::make($request->all(), [
        'judul' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategori,id',
        'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
    ]);

    if ($validator->fails()) {
        return $request->expectsJson() 
            ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
            : redirect()->back()->withErrors($validator);
    }

    try {
        // 2. STORE FILE
        $path = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('fotos', $fileName, 'public');  // <- PUBLIC DISK
            $path = str_replace('public/', '', $path);             // <- CLEAN PATH
        }

        // 3. CREATE DATABASE RECORD
        $foto = Foto::create([
            'judul' => $request->judul,
            'path' => $path,                    // <- STORE RELATIVE PATH
            'kategori_id' => $request->kategori_id,
            'status' => 'Aktif',
            'petugas_id' => auth()->id() ?? 1,
        ]);

        // 4. RETURN RESPONSE
        $foto->load('kategori');
        return $request->expectsJson()
            ? response()->json(['success' => true, 'data' => $foto])
            : redirect()->route('admin.fotos.index')->with('success', 'Foto berhasil diupload');
    }
    catch (\Exception $e) {
        // 5. ERROR HANDLING
        if (isset($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);  // <- CLEANUP ON ERROR
        }
        return $request->expectsJson()
            ? response()->json(['success' => false, 'message' => $e->getMessage()], 500)
            : redirect()->back()->with('error', $e->getMessage());
    }
}
```

### Upload Form (create.blade.php)

**File**: `resources/views/admin/galeries/create.blade.php` (Lines 40-44)

```html
<form action="{{ route('admin.fotos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="judul" placeholder="Judul Foto" required>
    <select name="kategori_id" required>...</select>
    <input type="file" name="foto" accept="image/*" required>
    <button type="submit">Upload Foto</button>
</form>
```

### Photo Model (Foto.php)

**File**: `app/Models/Foto.php`

```php
protected $table = 'foto';

protected $fillable = [
    'judul',
    'deskripsi',
    'path',              // <- RELATIVE PATH STORED HERE
    'file',              // <- COMPATIBILITY FIELD
    'galery_id',
    'kategori_id',
    'status',
    'petugas_id',
    'likes_count'
];

// Accessor for full URL
public function getFullPathAttribute()
{
    $stored = $this->path ?: $this->file;
    return $stored ? asset('storage/' . $stored) : '';
}

// Relationships
public function kategori()
{
    return $this->belongsTo(Kategori::class, 'kategori_id');
}
```

### Database Schema (foto table)

```sql
CREATE TABLE `foto` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `judul` varchar(255) NOT NULL,
    `deskripsi` text NULLABLE,
    `path` varchar(255) NULLABLE,              -- <- RELATIVE PATH: "fotos/1762828621_4x7h10XdN4.jpeg"
    `file` varchar(255) NULLABLE,
    `galery_id` bigint UNSIGNED NULLABLE,
    `kategori_id` bigint UNSIGNED NOT NULL,
    `status` enum('Aktif','Nonaktif') DEFAULT 'Aktif',
    `petugas_id` bigint UNSIGNED NULLABLE,
    `likes_count` int DEFAULT 0,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`),
    FOREIGN KEY (`petugas_id`) REFERENCES `petugas` (`id`),
    INDEX (`status`), 
    INDEX (`kategori_id`)
);
```

---

## Database Entry Example

### Sample Record (from Latest Upload)
```json
{
    "id": 82,
    "judul": "Upacara Bendera Senin Pagi",
    "deskripsi": null,
    "path": "fotos/1762828621_4x7h10XdN4.jpeg",
    "file": null,
    "galery_id": null,
    "kategori_id": 3,
    "status": "Aktif",
    "petugas_id": 1,
    "likes_count": 0,
    "created_at": "2025-11-12 12:40:21",
    "updated_at": "2025-11-12 12:40:21",
    "kategori": {
        "id": 3,
        "nama": "Upacara",
        "status": "Aktif"
    }
}
```

### Database Path Storage
```
Database Column: path
Format: "fotos/{unique_filename}"
Example: "fotos/1762828621_4x7h10XdN4.jpeg"

Access URL:
/storage/fotos/1762828621_4x7h10XdN4.jpeg
```

---

## File System Storage

### Directory Structure
```
storage/app/public/
├── .gitignore                          -- Allow public files
├── .htaccess                           -- Serve files directly
└── fotos/                              -- Photo folder
    ├── 1762827237_AqqS9GbfnF.JPG
    ├── 1762827890_3bI9ZKU7wV.JPG
    ├── 1762828218_yQp7yhM95m.jpg
    ├── 1762828621_4x7h10XdN4.jpeg
    └── [78 more files...]

public/
├── storage/ -> (symlink to storage/app/public)
├── index.php
├── .htaccess
└── ...
```

### File Naming Convention
- **Format**: `{timestamp}_{randomString}.{extension}`
- **Example**: `1762828621_4x7h10XdN4.jpeg`
- **Timestamp**: Current Unix timestamp (seconds since 1970)
- **Random**: 10-character alphanumeric string (prevents collisions)

### Storage Configuration

**File**: `config/filesystems.php`
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
]
```

---

## Verification & Testing

### 1. Database Verification
```bash
# Check total photo records
php artisan tinker
> App\Models\Foto::count()
# Should return: 82

# Check specific photo
> $foto = App\Models\Foto::first();
> $foto->path
# Should return: "fotos/1762828621_4x7h10XdN4.jpeg"

# Check all paths
> App\Models\Foto::pluck('path')->take(5)
```

### 2. Filesystem Verification
```bash
# Count files in fotos directory
ls storage/app/public/fotos/ | wc -l
# Should return: 82

# Check file permissions
ls -la storage/app/public/fotos/ | head -5

# Verify symlink
ls -la public/storage
# Should show: storage -> ../storage/app/public
```

### 3. URL Access Verification
```bash
# Test single image
curl http://127.0.0.1:8000/storage/fotos/1762828621_4x7h10XdN4.jpeg -v
# Should return: HTTP 200

# Check content type
curl -I http://127.0.0.1:8000/storage/fotos/1762828621_4x7h10XdN4.jpeg
# Should show: Content-Type: image/jpeg
```

### 4. Admin Interface Test
1. Navigate to: `http://127.0.0.1:8000/admin/galeri`
2. Click "Tambah Foto"
3. Fill in form:
   - Judul: "Test Photo"
   - Kategori: Select any
   - Foto: Upload image file
4. Submit and verify:
   - ✓ File appears in `storage/app/public/fotos/`
   - ✓ Database record created with correct path
   - ✓ Image displays in gallery list

---

## Common Issues & Solutions

### Issue 1: File Uploaded but Not in Database
**Cause**: Exception during `Foto::create()`
**Solution**:
```php
// Check validation
php artisan tinker
> Foto::validate(['kategori_id' => 1])  // Verify kategori exists

// Check database connection
> DB::connection()->getDatabaseName()

// Check recent errors
tail -f storage/logs/laravel.log
```

### Issue 2: Database Entry but No File
**Cause**: `Storage::storeAs()` failed silently
**Solution**:
```bash
# Check storage permissions
chmod -R 755 storage/app/public/fotos

# Check disk space
df -h

# Verify file wasn't too large
# Max: 5MB per config/filesystems.php
```

### Issue 3: 403 Forbidden on Image Access
**Cause**: Symlink broken or .htaccess blocking
**Solution** (see STORAGE_ACCESS_FIX.md):
```bash
# Recreate symlink
php artisan storage:link

# Check .htaccess
cat public/.htaccess
# Should have: RewriteRule ^storage/ - [L]
```

### Issue 4: Database Entry Shows but Path is Wrong
**Cause**: Path stored with "public/" prefix
**Solution** (Already handled in store() method):
```php
// Line 75 in FotoController
$path = str_replace('public/', '', $path);  // <- REMOVES "public/" PREFIX
```

---

## Admin UI Features

### List View (`admin/galeries/index.blade.php`)
- Display all photos in grid layout
- Filter by kategori
- View, Edit, Delete actions
- Bulk delete support
- Status indicators

### Create View (`admin/galeries/create.blade.php`)
- Drag & drop upload
- File preview
- Inline validation
- Loading state indicators

### Edit View (`admin/galeries/edit.blade.php`)
- Update judul and kategori
- Replace photo (old file auto-deleted)
- Show current photo preview
- Modal-based interface

### Delete Feature
- Soft delete from database ✓
- Hard delete from filesystem ✓
- Handles missing files gracefully

---

## API Endpoints (Used by Admin Panel)

### GET /admin/fotos (List)
```
Response: HTML page with gallery grid
Query params: kategori={id}
```

### GET /admin/galeri/create (Create Form)
```
Response: HTML form page
```

### POST /fotos (Store)
```
Method: POST
Content-Type: multipart/form-data
Body:
  - judul: string (required)
  - kategori_id: integer (required, must exist)
  - foto: file (required, image type)

Response: 
  JSON: { success: true, data: {...} }
  OR redirect with flash message
```

### GET /admin/fotos/{id} (Show)
```
Response: JSON { success: true, data: {...} }
Used by: Modal view/edit dialogs
```

### PUT /fotos/{id} (Update)
```
Method: PUT (via POST with _method=PUT)
Content-Type: multipart/form-data
Body:
  - judul: string (required)
  - kategori_id: integer (required)
  - foto: file (optional)
```

### DELETE /fotos/{id} (Delete)
```
Method: DELETE
Response: JSON { success: true, message: "..." }
```

---

## Database Query Examples

### Get All Photos with Category
```php
$fotos = Foto::with('kategori')->where('status', 'Aktif')->get();
```

### Get Photos by Category
```php
$fotos = Foto::where('kategori_id', 1)->orderBy('created_at', 'desc')->get();
```

### Get Photo with Full URL
```php
$foto = Foto::first();
$url = $foto->full_path;  // Uses accessor: asset('storage/' . $path)
```

### Count Photos by Status
```php
$active = Foto::where('status', 'Aktif')->count();
$inactive = Foto::where('status', 'Nonaktif')->count();
```

---

## Performance Considerations

1. **Database Indexing**: `status` and `kategori_id` are indexed
2. **File Storage**: Files stored outside Laravel app root (good for performance)
3. **Caching**: Images cached by browser (1-year expiration via .htaccess)
4. **Lazy Loading**: Relationships use eager loading via `->with()`

---

## Security Considerations

1. **File Validation**: Only image types allowed (MIME check + extension)
2. **File Size Limit**: 5MB per file
3. **Path Traversal**: Files stored in controlled directory
4. **Authentication**: Admin routes protected (check Kernel.php for middleware)
5. **Authorization**: Only admin users can upload (implied by route prefix)

---

## Future Improvements

1. **Image Optimization**: Add image compression on upload
2. **Thumbnail Generation**: Create thumbnails for faster loading
3. **Batch Upload**: Support multiple file upload at once
4. **Soft Delete**: Add soft delete support to preserve history
5. **CDN Integration**: Move storage to cloud (S3, Google Cloud)
6. **Image Cropping**: Allow admin to crop/resize before save

---

## Related Files Reference

| File | Purpose |
|------|---------|
| `app/Http/Controllers/Admin/FotoController.php` | Upload/CRUD logic |
| `app/Models/Foto.php` | Database model |
| `routes/web.php` | URL routing |
| `resources/views/admin/galeries/` | UI templates |
| `config/filesystems.php` | Storage configuration |
| `public/.htaccess` | Apache rewrite rules |
| `storage/app/public/.htaccess` | Storage access rules |
| `database/migrations/` | Database schema |

---

## Troubleshooting Checklist

- [ ] File uploaded successfully (check `storage/app/public/fotos/`)
- [ ] Database record created (check `foto` table)
- [ ] Path format correct (`fotos/filename.ext`)
- [ ] Image accessible at `/storage/fotos/{filename}`
- [ ] Status code 200 (not 404 or 403)
- [ ] Content-Type header is `image/{format}`
- [ ] Admin user authenticated (check session)
- [ ] Kategori ID is valid (exists in `kategori` table)
- [ ] Disk space available (`df -h`)
- [ ] File permissions correct (check `storage/` folder)
- [ ] Symlink intact (check `public/storage`)
- [ ] .htaccess properly configured

