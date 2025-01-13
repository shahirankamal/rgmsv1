<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\ResearchGrant;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function create(ResearchGrant $grant)
    {
        return view('milestones.create', compact('grant'));
    }

    public function store(Request $request, ResearchGrant $grant)
    {
        $validated = $request->validate([
            'milestone_name' => 'required|string|max:255',
            'target_completion_date' => 'required|date',
            'deliverable' => 'required|string',
        ]);

        // Add the grant_id and default status
        $validated['grant_id'] = $grant->id;
        $validated['status'] = 'pending';

        $milestone = Milestone::create($validated);

        return redirect()->route('researchgrants.edit', $grant)
            ->with('success', 'Milestone created successfully.');
    }

    public function edit(ResearchGrant $grant, Milestone $milestone)
    {
        return view('milestones.edit', compact('grant', 'milestone'));
    }

    public function update(Request $request, ResearchGrant $grant, Milestone $milestone)
    {
        $validated = $request->validate([
            'milestone_name' => 'required|string|max:255',
            'target_completion_date' => 'required|date',
            'deliverable' => 'required|string',
            'status' => 'required|in:pending,completed',
            'remark' => 'nullable|string'
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completion_date'] = now();
        }

        $milestone->update($validated);

        return redirect()->route('researchgrants.edit', $grant)
            ->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();
        return back()->with('success', 'Milestone deleted successfully.');
    }

    public function complete(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'remark' => 'nullable|string|max:1000'
        ]);

        $milestone->update([
            'status' => 'completed',
            'remark' => $validated['remark'],
            'completion_date' => now()
        ]);

        return back()->with('success', 'Milestone marked as completed.');
    }
}
