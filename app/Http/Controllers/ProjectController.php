<?php

// app/Http/Controllers/ProjectController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects;
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id()
        ]);
        $project->users()->attach(Auth::id(), ['role' => 'owner']);
        return redirect()->route('projects.index');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    // public function invite(Request $request, Project $project)
    // {
    //     $user = User::where('email', $request->email)->first();
    //     if ($user) {
    //         $project->users()->attach($user->id, ['role' => 'collaborator']);
    //         return redirect()->route('projects.show', $project);
    //     }
    //     return redirect()->route('projects.show', $project)->with('error', 'User not found');
    // }



    public function invite(Request $request, Project $project)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $project->users()->attach($user->id, ['role' => 'collaborator', 'invitation_status' => 'pending']);
            return redirect()->route('projects.show', $project);
        }
        return redirect()->route('projects.show', $project)->with('error', 'User not found');
    }

    public function acceptInvitation(Project $project)
    {
        $user = Auth::user();
        $project->users()->updateExistingPivot($user->id, ['invitation_status' => 'accepted']);
        return redirect()->route('projects.index')->with('success', 'Invitation accepted');
    }



}
