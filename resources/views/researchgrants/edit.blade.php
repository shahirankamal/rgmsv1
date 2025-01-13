@extends('layouts.app')

@section('title', 'Edit Grant')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Grant</h1>
        <a href="{{ route('researchgrants.show', $grant) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="row">
        <!-- Left Column - Grant Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Grant Information</h5>
                        <!-- You can add a button here if needed -->
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('researchgrants.update', $grant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">Title:</label>
                            <div class="col-sm-10">
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       name="title" 
                                       value="{{ old('title', $grant->title) }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="grant_amount" class="col-sm-2 col-form-label">Amount:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-text">RM</span>
                                    <input type="number" 
                                           class="form-control @error('grant_amount') is-invalid @enderror" 
                                           name="grant_amount" 
                                           value="{{ old('grant_amount', $grant->grant_amount) }}">
                                    @error('grant_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="grant_provider" class="col-sm-2 col-form-label">Provider:</label>
                            <div class="col-sm-10">
                                <input type="text" 
                                       class="form-control @error('grant_provider') is-invalid @enderror" 
                                       name="grant_provider" 
                                       value="{{ old('grant_provider', $grant->grant_provider) }}">
                                @error('grant_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="start_date" class="col-sm-2 col-form-label">Start Date:</label>
                            <div class="col-sm-10">
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       name="start_date" 
                                       value="{{ old('start_date', $grant->start_date) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="duration" class="col-sm-2 col-form-label">Duration:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('duration') is-invalid @enderror" 
                                           name="duration" 
                                           value="{{ old('duration', $grant->duration) }}">
                                    <span class="input-group-text">months</span>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="leader_id" class="col-sm-2 col-form-label">Leader:</label>
                            <div class="col-sm-10">
                                <select name="leader_id" 
                                        class="form-select @error('leader_id') is-invalid @enderror">
                                    <option value="">Select Project Leader</option>
                                    @foreach($academicians as $academician)
                                        <option value="{{ $academician->id }}" 
                                                {{ old('leader_id', $grant->leader_id) == $academician->id ? 'selected' : '' }}>
                                            {{ $academician->name }} ({{ $academician->position }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('leader_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">Update Grant</button>
                                <a href="{{ route('researchgrants.show', $grant) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Team & Milestones -->
        <div class="col-lg-8">
            <div class="row h-100">
                <!-- Team Members Card -->
                <div class="col-md-6 h-100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Research Team</h5>
                                <button type="button" 
                                        class="btn btn-sm btn-light"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#addMemberModal">
                                    <i class="bi bi-plus-lg me-1"></i> Add Member
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="team-members-container">
                                @forelse($grant->members as $member)
                                    <div class="team-member-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="member-avatar me-3">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $member->name }}</h6>
                                                    <span class="member-position">{{ $member->position }}</span>
                                                </div>
                                            </div>
                                            <form action="{{ route('grants.members.remove', [$grant, $member]) }}" 
                                                  method="POST"
                                                  class="member-action">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-icon"
                                                        onclick="return confirm('Remove this member?')"
                                                        title="Remove member">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <p>No team members yet</p>
                                        <small>Click the button above to add members</small>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Milestones Card -->
                <div class="col-md-6 h-100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Project Milestones</h5>
                                <a href="{{ route('grants.milestones.create', $grant) }}" 
                                   class="btn btn-sm btn-light">
                                    <i class="bi bi-plus-lg me-1"></i> Add Milestone
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="milestones-container">
                                @forelse($grant->milestones as $milestone)
                                    <div class="milestone-item">
                                        <div class="milestone-status {{ $milestone->status == 'completed' ? 'completed' : 'pending' }}">
                                            <i class="bi {{ $milestone->status == 'completed' ? 'bi-check-circle-fill' : 'bi-clock' }}"></i>
                                        </div>
                                        <div class="milestone-content">
                                            <h6 class="milestone-title">{{ $milestone->milestone_name }}</h6>
                                            <div class="milestone-date">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $milestone->target_completion_date }}
                                            </div>
                                            <p class="milestone-deliverable">{{ $milestone->deliverable }}</p>
                                        </div>
                                        <div class="milestone-actions">
                                            <a href="{{ route('grants.milestones.edit', [$grant, $milestone]) }}" 
                                               class="btn btn-icon"
                                               title="Edit milestone">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('milestones.destroy', $milestone) }}" 
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-icon text-danger"
                                                        onclick="return confirm('Delete this milestone?')"
                                                        title="Delete milestone">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <i class="bi bi-flag"></i>
                                        <p>No milestones yet</p>
                                        <small>Click the button above to add milestones</small>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('grants.members.add', $grant) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Member</label>
                        <select name="member_id" class="form-select" required>
                            <option value="">Choose an academician</option>
                            @foreach($available_members as $academician)
                                <option value="{{ $academician->id }}">
                                    {{ $academician->name }} ({{ $academician->position }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Add this style section -->
<style>
    .team-members-container,
    .milestones-container {
        height: calc(100vh - 300px);
        overflow-y: auto;
    }

    .row.h-100 {
        margin-right: 0;
        margin-left: 0;
    }

    .col-md-6.h-100 {
        padding-right: 8px;
        padding-left: 8px;
    }

    .card {
        margin-bottom: 1rem;
    }

    .h-100 {
        height: 100% !important;
    }

    .team-member-item,
    .milestone-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .team-member-item:hover,
    .milestone-item:hover {
        background-color: #f8f9fa;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #495057;
    }

    .member-position {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .milestone-item {
        display: grid;
        grid-template-columns: 30px 1fr auto;
        gap: 0.75rem;
        align-items: start;
    }

    .milestone-status {
        font-size: 1.5rem;
        padding-top: 0.25rem;
    }

    .milestone-status.completed {
        color: #198754;
    }

    .milestone-status.pending {
        color: #ffc107;
    }

    .milestone-title {
        margin-bottom: 0.25rem;
    }

    .milestone-date {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .milestone-deliverable {
        font-size: 0.875rem;
        margin-bottom: 0;
        color: #495057;
    }

    .btn-icon {
        padding: 0.25rem;
        line-height: 1;
        border: none;
        background: none;
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        transform: scale(1.1);
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .empty-state p {
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .empty-state small {
        font-size: 0.875rem;
    }

    /* Custom scrollbar */
    .team-members-container::-webkit-scrollbar,
    .milestones-container::-webkit-scrollbar {
        width: 6px;
    }

    .team-members-container::-webkit-scrollbar-track,
    .milestones-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .team-members-container::-webkit-scrollbar-thumb,
    .milestones-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .team-members-container::-webkit-scrollbar-thumb:hover,
    .milestones-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .milestone-content {
        min-width: 0;
    }

    .milestone-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .milestone-deliverable {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection