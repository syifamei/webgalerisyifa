@extends('layouts.app')

@section('content')
<div class="container py-5">
    <a href="{{ route('agenda.index') }}" class="btn btn-outline-primary mb-4">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Agenda
    </a>
    
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 mb-2 fw-bold">{{ $agenda->title }}</h1>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-calendar me-2"></i>
                            {{ $agenda->scheduled_at ? $agenda->scheduled_at->format('d F Y') : '-' }}
                        </span>
                        @if($agenda->status)
                        <span class="badge {{ $agenda->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                            {{ $agenda->status }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <!-- Informasi Waktu dan Lokasi -->
            @if($agenda->waktu || $agenda->lokasi)
            <div class="row mb-4">
                @if($agenda->waktu)
                <div class="col-md-6 mb-3">
                    <div class="info-box p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Waktu</small>
                                <strong class="fs-5">{{ $agenda->waktu }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($agenda->lokasi)
                <div class="col-md-6 mb-3">
                    <div class="info-box p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-success text-white rounded-circle p-3 me-3">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Lokasi</small>
                                <strong class="fs-5">{{ $agenda->lokasi }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Deskripsi -->
            <div class="description-section">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-align-left me-2 text-primary"></i>
                    Deskripsi
                </h5>
                <div class="description-content">
                    {!! nl2br(e($agenda->description)) !!}
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="fas fa-info-circle me-1"></i>
                    Dipublikasikan: {{ $agenda->created_at ? $agenda->created_at->format('d M Y, H:i') : '-' }}
                </span>
                @if($agenda->updated_at && $agenda->updated_at != $agenda->created_at)
                <span>
                    <i class="fas fa-edit me-1"></i>
                    Diperbarui: {{ $agenda->updated_at->format('d M Y, H:i') }}
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
    
    .card-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    }
    
    .info-box {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .info-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .info-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .description-content {
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
    
    @media (max-width: 768px) {
        .card-header h1 {
            font-size: 1.5rem;
        }
        
        .info-icon {
            width: 48px;
            height: 48px;
        }
        
        .info-box strong {
            font-size: 1rem !important;
        }
    }
</style>
@endsection


