@extends('layouts.app')

@section('title', $grant->title)

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0 text-primary fw-bold">{{ $grant->title }}</h1>
            <p class="text-muted mb-0 small">Project Details</p>
        </div>
        <div>
            <a href="{{ route('projects.edit', $grant) }}" class="btn btn-soft-primary me-2">
                <i class="bi bi-pencil-square me-1"></i>Edit Project
            </a>
            <a href="{{ route('projects.index') }}" class="btn btn-soft-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Projects
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-4">
            <!-- Grant Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-dark-blue text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Grant Information
                    </h5>  
                </div>
                <div class="card-body">
                    <dl>
                        <dt>Grant Amount</dt>
                        <dd>RM {{ number_format($grant->grant_amount, 2) }}</dd>

                        <dt>Provider</dt>
                        <dd>{{ $grant->grant_provider }}</dd>

                        <dt>Start Date</dt>
                        <dd>{{ $grant->start_date }}</dd>

                        <dt>Duration</dt>
                        <dd>{{ $grant->duration }} months</dd>

                        <dt>End Date</dt>
                        <dd>
                            {{ \Carbon\Carbon::parse($grant->start_date)->addMonths($grant->duration)->format('Y-m-d') }}
                        </dd>

                        <dt>Status</dt>
                        <dd>
                            @php
                                $totalMilestones = $grant->milestones->count();
                                $completedMilestones = $grant->milestones->where('status', 'completed')->count();
                                $progressPercentage = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $progressPercentage }}%">
                                    {{ number_format($progressPercentage) }}% Complete
                                </div>
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Team Members Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-success text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Team Structure
                    </h5>
                    <button type="button" 
                            class="btn btn-sm btn-light rounded-circle" 
                            style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;"
                            data-bs-toggle="modal" 
                            data-bs-target="#addMemberModal">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="org-chart">
                        <!-- Leader Node -->
                        <div class="leader-section text-center mb-4">
                            <div class="avatar-circle mb-2">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h6 class="mb-0">{{ $grant->leader->name }}</h6>
                            <span class="badge bg-dark">Project Leader</span>
                        </div>

                        <!-- Vertical Line -->
                        <div class="vertical-line"></div>

                        <!-- Members Container -->
                        <div class="members-container">
                            @foreach($grant->members as $member)
                                <div class="member-section text-center">
                                    <div class="avatar-circle mb-2">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <h6 class="mb-0">{{ $member->name }}</h6>
                                    <small class="text-muted d-block">{{ $member->position }}</small>
                                    <form action="{{ route('projects.removeMember', [$grant, $member]) }}" 
                                          method="POST"
                                          class="remove-member-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-link text-danger"
                                                onclick="return confirm('Remove this member?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-8">
            <!-- Milestones Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-warning text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-flag me-2"></i>Project Milestones
                    </h5>
                    <a href="{{ route('projects.milestones.create', $grant) }}" 
                       class="btn btn-sm btn-light">
                        <i class="bi bi-plus-lg me-1"></i>Add Milestone
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-secondary">Milestone</th>
                                    <th class="text-secondary">Target Date</th>
                                    <th class="text-secondary">Deliverable</th>
                                    <th class="text-secondary">Status</th>
                                    <th class="text-secondary">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grant->milestones as $milestone)
                                    <tr>
                                        <td class="fw-medium">{{ $milestone->milestone_name }}</td>
                                        <td>
                                            <i class="bi bi-calendar-event text-muted me-1"></i>
                                            {{ \Carbon\Carbon::parse($milestone->target_completion_date)->format('d M Y') }} 
                                        </td>
                                        <td>{{ $milestone->deliverable }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-success-subtle text-success',
                                                    'in_progress' => 'bg-warning-subtle text-warning',
                                                    'pending' => 'bg-info-subtle text-info',
                                                    'delayed' => 'bg-danger-subtle text-danger'
                                                ];
                                                $statusClass = $statusClasses[$milestone->status] ?? 'bg-secondary-subtle text-secondary';
                                            @endphp
                                            <span class="badge rounded-pill {{ $statusClass }} px-3 py-2">
                                                <i class="bi bi-circle-fill me-1 small"></i>
                                                {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('projects.milestones.edit', [$grant, $milestone]) }}" 
                                                   class="btn btn-soft-primary btn-sm px-3">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('projects.milestones.destroy', [$grant, $milestone]) }}" 
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-soft-danger btn-sm px-3"
                                                            onclick="return confirm('Are you sure you want to delete this milestone?')">
                                                        <i class="bi bi-trash me-1"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-clipboard-x fs-4 mb-3 d-block"></i>
                                                No milestones added yet
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            <form action="{{ route('projects.members.add', $grant) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Member</label>
                        <select name="member_id" class="form-select" required>
                            <option value="">Choose an academician</option>
                            @foreach($academicians as $academician)
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

