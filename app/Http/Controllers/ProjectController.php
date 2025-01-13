<?php

namespace App\Http\Controllers;

use App\Models\ResearchGrant;
use App\Models\Milestone;
use App\Models\Academician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
    public function index()
    {
        $grants = auth()->user()->academician->ledGrants;
        return view('projects.index', compact('grants'));
    }

    public function show(ResearchGrant $grant)
    {
        $this->authorize('view', $grant);
        
        // Get all academicians who are not already members of this grant
        $academicians = \App\Models\Academician::whereNotIn('id', 
            $grant->members->pluck('id')->push($grant->leader_id)
        )->get();

        return view('projects.show', compact('grant', 'academicians'));
    }

    public function addMember(Request $request, ResearchGrant $grant)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:academicians,id'
        ]);

        // Check if member is already in the team
        if ($grant->members->contains($validated['member_id'])) {
            return back()->with('error', 'This academician is already a team member.');
        }

        // Add the member using the pivot table
        $grant->members()->attach($validated['member_id']);

        // Create user account if needed
        $member = Academician::find($validated['member_id']);
        if (!User::where('email', $member->email)->exists()) {
            User::create([
                'name' => $member->name,
                'email' => $member->email,
                'password' => Hash::make('password123'), // Default password
                'is_admin' => false,
                'academician_id' => $member->id
            ]);
        }

        return back()->with('success', 'Team member added successfully.');
    }

    public function removeMember(ResearchGrant $grant, Academician $member)
    {
        // Check if member exists in the team
        if (!$grant->members->contains($member->id)) {
            return back()->with('error', 'This academician is not a team member.');
        }

        // Remove the member
        $grant->members()->detach($member->id);

        return back()->with('success', 'Team member removed successfully.');
    }

    public function createMilestone(ResearchGrant $grant)
    {
        $this->authorize('update', $grant);
        return view('projects.milestones.create', compact('grant'));
    }

    public function storeMilestone(Request $request, ResearchGrant $grant)
    {
        $this->authorize('update', $grant);
        
        $validated = $request->validate([
            'milestone_name' => 'required|string|max:255',
            'target_completion_date' => 'required|date',
            'deliverable' => 'required|string'
        ]);

        // Add default status
        $validated['status'] = 'pending';
        
        // Create the milestone
        $grant->milestones()->create($validated);

        return redirect()
            ->route('projects.show', $grant)
            ->with('success', 'Milestone added successfully.');
    }

    public function editMilestone(ResearchGrant $grant, Milestone $milestone)
    {
        $this->authorize('update', $grant);
        return view('projects.milestones.edit', compact('grant', 'milestone'));
    }

    public function updateMilestone(Request $request, ResearchGrant $grant, Milestone $milestone)
    {
        $this->authorize('update', $grant);
        
        $validated = $request->validate([
            'milestone_name' => 'required|string|max:255',
            'target_completion_date' => 'required|date',
            'deliverable' => 'required|string',
            'status' => 'required|in:pending,completed',
            'remark' => 'nullable|string'
        ]);

        if ($validated['status'] === 'completed' && $milestone->status !== 'completed') {
            $validated['date_updated'] = now();
        }

        $milestone->update($validated);

        return redirect()
            ->route('projects.show', $grant)
            ->with('success', 'Milestone updated successfully');
    }

    public function destroyMilestone(ResearchGrant $grant, Milestone $milestone)
    {
        $this->authorize('update', $grant);
        
        $milestone->delete();

        return back()->with('success', 'Milestone deleted successfully');
    }

    public function edit(ResearchGrant $grant)
    {
        return view('projects.edit', compact('grant'));
    }

    public function update(Request $request, ResearchGrant $grant)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'grant_amount' => 'required|numeric|min:0',
            'grant_provider' => 'required|string|max:255',
            'start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
        ]);

        $grant->update($validated);

        return redirect()
            ->route('projects.show', $grant)
            ->with('success', 'Project updated successfully');
    }
} 