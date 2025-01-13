<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicianController;
use App\Http\Controllers\ResearchGrantController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ProjectLeaderMiddleware;
use App\Http\Controllers\MemberController;
use App\Http\Middleware\ProjectMemberMiddleware;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin routes
Route::middleware(['web', 'auth', AdminMiddleware::class])->group(function () {
    Route::resource('academicians', AcademicianController::class);
    // Research Grants routes
    Route::prefix('researchgrants')->group(function () {
        Route::get('/', [ResearchGrantController::class, 'index'])->name('researchgrants.index');
        Route::get('/create', [ResearchGrantController::class, 'create'])->name('researchgrants.create');
        Route::post('/', [ResearchGrantController::class, 'store'])->name('researchgrants.store');
        Route::get('/{grant}', [ResearchGrantController::class, 'show'])->name('researchgrants.show');
        Route::get('/{grant}/edit', [ResearchGrantController::class, 'edit'])->name('researchgrants.edit');
        Route::put('/{grant}', [ResearchGrantController::class, 'update'])->name('researchgrants.update');
        Route::delete('/{grant}', [ResearchGrantController::class, 'destroy'])->name('researchgrants.destroy');
    });
    //create milestone for grants routes
    Route::prefix('grants/{grant}/milestones')->group(function () {
        Route::get('/create', [MilestoneController::class, 'create'])
            ->name('grants.milestones.create');
        Route::post('/', [MilestoneController::class, 'store'])
            ->name('grants.milestones.store');
        Route::get('/{milestone}/edit', [MilestoneController::class, 'edit'])
            ->name('grants.milestones.edit');
        Route::put('/{milestone}', [MilestoneController::class, 'update'])
            ->name('grants.milestones.update');
    });
    //edit milestone for grants routes
    Route::prefix('milestones')->group(function () {
        Route::post('/', [MilestoneController::class, 'store'])->name('milestones.store');
        Route::put('/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
        Route::delete('/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');
    });

    // Team member management
    Route::post('grants/{grant}/members', [ResearchGrantController::class, 'addMember'])
        ->name('grants.members.add');
    Route::delete('grants/{grant}/members/{member}', [ResearchGrantController::class, 'removeMember'])
        ->name('grants.members.remove');
});

// Project Leader routes
Route::middleware(['auth', ProjectLeaderMiddleware::class])->group(function () {
    // View lead projects
    Route::get('/my-projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/my-projects/{grant}', [ProjectController::class, 'show'])->name('projects.show');
    
    // Manage project members
    Route::post('/my-projects/{grant}/members', [ProjectController::class, 'addMember'])
        ->name('projects.members.add');
    Route::delete('/my-projects/{grant}/members/{member}', [ProjectController::class, 'removeMember'])
        ->name('projects.members.remove');
    
    // Manage milestones
    Route::get('/my-projects/{grant}/milestones/create', [ProjectController::class, 'createMilestone'])
        ->name('projects.milestones.create');
    Route::post('/my-projects/{grant}/milestones', [ProjectController::class, 'storeMilestone'])
        ->name('projects.milestones.store');
    Route::put('/my-projects/{grant}/milestones/{milestone}', [ProjectController::class, 'updateMilestone'])
        ->name('projects.milestones.update');
    Route::delete('/my-projects/{grant}/milestones/{milestone}', [ProjectController::class, 'destroyMilestone'])
        ->name('projects.milestones.destroy');

    // Milestone completion route
    Route::post('milestones/{milestone}/complete', [MilestoneController::class, 'complete'])
        ->name('milestones.complete');

    // Inside the Project Leader routes group
    Route::get('/my-projects/{grant}/milestones/{milestone}/edit', [ProjectController::class, 'editMilestone'])
        ->name('projects.milestones.edit');
});

// Member routes - only for assigned team members
Route::middleware(['auth'])->group(function () {
    Route::get('/my-memberships', [MemberController::class, 'index'])->name('projectmember.index');
    Route::get('/my-memberships/{grant}', [MemberController::class, 'show'])->name('projectmember.show');
});

// Add this route for unauthorized access
Route::get('/unauthorized', function() {
    return redirect()->route('home')->with('error', 'You are not authorized to access this page.');
})->name('unauthorized');

// Temporary route for debugging - remove in production
Route::get('/debug/check-user/{email}', function($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        return "User exists with ID: " . $user->id;
    }
    return "User not found";
});

// Temporary route for testing - remove in production
Route::get('/debug/create-test-user', function() {
    $user = \App\Models\User::create([
        'name' => 'Test Leader',
        'email' => 'test.leader@example.com',
        'password' => bcrypt('password123'),
        'is_admin' => false
    ]);
    return "Test user created with ID: " . $user->id;
});

// Project member management routes
Route::delete('projects/{grant}/members/{member}', [ProjectController::class, 'removeMember'])
    ->name('projects.removeMember');
Route::post('projects/{grant}/members', [ProjectController::class, 'addMember'])
    ->name('projects.addMember');
Route::post('milestones/{milestone}/complete', [MilestoneController::class, 'complete'])
    ->name('milestones.complete');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Add these routes if they don't exist already
Route::get('/projects/{grant}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::put('/projects/{grant}', [ProjectController::class, 'update'])->name('projects.update');

Route::get('/grants/{grant}/milestones/create', [MilestoneController::class, 'create'])
    ->name('grants.milestones.create');

