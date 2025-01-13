<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use Illuminate\Http\Request;

class AcademicianController extends Controller
{
    public function index()
    {
        $academicians = Academician::all();
        return view('academicians.index', compact('academicians'));
    }

    public function create()
    {
        return view('academicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'staff_number' => 'required|string|max:255|unique:academicians',
            'email' => 'required|email|max:255|unique:academicians',
            'college' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        Academician::create($validated);

        return redirect()->route('academicians.index')->with('success', 'Academician added successfully.');
    }

    public function show(Academician $academician)
    {
        $academician->load([
            'ledGrants.members',
            'ledGrants.milestones',
            'memberGrants.leader',
            'memberGrants.members',
            'memberGrants.milestones'
        ]);
        
        return view('academicians.show', compact('academician'));
    }

    public function edit(Academician $academician)
    {
        return view('academicians.edit', compact('academician'));
    }

    public function update(Request $request, Academician $academician)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'staff_number' => 'required|string|max:255|unique:academicians,staff_number,' . $academician->id,
            'email' => 'required|email|max:255|unique:academicians,email,' . $academician->id,
            'college' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        $academician->update($validated);
//message academician has been updated
        return redirect()->route('academicians.show', $academician)
            ->with('success', 'Academician updated successfully.');
    }

    public function destroy(Academician $academician)
    {
        $academician->delete();

        return redirect()->route('academicians.index')->with('success', 'Academician deleted successfully.');
    }
}

