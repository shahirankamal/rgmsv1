@extends('layouts.app')

@section('title', $grant->title)

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0 text-primary fw-bold">{{ $grant->title }}</h1>
            <p class="text-muted mb-0 small">Project Details</p>
        </div>
        <a href="{{ route('projectmember.index') }}" class="btn btn-soft-secondary"
            <i class="bi bi-arrow-left me-1"></i>Back to My Projects
        </a>
    </div>

    <!-- Progress Overview Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-graph-up me-2"></i>Project Progress
            </h5>
        </div>
        <div class="card-body">
            @php
                $totalMilestones = $grant->milestones->count();
                $completedMilestones = $grant->milestones->where('status', 'completed')->count();
                $progressPercentage = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;
            @endphp
            <div class="progress" style="height:45px;">
                <div class="progress-bar bg-success" 
                     role="progressbar" 
                     style="width: {{ $progressPercentage }}%">
                    {{ number_format($progressPercentage) }}% Complete
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-4">
            <!-- Grant Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-dark-blue text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Grant Details
                    </h5>
                </div>
                <div class="card-body">
                    <dl>
                        <dt class="text-muted">Grant Amount</dt>
                        <dd class="mb-3 fs-5">RM {{ number_format($grant->grant_amount, 2) }}</dd>

                        <dt class="text-muted">Provider</dt>
                        <dd class="mb-3">{{ $grant->grant_provider }}</dd>

                        <dt class="text-muted">Start Date</dt>
                        <dd class="mb-3">{{ $grant->start_date }}</dd>

                        <dt class="text-muted">Duration</dt>
                        <dd class="mb-3">{{ $grant->duration }} months</dd>

                        <dt class="text-muted">End Date</dt>
                        <dd>{{ \Carbon\Carbon::parse($grant->start_date)->addMonths($grant->duration)->format('Y-m-d') }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Research Team Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Team Structure
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Project Leader -->
                    <div class="text-center mb-4">
                        <div class="leader-avatar mb-2">
                            <i class="bi bi-person-circle text-black" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mb-1">{{ $grant->leader->name }}</h5>
                        <span class="badge bg-black px-3">Project Leader</span>
                    </div>

                    <!-- Vertical connector line -->
                    @if($grant->members->count() > 0)
                        <div class="d-flex justify-content-center">
                            <div class="vertical-line" style="width: 2px; height: 40px; background-color: #dee2e6;"></div>
                        </div>
                    @endif

                    <!-- Team Members Grid -->
                    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center mt-2">
                        @foreach($grant->members as $member)
                            <div class="col">
                                <div class="team-member-card text-center p-3 border rounded">
                                    <div class="member-avatar mb-2">
                                        <i class="bi bi-person text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="mb-1">{{ $member->name }}</h6>
                                    <small class="text-muted d-block">{{ $member->position }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Milestones -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-warning text-dark py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-flag me-2"></i>Project Milestones
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($grant->milestones as $milestone)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-3 flex-grow-1">
                                        <h5 class="mb-2">{{ $milestone->milestone_name }}</h5>
                                        <p class="mb-1 text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Due: {{ $milestone->target_completion_date }}
                                        </p>
                                        @if($milestone->deliverable)
                                            <p class="mb-0">
                                                <i class="bi bi-file-text me-1"></i>
                                                {{ $milestone->deliverable }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="status-badge d-flex align-items-center">
                                        <div class="rectangle-badge {{ $milestone->status == 'completed' ? 'bg-success' : 'bg-warning' }} d-flex align-items-center justify-content-center">
                                            {{ ucfirst($milestone->status) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="bi bi-clipboard-x mb-2 fs-4"></i>
                                <p class="mb-0">No milestones have been added to this project yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-badge {
        min-width: 120px;
    }
    .rectangle-badge {
        width: 120px;
        height: 40px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
        text-align: center;
        text-transform: uppercase;
        border-radius: 6px;
    }
    .leader-avatar {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .member-avatar {
        width: 60px;
        height: 60px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .team-member-card {
        transition: transform 0.2s;
    }

    .team-member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    
    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        color: #000 !important;
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

    /* Update the progress bar styling */
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    /* Update list group styling */
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 1.25rem;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endsection 