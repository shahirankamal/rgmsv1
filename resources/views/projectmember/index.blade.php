@extends('layouts.app')

@section('title', 'My Projects')

@section('styles')
<style>
    .project-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        background: linear-gradient(145deg, #4f46e5, #6366f1);
        color: white;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
    }
    
    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
    }

    .status-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(5px);
    }

    .project-stats {
        border-left: 3px solid rgba(255, 255, 255, 0.1);
    }

    .hover-lift {
        transition: transform 0.2s;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
    }

    .progress-status {
        padding: 10px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        margin-bottom: 15px;
    }

    .status-step {
        position: relative;
        padding: 8px 15px;
        border-radius: 6px;
        margin-bottom: 8px;
        background: rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .status-step.completed {
        background: rgba(255, 255, 255, 0.2);
    }

    .badge.bg-light {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .project-card .btn-outline-primary {
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .project-card .btn-outline-primary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
    }

    .project-card .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }
</style>
@endsection

@section('content')
<div>
    @if($memberships->isEmpty())
        <div class="alert alert-info">
            You are not currently assigned to any research projects.
        </div>
    @else
        <div class="row g-4">
            @foreach($memberships as $grant)
                <div class="col-md-6 col-lg-4">
                    <div class="card project-card h-100">
                        <div class="card-body p-4">
                            <!-- Status Badge -->
                            <span class="badge status-badge">Active</span>

                            <!-- Project Title & Leader -->
                            <h1 class="card-title mb-1 text-center fw-bold">{{ $grant->title }}</h1>
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-person-circle me-2"></i>
                                <span>{{ $grant->leader->name }}</span>
                            </div>

                            <!-- Project Details -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <span class="fw-bold me-2">Start Date:</span>
                                    <small class="fs-5">{{ $grant->start_date }}</small>
                                </div>
                            </div>

                            <!-- Progress Status Section -->
                            @php
                                $totalMilestones = $grant->milestones->count();
                                $completedMilestones = $grant->milestones->where('status', 'completed')->count();
                                $progressPercentage = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;
                                
                                $status = 'Not Started';
                                if ($progressPercentage == 100) {
                                    $status = 'Completed';
                                } elseif ($progressPercentage > 66) {
                                    $status = 'Final Stage';
                                } elseif ($progressPercentage > 33) {
                                    $status = 'In Progress';
                                } elseif ($progressPercentage > 0) {
                                    $status = 'Initial Stage';
                                }
                            @endphp
                            
                            <div class="progress-status text-center">
                                <div class="status-step {{ $progressPercentage > 0 ? 'completed' : '' }}">
                                    <span class="status-icon">{{ $progressPercentage > 0 ? '✓' : '1' }}</span>
                                    Initial Stage
                                </div>
                                <div class="status-step {{ $progressPercentage > 33 ? 'completed' : '' }}">
                                    <span class="status-icon">{{ $progressPercentage > 34 ? '✓' : '2' }}</span>
                                    In Progress
                                </div>
                                <div class="status-step {{ $progressPercentage > 66 ? 'completed' : '' }}">
                                    <span class="status-icon">{{ $progressPercentage > 67 ? '✓' : '3' }}</span>
                                    Final Stage
                                </div>
                            </div>

                            <!-- Stats Row -->
                            <div class="row g-0 text-center mb-4">
                                <div class="col-6">
                                    <div class="p-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people me-2"></i>
                                            <span>{{ $grant->members->count() }} members</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 project-stats">
                                    <div class="p-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bi bi-flag me-2"></i>
                                            <span>{{ $completedMilestones }}/{{ $totalMilestones }} milestones</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('projectmember.show', $grant) }}" 
                                   class="btn btn-outline-primary p-0 d-flex justify-content-center align-items-center hover-lift"
                                   style="width: 50px; height: 50px; background-color: transparent; border-radius: 50%;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 