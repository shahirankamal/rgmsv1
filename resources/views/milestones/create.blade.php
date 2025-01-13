@extends('layouts.app')

@section('title', 'Add Milestone')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Milestone</h1>
        <a href="{{ route('researchgrants.edit', $grant) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('grants.milestones.store', $grant) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Milestone Name</label>
                            <input type="text" 
                                   class="form-control @error('milestone_name') is-invalid @enderror" 
                                   name="milestone_name" 
                                   value="{{ old('milestone_name') }}" 
                                   required>
                            @error('milestone_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Date</label>
                            <input type="date" 
                                   class="form-control @error('target_completion_date') is-invalid @enderror" 
                                   name="target_completion_date" 
                                   value="{{ old('target_completion_date') }}" 
                                   required>
                            @error('target_completion_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deliverable</label>
                            <textarea class="form-control @error('deliverable') is-invalid @enderror" 
                                     name="deliverable" 
                                     rows="4" 
                                     required 
                                     placeholder="Describe the expected deliverable for this milestone">{{ old('deliverable') }}</textarea>
                            @error('deliverable')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('researchgrants.edit', $grant) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Milestone</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Grant Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $grant->title }}</p>
                    <p><strong>Provider:</strong> {{ $grant->grant_provider }}</p>
                    <p><strong>Duration:</strong> {{ $grant->duration }} months</p>
                    <p><strong>Start Date:</strong> {{ $grant->start_date }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 