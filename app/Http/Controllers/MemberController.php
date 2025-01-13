<?php

namespace App\Http\Controllers;

use App\Models\ResearchGrant;
use App\Models\Academician;
use App\Models\Milestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MemberController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->academician) {
            return redirect()->route('home')
                ->with('error', 'No academician profile found.');
        }

        // Get only the grants where the user is a member (not leader)
        $memberships = ResearchGrant::whereHas('members', function($query) use ($user) {
            $query->where('academician_id', $user->academician_id);
        })
        ->where('leader_id', '!=', $user->academician_id) // Exclude grants where user is leader
        ->with(['leader', 'milestones'])
        ->get();

        return view('projectmember.index', compact('memberships'));
    }

    public function show(ResearchGrant $grant)
    {
        $user = Auth::user();
        
        // Check if user is a leader
        if (!$grant->members->contains('id', $user->academician_id) || 
            $grant->leader_id === $user->academician_id) {
            return redirect()->route('projectmember.index')
                ->with('error', 'You are not authorized to view this project.');
        }

        // Load relationships
        $grant->load(['leader', 'members', 'milestones']);
        
        return view('projectmember.show', compact('grant'));
    }

} 