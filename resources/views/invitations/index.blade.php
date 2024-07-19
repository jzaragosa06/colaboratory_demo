@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Pending Invitations</h1>
        <ul>
            @foreach (Auth::user()->pendingInvitations as $project)
                <li>
                    {{ $project->name }} -
                    <form action="{{ route('projects.acceptInvitation', $project) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Accept Invitation</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
