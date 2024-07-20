<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Project;
use Auth;

class MessageController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'message' => $request->message,
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Message posted successfully');
    }
}