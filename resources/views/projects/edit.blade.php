@extends('layouts.app')

@section('title', 'Edit Project - ' . $grant->title)

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0 text-primary fw-bold">Edit Project</h1>
            <p class="text-muted mb-0 small">{{ $grant->title }}</p>
        </div>
        <a href="{{ route('projects.show', $grant) }}" class="btn btn-soft-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Project
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('projects.update', $grant) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               name="title" 
                               value="{{ old('title', $grant->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Grant Amount (RM)</label>
                        <input type="number" 
                               class="form-control @error('grant_amount') is-invalid @enderror" 
                               name="grant_amount" 
                               value="{{ old('grant_amount', $grant->grant_amount) }}" 
                               step="0.01" 
                               required>
                        @error('grant_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Grant Provider</label>
                        <input type="text" 
                               class="form-control @error('grant_provider') is-invalid @enderror" 
                               name="grant_provider" 
                               value="{{ old('grant_provider', $grant->grant_provider) }}" 
                               required>
                        @error('grant_provider')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" 
                               class="form-control @error('start_date') is-invalid @enderror" 
                               name="start_date" 
                               value="{{ old('start_date', $grant->start_date) }}" 
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration (months)</label>
                        <input type="number" 
                               class="form-control @error('duration') is-invalid @enderror" 
                               name="duration" 
                               value="{{ old('duration', $grant->duration) }}" 
                               required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('projects.show', $grant) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-soft-primary {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        border-color: transparent;
    }
    
    .btn-soft-primary:hover {
        color: #fff;
        background-color: #0d6efd;
    }
    
    .btn-soft-secondary {
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
        border-color: transparent;
    }
    
    .btn-soft-secondary:hover {
        color: #fff;
        background-color: #6c757d;
    }
</style>
@endsection 