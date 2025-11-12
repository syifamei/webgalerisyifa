@extends('layouts.app')

@section('title', $informasi->judul . ' - SMKN 4 BOGOR')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white py-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 mb-2 fw-bold">{{ $informasi->judul }}</h1>
                    <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-calendar me-2"></i>
                            {{ $informasi->tanggal_posting ? \Carbon\Carbon::parse($informasi->tanggal_posting)->format('d F Y') : '-' }}
                        </span>
                        @if($informasi->admin)
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-user me-2"></i>
                            {{ $informasi->admin->name }}
                        </span>
                        @endif
                        @if($informasi->status)
                        <span class="badge {{ $informasi->status === 'Aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                            {{ $informasi->status }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            @if($informasi->gambar)
            <div class="mb-4">
                <img src="{{ $informasi->gambar_url }}" alt="{{ $informasi->judul }}" 
                     class="img-fluid rounded shadow-sm" style="width: 100%; max-height: 500px; object-fit: cover;">
            </div>
            @endif
            
            <!-- Deskripsi Singkat -->
            @if($informasi->deskripsi)
            <div class="mb-4">
                <div class="alert alert-light border-start border-primary border-4" role="alert">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Ringkasan
                    </h6>
                    <p class="mb-0">{{ $informasi->deskripsi }}</p>
                </div>
            </div>
            @endif
            
            <!-- Konten -->
            <div class="content-section">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-align-left me-2 text-primary"></i>
                    Informasi Lengkap
                </h5>
                <div class="content-body">
                    {!! nl2br(e($informasi->konten ?? $informasi->deskripsi)) !!}
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center text-muted small flex-wrap gap-2">
                <span>
                    <i class="fas fa-clock me-1"></i>
                    Dipublikasikan: {{ $informasi->created_at ? $informasi->created_at->format('d M Y, H:i') : '-' }}
                </span>
                @if($informasi->updated_at && $informasi->updated_at != $informasi->created_at)
                <span>
                    <i class="fas fa-edit me-1"></i>
                    Diperbarui: {{ $informasi->updated_at->format('d M Y, H:i') }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .bg-gradient-primary {
        background: #0d6efd;
    }
    
    .card-header {
        background: #0d6efd;
    }
    
    .content-body {
        font-size: 1rem;
        line-height: 1.8;
        color: #495057;
        text-align: justify;
    }
    
    .btn-outline-primary {
        border-width: 2px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    
    .alert-light {
        background-color: #f8f9fc;
    }
    
    @media (max-width: 768px) {
        .card-header h1 {
            font-size: 1.5rem;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem !important;
        }
    }
</style>
@endsection

