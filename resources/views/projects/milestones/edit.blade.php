@extends('layouts.app')

@section('title', 'Edit Milestone - ' . $grant->title)

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0 text-primary fw-bold">Edit Milestone</h1>
            <p class="text-muted mb-0 small">{{ $grant->title }}</p>
        </div>
        <a href="{{ route('projects.show', $grant) }}" class="btn btn-soft-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Project
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('projects.milestones.update', [$grant, $milestone]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Milestone Name</label>
                        <input type="text" 
                               class="form-control @error('milestone_name') is-invalid @enderror" 
                               name="milestone_name" 
                               value="{{ old('milestone_name', $milestone->milestone_name) }}" 
                               required>
                        @error('milestone_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Target Completion Date</label>
                        <input type="date" 
                               class="form-control @error('target_completion_date') is-invalid @enderror" 
                               name="target_completion_date" 
                               value="{{ old('target_completion_date', $milestone->target_completion_date) }}" 
                               required>
                        @error('target_completion_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Deliverable</label>
                        <textarea class="form-control @error('deliverable') is-invalid @enderror" 
                                 name="deliverable" 
                                 rows="4" 
                                 required>{{ old('deliverable', $milestone->deliverable) }}</textarea>
                        @error('deliverable')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $milestone->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $milestone->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control @error('remark') is-invalid @enderror" 
                                 name="remark" 
                                 rows="3">{{ old('remark', $milestone->remark) }}</textarea>
                        @error('remark')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('projects.show', $grant) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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