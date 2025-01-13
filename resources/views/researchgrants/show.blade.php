@extends('layouts.app')

@section('title', $grant->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-sm-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Research Grant Details</h1>
                    <p class="mb-0 text-muted">View grant information and team members</p>
                </div>
                <div class="mt-3 mt-sm-0">
                    <a href="{{ route('researchgrants.edit', $grant) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Grant
                    </a>
                    <a href="{{ route('researchgrants.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="row">
        <!-- Left Column - Grant Information -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="grant-icon-lg bg-primary text-white mx-auto mb-3">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4 class="mb-1">{{ $grant->title }}</h4>
                    <span class="badge bg-success mb-3">
                        RM {{ number_format($grant->grant_amount, 2) }}
                    </span>
                    <hr>
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="text-muted small">Grant Provider</label>
                            <p class="mb-0">{{ $grant->grant_provider }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Start Date</label>
                            <p class="mb-0">{{ $grant->start_date }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="mb-0">{{ $grant->duration }} months</p>
                        </div>
                        <div>
                            <label class="text-muted small">Project Leader</label>
                            <div class="d-flex align-items-center mt-1">
                                <div class="avatar-circle bg-info text-white me-2">
                                    {{ strtoupper(substr($grant->leader->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="mb-0">{{ $grant->leader->name }}</p>
                                    <small class="text-muted">{{ $grant->leader->position }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Team Members & Milestones -->
        <div class="col-lg-8">
            <!-- Team Members Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Team Members</h5>
                        <span class="badge bg-primary ms-2">{{ $grant->members->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($grant->members->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-people display-4 d-block mb-2"></i>
                            No team members assigned yet.
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($grant->members as $member)
                                <div class="list-group-item p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-light text-dark me-3">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $member->name }}</h6>
                                            <small class="text-muted">{{ $member->position }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Milestones Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Milestones</h5>
                        <span class="badge bg-secondary ms-2">{{ $grant->milestones->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($grant->milestones->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-flag display-4 d-block mb-2"></i>
                            No milestones added yet.
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($grant->milestones as $milestone)
                                <div class="list-group-item p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $milestone->milestone_name }}</h6>
                                        <span class="badge {{ $milestone->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($milestone->status) }}
                                        </span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Target Date</small>
                                            {{ $milestone->target_completion_date }}
                                        </div>
                                        @if($milestone->remark)
                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Remarks</small>
                                                {{ $milestone->remark }}
                                            </div>
                                        @endif
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
    .grant-icon-lg {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .badge {
        padding: 0.5rem 0.8rem;
        font-weight: 500;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
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