@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="welcome-content">
                <div class="welcome-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="welcome-text">
                    <h1 class="welcome-title">Selamat Datang, {{ Session::get('admin_username') }}! </h1>
                    <p class="welcome-subtitle">Kelola konten website SMKN 4 BOGOR dengan mudah dan efisien</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats Cards - Responsive Grid -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-blue">
            <div class="stat-icon">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalFotos }}</div>
                <div class="stat-label">Total Foto</div>
                <div class="stat-badge">
                    <i class="fas fa-arrow-up"></i> {{ $fotosBulanIni }} bulan ini
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalAgenda ?? 0 }}</div>
                <div class="stat-label">Total Agenda</div>
                <div class="stat-badge">
                    <i class="fas fa-clock"></i> {{ $agendaAktif ?? 0 }} aktif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-orange">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalInformasi ?? 0 }}</div>
                <div class="stat-label">Total Informasi</div>
                <div class="stat-badge">
                    <i class="fas fa-check-circle"></i> {{ $informasiAktif ?? 0 }} aktif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card stat-purple">
            <div class="stat-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalKategoris }}</div>
                <div class="stat-label">Kategori</div>
                <div class="stat-badge">
                    <i class="fas fa-check-circle"></i> {{ $kategorisAktif }} aktif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Sections - Responsive Layout -->
<div class="row">
    <!-- Recent Photos - Mobile First -->
    <div class="col-lg-6 mb-4">
        <div class="content-card">
            <div class="content-header">
                <div class="content-title">
                    <i class="fas fa-images"></i>
                    <span>Galeri Terbaru</span>
                </div>
                <a href="{{ route('admin.fotos.index') }}" class="btn-manage">
                    <i class="fas fa-cog me-1"></i>
                    Kelola
                </a>
            </div>
            <div class="content-body">
                @if($recentFotos->count() > 0)
                <div class="gallery-grid">
                    @foreach($recentFotos->take(4) as $foto)
                    <div class="gallery-item">
                        <div class="gallery-image">
                            <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $foto->judul }}">
                            <div class="gallery-overlay">
                                <span class="gallery-category">{{ $foto->kategori->nama ?? 'Umum' }}</span>
                            </div>
                        </div>
                        <div class="gallery-info">
                            <h6 class="gallery-title">{{ Str::limit($foto->judul, 30) }}</h6>
                            <p class="gallery-date">
                                <i class="fas fa-calendar"></i>
                                {{ $foto->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <p>Belum ada foto</p>
                    <a href="{{ route('admin.fotos.create') }}" class="btn-add">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Foto
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Agenda -->
    <div class="col-lg-3 mb-4">
        <div class="content-card">
            <div class="content-header">
                <div class="content-title">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Agenda Terdekat</span>
                </div>
                <a href="{{ route('admin.agenda.index') }}" class="btn-manage">
                    <i class="fas fa-eye me-1"></i>
                    Semua
                </a>
            </div>
            <div class="content-body">
                @if(isset($recentAgenda) && $recentAgenda->count() > 0)
                <div class="agenda-list">
                    @foreach($recentAgenda->take(4) as $agenda)
                    <div class="agenda-item">
                        <div class="agenda-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="agenda-details">
                            <h6 class="agenda-title">{{ Str::limit($agenda->title ?? $agenda->judul, 50) }}</h6>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Belum ada agenda</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Informasi -->
    <div class="col-lg-3 mb-4">
        <div class="content-card">
            <div class="content-header">
                <div class="content-title">
                    <i class="fas fa-newspaper"></i>
                    <span>Informasi Terbaru</span>
                </div>
                <a href="{{ route('admin.informasi.index') }}" class="btn-manage">
                    <i class="fas fa-eye me-1"></i>
                    Semua
                </a>
            </div>
            <div class="content-body">
                @if(isset($recentInformasi) && $recentInformasi->count() > 0)
                <div class="agenda-list">
                    @foreach($recentInformasi->take(4) as $informasi)
                    <div class="agenda-item">
                        <div class="agenda-icon" style="background: linear-gradient(135deg, #fd7e14, #e76300);">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="agenda-details">
                            <h6 class="agenda-title">{{ Str::limit($informasi->judul, 50) }}</h6>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <p>Belum ada informasi</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Welcome Card */
    .welcome-card {
        background: #0d6efd;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .welcome-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        position: relative;
        z-index: 1;
    }
    
    .welcome-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        flex-shrink: 0;
    }
    
    .welcome-text {
        flex: 1;
    }
    
    .welcome-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
    }
    
    .welcome-subtitle {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }
    
    /* Modern Stat Cards */
    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.06);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    
    
    .stat-blue::before {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    }
    
    .stat-green::before {
        background: linear-gradient(135deg, #198754, #157347);
    }
    
    .stat-orange::before {
        background: linear-gradient(135deg, #fd7e14, #e76300);
    }
    
    .stat-purple::before {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }
    
    .stat-blue .stat-icon {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    
    .stat-green .stat-icon {
        background: linear-gradient(135deg, #198754, #157347);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }
    
    .stat-orange .stat-icon {
        background: linear-gradient(135deg, #fd7e14, #e76300);
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3);
    }
    
    .stat-purple .stat-icon {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
    }
    
    .stat-content {
        flex: 1;
        position: relative;
        z-index: 1;
    }
    
    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: #198754;
        background: rgba(25, 135, 84, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    
    /* Content Cards */
    .content-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-bottom: 2px solid #f0f0f0;
    }
    
    .content-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    
    .content-title i {
        color: #0d6efd;
    }
    
    .btn-manage {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        background: #0d6efd;
        color: white;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
    }
    
    .btn-manage:hover {
        background: #0b5ed7;
        color: white;
    }
    
    .content-body {
        padding: 1.25rem;
    }
    
    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .gallery-item {
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .gallery-image {
        position: relative;
        padding-top: 75%;
        overflow: hidden;
    }
    
    .gallery-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.5rem;
    }
    
    .gallery-category {
        display: inline-block;
        background: #0d6efd;
        color: white;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .gallery-info {
        padding: 0.75rem;
    }
    
    .gallery-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 0.4rem 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .gallery-date {
        font-size: 0.7rem;
        color: #6c757d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    /* Agenda List */
    .agenda-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .agenda-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .agenda-item:hover {
        background: #e9ecef;
    }
    
    .agenda-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #198754, #157347);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .agenda-details {
        flex: 1;
        min-width: 0;
    }
    
    .agenda-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 0.4rem 0;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .agenda-date {
        font-size: 0.75rem;
        color: #6c757d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .btn-add {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: #0d6efd;
        color: white;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
    }
    
    .btn-add:hover {
        background: #0b5ed7;
        color: white;
    }
    
    /* Responsive Design */
    @media (max-width: 576px) {
        .welcome-card {
            padding: 1.25rem;
        }
        
        .welcome-content {
            flex-direction: column;
            text-align: center;
        }
        
        .welcome-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .welcome-title {
            font-size: 1.2rem;
        }
        
        .welcome-subtitle {
            font-size: 0.85rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .content-header {
            padding: 1rem;
        }
        
        .content-body {
            padding: 1rem;
        }
        
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
    }
    
    @media (min-width: 992px) and (max-width: 1199px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection