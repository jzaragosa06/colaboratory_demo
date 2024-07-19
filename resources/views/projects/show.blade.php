{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $project->name }}</h1>
        <p>{{ $project->description }}</p>
        <h2>Collaborators</h2>
        <ul>
            @foreach ($project->users as $user)
                <li>{{ $user->name }} ({{ $user->pivot->role }})</li>
            @endforeach
        </ul>
        <form action="{{ route('projects.invite', $project) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Invite by Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Invite</button>
        </form>
    </div>
@endsection --}}


@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $project->name }}</h1>
        <p>{{ $project->description }}</p>
        <h2>Collaborators</h2>
        <ul>
            @foreach ($project->users as $user)
                <li>
                    @if ($user->id == Auth::id())
                        {{ $user->name }} ({{ $user->pivot->role }})
                    @else
                        {{ $user->name }} ({{ $user->pivot->role }}) - {{ $user->pivot->invitation_status }}
                    @endif
                    @if ($user->pivot->invitation_status == 'pending' && $user->id == Auth::id())
                        <form action="{{ route('projects.acceptInvitation', $project) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Accept Invitation</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
        <form action="{{ route('projects.invite', $project) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Invite by Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Invite</button>
        </form>
    </div>
@endsection
