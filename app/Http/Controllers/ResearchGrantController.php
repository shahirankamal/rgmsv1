<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use App\Models\ResearchGrant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ResearchGrantController extends Controller
{
    private const DEFAULT_PASSWORD = 'password123';

    public function index()
    {
        $grants = ResearchGrant::with('leader', 'members', 'milestones')->get();
        $grants = ResearchGrant::with('leader')->get();
        return view('researchgrants.index', compact('grants'));
    }

    public function create()
    {
        $academicians = Academician::all();
        return view('researchgrants.create', compact('academicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'grant_amount' => 'required|numeric|min:0',
            'grant_provider' => 'required|string|max:255',
            'start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'leader_id' => 'required|exists:academicians,id',
            'members' => 'array',
            'members.*' => 'exists:academicians,id'
        ]);

        // Create the grant
        $grant = ResearchGrant::create([
            'title' => $validated['title'],
            'grant_amount' => $validated['grant_amount'],
            'grant_provider' => $validated['grant_provider'],
            'start_date' => $validated['start_date'],
            'duration' => $validated['duration'],
            'leader_id' => $validated['leader_id']
        ]);

        $newUsers = [];

        // Create user account for leader if doesn't exist
        $leader = Academician::find($validated['leader_id']);
        if (!User::where('email', $leader->email)->exists()) {
            User::create([
                'name' => $leader->name,
                'email' => $leader->email,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'is_admin' => false,
                'academician_id' => $leader->id
            ]);
            $newUsers[] = $leader->email;
        }

        // Create user accounts for members and attach them to grant
        if (!empty($validated['members'])) {
            foreach ($validated['members'] as $memberId) {
                $member = Academician::find($memberId);
                
                // Create user account if it doesn't exist
                if (!User::where('email', $member->email)->exists()) {
                    User::create([
                        'name' => $member->name,
                        'email' => $member->email,
                        'password' => Hash::make(self::DEFAULT_PASSWORD),
                        'is_admin' => false,
                        'academician_id' => $member->id
                    ]);
                    $newUsers[] = $member->email;
                }

                // Attach member to grant
                $grant->members()->attach($memberId);
            }
        }

        $message = 'Research grant created successfully.';
        if (!empty($newUsers)) {
            $message .= ' New users can login with their email and default password: ' . self::DEFAULT_PASSWORD;
        }

        return redirect()->route('researchgrants.index')
            ->with('success', $message);
    }

    public function show(ResearchGrant $grant)
    {
        $grant->load(['leader', 'members', 'milestones']);
        return view('researchgrants.show', ['grant' => $grant]);
    }

    public function edit(ResearchGrant $grant)
    {
        $academicians = Academician::all();
        $available_members = Academician::whereNotIn('id', 
            $grant->members->pluck('id')->push($grant->leader_id)
        )->get();

        return view('researchgrants.edit', compact('grant', 'academicians', 'available_members'));
    }

    public function update(Request $request, ResearchGrant $grant)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'grant_amount' => 'nullable|numeric',
                'grant_provider' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'duration' => 'nullable|integer',
                'leader_id' => 'nullable|exists:academicians,id',
                'members' => 'nullable|array',
                'members.*' => 'exists:academicians,id'
            ]);

            DB::beginTransaction();

            // Update only provided fields
            $grant->update(array_filter($validated, function($value) {
                return !is_null($value);
            }));

            // Update members if provided
            if (isset($validated['members'])) {
                $grant->members()->sync($validated['members']);
            }

            DB::commit();

            return redirect()->route('researchgrants.index')
                ->with('success', 'Research Grant updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update grant. ' . $e->getMessage()]);
        }
    }

    public function destroy(ResearchGrant $grant)
    {
        $grant->delete();

        return redirect()->route('researchgrants.index')->with('success', 'Research Grant deleted successfully.');
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

        // Add the member using the pivot table (grant_members)
        $grant->members()->attach($validated['member_id']);

        // Create user account if needed
        $member = Academician::find($validated['member_id']);
        if (!User::where('email', $member->email)->exists()) {
            User::create([
                'name' => $member->name,
                'email' => $member->email,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
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
}

