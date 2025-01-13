@extends('layouts.app')

@section('title', 'Create Research Grant')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create Research Grant</h5>
                    <a href="{{ route('researchgrants.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('researchgrants.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="grant_amount" class="form-label">Grant Amount (RM)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('grant_amount') is-invalid @enderror" 
                                   id="grant_amount" 
                                   name="grant_amount" 
                                   value="{{ old('grant_amount') }}" 
                                   required>
                            @error('grant_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="grant_provider" class="form-label">Grant Provider</label>
                            <input type="text" 
                                   class="form-control @error('grant_provider') is-invalid @enderror" 
                                   id="grant_provider" 
                                   name="grant_provider" 
                                   value="{{ old('grant_provider') }}" 
                                   required>
                            @error('grant_provider')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (months)</label>
                            <input type="number" 
                                   class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" 
                                   name="duration" 
                                   value="{{ old('duration') }}" 
                                   required>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="leader_id" class="form-label">Project Leader</label>
                            <select class="form-select select2 @error('leader_id') is-invalid @enderror" 
                                    id="leader_id" 
                                    name="leader_id" 
                                    data-placeholder="Search for a Project Leader"
                                    required>
                                <option value=""></option>
                                @foreach($academicians as $academician)
                                    <option value="{{ $academician->id }}" 
                                        {{ old('leader_id') == $academician->id ? 'selected' : '' }}>
                                        {{ $academician->name }} ({{ $academician->position }})
                                    </option>
                                @endforeach
                            </select>
                            @error('leader_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Project Members</label>
                            <div class="member-inputs">
                                <!-- Initial container will be empty -->
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-member">
                                <i class="bi bi-plus-circle me-1"></i>Add Member
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Create Research Grant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to create member select HTML
    function getMemberSelectHTML() {
        return `
            <option value="">Select a member</option>
            @foreach($academicians as $academician)
                <option value="{{ $academician->id }}">
                    {{ $academician->name }} ({{ $academician->position }})
                </option>
            @endforeach
        `;
    }

    // Function to initialize Select2 on an element
    function initializeSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            placeholder: 'Search for a Project Member',
            allowClear: true,
            width: '100%'
        });
    }

    // Project member addition code
    const addButton = document.getElementById('add-member');
    const container = document.querySelector('.member-inputs');

    addButton.addEventListener('click', function() {
        const wrapper = document.createElement('div');
        wrapper.className = 'member-row mb-2 d-flex';
        
        // Create new select element
        const select = document.createElement('select');
        select.className = 'form-select select2-members';
        select.name = 'members[]';
        select.required = true;
        select.innerHTML = getMemberSelectHTML();
        
        // Create remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm ms-2 remove-member';
        removeBtn.innerHTML = '<i class="bi bi-trash"></i>';
        
        // Add remove button functionality
        removeBtn.addEventListener('click', function() {
            wrapper.remove();
            updateRemoveButtons();
        });
        
        wrapper.appendChild(select);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);

        // Initialize Select2 on the new select
        initializeSelect2(select);
        updateRemoveButtons();
    });

    // Function to update remove buttons visibility
    function updateRemoveButtons() {
        const memberRows = document.querySelectorAll('.member-row');
        memberRows.forEach(row => {
            const removeBtn = row.querySelector('.remove-member');
            if (removeBtn) {
                removeBtn.style.display = memberRows.length > 1 ? 'block' : 'none';
            }
        });
    }

    // Add click handler for remove buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            e.target.closest('.member-row').remove();
            updateRemoveButtons();
        }
    });
});
</script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true
    });
});
</script>
@endpush
@endsection








