@extends('layouts.app')

@section('title', 'Informasi Sekolah - SMKN 4 BOGOR')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">Informasi Sekolah</h1>
        <p class="page-subtitle">Informasi terbaru dan penting seputar kegiatan sekolah SMKN 4 Bogor</p>
    </div>

    @if($informasis->count() > 0)
        <div class="row g-4">
            @foreach($informasis as $informasi)
            <div class="col-md-6 col-lg-4">
                <div class="informasi-card h-100">
                    <div class="card-header-custom">
                        <div class="icon-wrapper">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="header-content">
                            <h5 class="informasi-title">{{ $informasi->judul }}</h5>
                        </div>
                    </div>
                    
                    <div class="card-body-custom">
                        <p class="informasi-description">
                            {{ $informasi->deskripsi }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $informasis->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 my-5">
            <div class="mb-4">
                <i class="fas fa-newspaper fa-4x text-muted opacity-25"></i>
            </div>
            <h4 class="mb-2">Belum ada informasi</h4>
            <p class="text-muted mb-0">Informasi sekolah akan ditampilkan di sini</p>
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
    .informasi-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.06);
        position: relative;
    }
    
    .informasi-card::before {
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
    
    .informasi-title {
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
    
    .informasi-description {
        color: #6c757d;
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0;
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

    /* Empty state */
    .informasi-empty { 
        width: 100%;
        text-align: center; 
        padding: 3rem 1rem; 
        background: #f8f9fa; 
        border: 1px dashed #dee2e6; 
        border-radius: 12px;
        margin: 2rem 0;
    }
    
    .informasi-empty i {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 1rem;
    }

    /* Status badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
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
        
        .informasi-title {
            font-size: 0.9rem;
        }
        
        .card-body-custom {
            padding: 0.85rem;
        }
        
        .informasi-description {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .informasi-card {
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
        
        .badge-status {
            font-size: 0.6rem;
        }
    }
</style>
@endsection
