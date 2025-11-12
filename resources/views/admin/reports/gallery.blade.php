<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Galeri - {{ $generatedAt }}</title>
    <style>
        /* ========================================
           PROFESSIONAL PDF STYLING - BLUE THEME
           ======================================== */
        
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10.5px;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 25px;
            background: #ffffff;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 18px;
            background: #ffffff;
            padding-top: 12px;
        }
        
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 22px;
            font-weight: 700;
            color: #1a2b6b;
            letter-spacing: 0.2px;
        }
        
        .header p {
            margin: 4px 0;
            color: #333333;
            font-size: 10px;
            line-height: 1.6;
        }
        
        .header p strong {
            color: #1a2b6b;
            font-weight: 700;
        }
        
        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 35px;
        }
        
        .stat-card {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            padding: 14px 12px;
            text-align: center;
            box-shadow: none;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: 700;
            color: #1a2b6b;
            margin-bottom: 5px;
            letter-spacing: -0.2px;
        }
        
        .stat-label {
            font-size: 8px;
            color: #374151;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.4px;
        }
        
        /* Section Styling */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a2b6b;
            margin-bottom: 12px;
            border-bottom: 2px solid #d1d5db;
            padding-bottom: 6px;
        }
        
        .section p {
            margin-bottom: 12px;
            line-height: 1.7;
        }
        
        /* Professional Table Styling - Blue Theme */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 18px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: none;
        }
        
        .table th,
        .table td {
            border: 1px solid #d1d5db;
            padding: 8px 7px;
            text-align: left;
        }
        
        .table th {
            background: #3b82f6;
            color: #ffffff;
            font-weight: 700;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-color: #3b82f6;
        }
        
        .table tbody tr {
            background: #ffffff;
        }
        
        .table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .table tbody tr:hover {
            background: #f3f4f6;
        }
        
        .table td {
            font-size: 9px;
            color: #333333;
            border-color: #d1d5db;
        }
        
        .table td strong {
            font-weight: 700;
            color: #1a2b6b;
        }
        
        .table tfoot {
            background: #f3f4f6;
            border-top: 2px solid #d1d5db;
        }
        
        .table tfoot td {
            padding: 10px 7px;
            font-weight: 700;
            color: #1a2b6b;
            font-size: 9px;
            border-color: #d1d5db;
        }
        
        /* Text Alignment */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Enhanced Badge Styling - Blue Theme */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: 700;
            border-radius: 3px;
            color: white;
            letter-spacing: 0.1px;
        }
        
        .badge-success {
            background: #10b981;
        }
        
        .badge-warning {
            background: #f59e0b;
            color: #ffffff;
        }
        
        .badge-info {
            background: #3b82f6;
        }
        
        /* Footer Styling */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8.5px;
            color: #666666;
            border-top: 2px solid #bbdefb;
            padding-top: 12px;
            line-height: 1.7;
        }
        
        .footer p {
            margin: 4px 0;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* Typography Enhancements */
        strong {
            font-weight: 700;
            color: #1a2b6b;
        }
        
        /* Spacing & Layout */
        .section p[style*="italic"] {
            padding: 8px 12px;
            background: #dbeafe;
            border-left: 3px solid #3b82f6;
            border-radius: 3px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN LIKE & DOWNLOAD GALERI</h1>
        <p><strong>SMKN 4 BOGOR</strong></p>
        <p>Laporan Berdasarkan Kategori Galeri</p>
        <p>Dibuat pada: {{ $generatedAt }}</p>
        <p>Periode: <strong>{{ $period }}</strong></p>
    </div>

    <!-- Statistics Overview -->
    <div class="section">
        <div class="section-title">Ringkasan Statistik Keseluruhan</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">User Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalPhotos }}</div>
                <div class="stat-label">Total Foto</div>
            </div>
            <div class="stat-card" style="background: #dcfce7; border-color: #86efac;">
                <div class="stat-number" style="color: #10b981;">{{ $totalLikes }}</div>
                <div class="stat-label">Total Like</div>
            </div>
            <div class="stat-card" style="background: #fef3c7; border-color: #fde68a;">
                <div class="stat-number" style="color: #f59e0b;">{{ $totalDownloads }}</div>
                <div class="stat-label">Total Download</div>
            </div>
        </div>
    </div>

    <!-- Laporan Utama: Statistik per Kategori -->
    <div class="section">
        <div class="section-title">Detail Like & Download per Kategori</div>
        <p style="margin-bottom: 15px; font-style: italic; color: #4a5568; padding: 10px 12px; background: #dbeafe; border-left: 3px solid #3b82f6; border-radius: 4px;">
            <strong>Informasi:</strong> Laporan ini menampilkan total jumlah like dan download yang dikelompokkan berdasarkan kategori galeri untuk periode <strong>{{ $period }}</strong>. Data ini dapat digunakan untuk menganalisis kategori mana yang paling populer di kalangan pengguna.
        </p>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="8%" class="text-center">No</th>
                    <th width="35%">Nama Kategori</th>
                    <th width="19%" class="text-center">Jumlah Foto</th>
                    <th width="19%" class="text-center">Total Like</th>
                    <th width="19%" class="text-center">Total Download</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoriStats as $index => $stat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $stat['nama'] }}</strong></td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $stat['total_fotos'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success">{{ $stat['total_likes'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">{{ $stat['total_downloads'] }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px;">
                        <em style="color: #9ca3af;">Tidak ada data kategori pada periode ini</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>TOTAL KESELURUHAN:</strong></td>
                    <td class="text-center"><strong>{{ $totalPhotos }} foto</strong></td>
                    <td class="text-center"><strong style="color: #10b981;">{{ $totalLikes }}</strong></td>
                    <td class="text-center"><strong style="color: #f59e0b;">{{ $totalDownloads }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Analisis Kategori Terpopuler -->
    @if($kategoriStats->count() > 0)
    <div class="section">
        <div class="section-title">Kategori Terpopuler</div>
        @php
            $mostLiked = $kategoriStats->sortByDesc('total_likes')->first();
            $mostDownloaded = $kategoriStats->sortByDesc('total_downloads')->first();
        @endphp
        
        <table class="table" style="margin-bottom: 0;">
            <tbody>
                <tr>
                    <td style="width: 50%; padding: 12px;">
                        <strong style="color: #10b981; font-size: 10px;">Paling Banyak Disukai:</strong><br>
                        <span style="font-size: 11px; font-weight: 700; color: #1a2b6b;">{{ $mostLiked['nama'] }}</span><br>
                        <span style="font-size: 9px; color: #666;">{{ $mostLiked['total_likes'] }} likes dari {{ $mostLiked['total_fotos'] }} foto</span>
                    </td>
                    <td style="width: 50%; padding: 12px;">
                        <strong style="color: #f59e0b; font-size: 10px;">Paling Banyak Diunduh:</strong><br>
                        <span style="font-size: 11px; font-weight: 700; color: #1a2b6b;">{{ $mostDownloaded['nama'] }}</span><br>
                        <span style="font-size: 9px; color: #666;">{{ $mostDownloaded['total_downloads'] }} downloads dari {{ $mostDownloaded['total_fotos'] }} foto</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <!-- Daftar User yang Sudah Register/Login (Aktif) -->
    <div class="section page-break">
        <div class="section-title">Daftar User yang Sudah Register/Login (Aktif)</div>
        <p style="margin-bottom: 15px; font-style: italic; color: #4a5568; padding: 10px 12px; background: #dbeafe; border-left: 3px solid #3b82f6; border-radius: 4px;">
            <strong>Informasi:</strong> Daftar ini menampilkan user yang sudah melakukan registrasi dan login dengan status akun <strong>aktif</strong> untuk periode <strong>{{ $period }}</strong>. User yang tidak aktif tidak ditampilkan dalam daftar ini.
        </p>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="8%" class="text-center">No</th>
                    <th width="25%">Nama Lengkap</th>
                    <th width="20%">Username</th>
                    <th width="27%">Email</th>
                    <th width="20%" class="text-center">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->username ?? '-' }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px;">
                        <em style="color: #9ca3af;">Tidak ada user aktif terdaftar pada periode ini</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>TOTAL USER AKTIF:</strong></td>
                    <td class="text-center"><strong style="color: #3b82f6;">{{ $totalUsers }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem manajemen galeri SMKN 4 BOGOR</p>
        <p>Halaman 1 dari 1 | {{ $generatedAt }}</p>
    </div>
</body>
</html>

