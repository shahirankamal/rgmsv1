@extends('layouts.app')

@section('title', $academician->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-sm-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Academician Profile</h1>
                    <p class="mb-0 text-muted">View academician details and research grants</p>
                </div>
                <div class="mt-3 mt-sm-0">
                    <a href="{{ route('academicians.edit', $academician) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                    <a href="{{ route('academicians.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="avatar-circle-lg bg-primary text-white mx-auto mb-3">
                        {{ strtoupper(substr($academician->name, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ $academician->name }}</h4>
                    <p class="text-muted mb-3">{{ $academician->position }}</p>
                    <hr>
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="text-muted small">Staff Number</label>
                            <p class="mb-0">{{ $academician->staff_number }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $academician->email }}" class="text-decoration-none">
                                    {{ $academician->email }}
                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Department</label>
                            <p class="mb-0">{{ $academician->department }}</p>
                        </div>
                        <div>
                            <label class="text-muted small">College</label>
                            <p class="mb-0">{{ $academician->college }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Research Grants as Leader -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: linear-gradient(45deg, #3a8bff, #0063e6); border: none;">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">Research Grants as Leader</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($academician->ledGrants->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-folder-x display-4 d-block mb-2"></i>
                            No research grants as leader.
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($academician->ledGrants as $grant)
                                <div class="list-group-item p-4 grant-card">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold">{{ $grant->title }}</h6>
                                        <span class="fw-semibold">
                                            RM {{ number_format($grant->grant_amount, 2) }}
                                        </span>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-building text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">Provider</small>
                                                    <span class="fw-medium">{{ $grant->grant_provider }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">Start Date</small>
                                                    <span class="fw-medium">{{ $grant->start_date }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">Duration</small>
                                                    <span class="fw-medium">{{ $grant->duration }} months</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($grant->members->isNotEmpty())
                                        <div class="team-members-section">
                                            <small class="text-muted d-block mb-2">
                                                <i class="bi bi-people-fill text-primary me-1"></i> Team Members
                                            </small>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                @foreach($grant->members as $member)
                                                    <div class="member-badge">
                                                        <div class="member-avatar">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                        <span class="member-name">{{ $member->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($grant->milestones->isNotEmpty())
                                        <div class="mt-3">
                                            <small class="text-muted d-block mb-2">
                                                <i class="bi bi-flag-fill text-primary me-1"></i> Progress
                                            </small>
                                            <div class="progress rounded-pill shadow-sm" style="height: 12px; background-color: #f8f9fa;">
                                                @php
                                                    $completed = $grant->milestones->where('status', 'completed')->count();
                                                    $total = $grant->milestones->count();
                                                    $percentage = ($total > 0) ? ($completed / $total * 100) : 0;
                                                @endphp
                                                <div class="progress-bar rounded-pill" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%; background: linear-gradient(45deg, #2196F3, #00BCD4);"
                                                     aria-valuenow="{{ $percentage }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted mt-1 d-block">
                                                {{ $completed }} of {{ $total }} milestones completed ({{ number_format($percentage, 1) }}%)
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Research Grants as Member -->
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background: linear-gradient(45deg, #3a8bff, #0063e6); border: none;">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white">Research Grants as Member</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($academician->memberGrants->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-folder-x display-4 d-block mb-2"></i>
                            Not a member of any research grants.
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($academician->memberGrants as $grant)
                                <div class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0">{{ $grant->title }}</h6>
                                            <small class="text-muted">
                                                Led by {{ $grant->leader->name }}
                                            </small>
                                        </div>
                                        <span class="fw-semibold">
                                            RM {{ number_format($grant->grant_amount, 2) }}
                                        </span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Provider</small>
                                            <span class="badge bg-light text-dark">{{ $grant->grant_provider }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Start Date</small>
                                            {{ $grant->start_date }}
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block">Duration</small>
                                            <span class="badge bg-info">{{ $grant->duration }} months</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle-lg {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .badge {
        padding: 0.5rem 0.8rem;
        font-weight: 500;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .grant-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .grant-card:hover {
        border-left-color: #0d6efd;
        transform: translateX(4px);
    }

    .member-badge {
        display: flex;
        align-items: center;
        background-color: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.2s ease;
    }

    .member-badge:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    .member-avatar {
        width: 28px;
        height: 28px;
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: bold;
        margin-right: 0.5rem;
    }

    .member-name {
        font-size: 0.875rem;
        color: #495057;
    }

    .team-members-section {
        padding: 1rem;
        background-color: white;
        border-radius: 0.5rem;
        margin-top: 1rem;
        border: 1px solid #e9ecef;
    }

    .card-header:first-child {
        border-radius: 0.5rem 0.5rem 0 0;
    }
    
    .card {
        border-radius: 0.5rem;
    }
</style>

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@endsection 