@extends('layouts.app')

@section('title', 'Agenda Sekolah')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">Agenda Sekolah</h1>
        <p class="page-subtitle">Jadwal dan informasi kegiatan terbaru SMKN 4 Bogor</p>
    </div>

    @if($agendas->count() > 0)
        <div class="row g-4">
            @foreach($agendas as $agenda)
            <div class="col-md-6 col-lg-4">
                <div class="agenda-card h-100">
                    <div class="card-header-custom">
                        <div class="icon-wrapper">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="header-content">
                            <h5 class="agenda-title">{{ $agenda->title }}</h5>
                        </div>
                    </div>
                    
                    <div class="card-body-custom">
                        <div class="info-item">
                            <div class="info-icon bg-primary-soft">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-text">
                                <small>Tanggal</small>
                                <strong>{{ $agenda->scheduled_at ? $agenda->scheduled_at->translatedFormat('D, d M Y') : '-' }}</strong>
                            </div>
                        </div>
                        
                        @if($agenda->waktu)
                        <div class="info-item">
                            <div class="info-icon bg-info-soft">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-text">
                                <small>Waktu</small>
                                <strong>{{ $agenda->waktu }}</strong>
                            </div>
                        </div>
                        @endif
                        
                        @if($agenda->lokasi)
                        <div class="info-item">
                            <div class="info-icon bg-success-soft">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-text">
                                <small>Lokasi</small>
                                <strong>{{ $agenda->lokasi }}</strong>
                            </div>
                        </div>
                        @endif
                        
                        <p class="agenda-description">
                            {{ $agenda->description }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $agendas->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 my-5">
            <div class="mb-4">
                <i class="fas fa-calendar-alt fa-4x text-muted opacity-25"></i>
            </div>
            <h4 class="mb-2">Belum ada agenda</h4>
            <p class="text-muted mb-0">Silakan periksa kembali di lain waktu</p>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-title {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        margin-bottom: 8px !important;
    }

    .page-subtitle {
        font-size: 0.9rem !important;
        color: #6b7280 !important;
    }

    /* Modern Card Styles */
    .agenda-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.06);
        position: relative;
    }
    
    .agenda-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #0d6efd;
    }
    
    .card-header-custom {
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .icon-wrapper {
        width: 44px;
        height: 44px;
        background: #0d6efd;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(13, 110, 253, 0.25);
    }
    
    .header-content {
        flex: 1;
        min-width: 0;
    }
    
    .agenda-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-body-custom {
        padding: 1rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .info-icon {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    
    .bg-primary-soft {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .bg-info-soft {
        background: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }
    
    .bg-success-soft {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .info-text {
        flex: 1;
        min-width: 0;
    }
    
    .info-text small {
        display: block;
        font-size: 0.65rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.15rem;
        font-weight: 600;
    }
    
    .info-text strong {
        display: block;
        font-size: 0.8rem;
        color: #212529;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .agenda-description {
        color: #6c757d;
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0.75rem 0 0 0;
        text-align: justify;
        white-space: pre-line;
    }

    /* Pagination styles */
    .pagination .page-link {
        color: #0d6efd;
        border: 1px solid #dee2e6;
        margin: 0 5px;
        border-radius: 8px !important;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .pagination .page-link:hover {
        background-color: #f8f9fa;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.3rem !important;
        }
        
        .page-subtitle {
            font-size: 0.85rem !important;
        }
        
        .card-header-custom {
            padding: 0.85rem;
        }
        
        .icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .agenda-title {
            font-size: 0.9rem;
        }
        
        .card-body-custom {
            padding: 0.85rem;
        }
        
        .info-item {
            padding: 0.4rem;
            gap: 0.5rem;
        }
        
        .info-icon {
            width: 28px;
            height: 28px;
            font-size: 0.7rem;
        }
        
        .info-text small {
            font-size: 0.6rem;
        }
        
        .info-text strong {
            font-size: 0.75rem;
        }
        
        .agenda-description {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .agenda-card {
            border-radius: 10px;
        }
        
        .card-header-custom {
            flex-direction: column;
            text-align: center;
            align-items: center;
            padding: 0.75rem;
        }
        
        .header-content {
            text-align: center;
        }
    }
</style>
@endsection