<!-- Add Milestone Modal -->
<div class="modal fade" id="addMilestoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Milestone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.milestones.store', $grant) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Milestone Name</label>
                        <input type="text" class="form-control" name="milestone_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Date</label>
                        <input type="date" class="form-control" name="target_completion_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deliverable</label>
                        <textarea class="form-control" 
                                 name="deliverable" 
                                 rows="3" 
                                 required 
                                 placeholder="Describe the expected deliverable for this milestone"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Milestone Modals -->
@foreach($grant->milestones as $milestone)
    <div class="modal fade" id="editMilestone{{ $milestone->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Milestone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('projects.milestones.update', [$grant, $milestone]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Milestone Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="milestone_name" 
                                   value="{{ $milestone->milestone_name }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Target Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   name="target_completion_date" 
                                   value="{{ $milestone->target_completion_date }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deliverable</label>
                            <textarea class="form-control" 
                                     name="deliverable" 
                                     rows="3" 
                                     required>{{ $milestone->deliverable }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $milestone->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $milestone->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" 
                                     name="remark" 
                                     rows="3">{{ $milestone->remark }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Milestone</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

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

<style>
    .org-chart {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
    }

    .avatar-circle {
        width: 64px;
        height: 64px;
        background-color: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .avatar-circle i {
        font-size: 32px;
        color: #6c757d;
    }

    .leader-section .avatar-circle {
        border-color: #198754;
    }

    .leader-section .avatar-circle i {
        color: #198754;
    }

    .vertical-line {
        width: 2px;
        height: 40px;
        background-color: #dee2e6;
        margin: 0 auto;
    }

    .members-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        position: relative;
        padding-top: 1rem;
        width: 100%;
    }

    .members-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 80%;
        height: 2px;
        background-color: #dee2e6;
        transform: translateX(-50%);
    }

    .member-section {
        position: relative;
        min-width: 120px;
    }

    .member-section::before {
        content: '';
        position: absolute;
        top: -1rem;
        left: 50%;
        width: 2px;
        height: 1rem;
        background-color: #dee2e6;
    }

    .remove-member-form {
        position: absolute;
        top: 0;
        right: 0;
    }

    .remove-member-form button {
        padding: 0;
        font-size: 1rem;
    }

    .btn-soft-primary {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        border-color: transparent;
    }
    
    .btn-soft-primary:hover {
        color: #fff;
        background-color: #0d6efd;
    }
    
    .btn-soft-danger {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
        border-color: transparent;
    }
    
    .btn-soft-danger:hover {
        color: #fff;
        background-color: #dc3545;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #0143a3, #0d6efd);
    }
    
    .bg-gradient-success {
        background: linear-gradient(45deg, #0f5132, #198754);
    }
    
    .bg-gradient-dark-blue {
        background: linear-gradient(45deg, #12263f, #1e4db7);
    }
    
    .btn-soft-secondary {
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
        border-color: transparent;
        transition: all 0.3s ease;
    }
    
    .btn-soft-secondary:hover {
        color: #fff;
        background-color: #6c757d;
    }
    
    .card {
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Update modal headers to match the new style */
    .modal .modal-header {
        background: linear-gradient(45deg, #0143a3, #0d6efd);
        color: white;
        border-bottom: 0;
    }
    
    .modal .btn-close {
        filter: brightness(0) invert(1);
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        color: #000 !important; /* Making text dark for better contrast */
    }
    
    /* Update the Add Milestone button for better contrast */
    .bg-gradient-warning .btn-light {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: #000;
    }
    
    .bg-gradient-warning .btn-light:hover {
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection 