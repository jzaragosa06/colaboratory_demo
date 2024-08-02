@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $project->name }}</h1>
        <p>{{ $project->description }}</p>
        <h2>Collaborators</h2>
        <ul>
            @foreach ($project->users as $user)
                <li>
                    <img src="{{ Storage::url($user->profile_photo) }}" class="rounded-circle"
                        alt="{{ $user->name }}'s Profile Photo" width="30" height="30">

                    {{ $user->name }} ({{ $user->pivot->role }}) - {{ $user->pivot->invitation_status }}
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

        @php
            $isAccepted = $project->users
                ->where('id', Auth::id())
                ->where('pivot.invitation_status', 'accepted')
                ->isNotEmpty();
        @endphp



        @if ($isAccepted)
            <form action="{{ route('projects.invite', $project) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Invite by Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Invite</button>
            </form>
            <h2>Upload File to Project</h2>
            <form action="{{ route('store_to_project') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="form-group">
                    <label for="file">File</label>
                    <input type="file" name="file" id="file" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            {{-- <h2>Project Files</h2>
            <ul>
                @foreach ($project->files as $file)
                    <li>
                        <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
                        @foreach ($file->associations as $association)
                            <br>Associated JSON: <a
                                href="{{ Storage::url($association->associated_file_path) }}">{{ pathinfo($association->associated_file_path, PATHINFO_BASENAME) }}</a>
                            <br>Modified by: {{ $association->user->name }}
                        @endforeach
                        <form action="{{ route('files.associateJson', $file) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Generate JSON</button>
                        </form>
                    </li>
                @endforeach
            </ul> --}}


            {{-- <h2>Project Files</h2>
            <ul>
                @foreach ($project->files as $file)
                    <li>
                        <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
                        @foreach ($file->associations as $association)
                            <br>Associated JSON: <a
                                href="{{ Storage::url($association->associated_file_path) }}">{{ pathinfo($association->associated_file_path, PATHINFO_BASENAME) }}</a>
                            <br>Modified by: <img src="{{ Storage::url($association->user->profile_photo) }}"
                                class="rounded-circle" alt="{{ $association->user->name }}'s Profile Photo" width="20"
                                height="20"> {{ $association->user->name }}
                        @endforeach
                        <form action="{{ route('files.associateJson', $file) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Generate JSON</button>
                        </form>
                    </li>
                @endforeach
            </ul> --}}

            <h2>Project Files</h2>
            <ul>
                @foreach ($project->files as $file)
                    <li>
                        <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
                        @foreach ($file->associations as $association)
                            <br>Associated JSON: <a
                                href="{{ Storage::url($association->associated_file_path) }}">{{ pathinfo($association->associated_file_path, PATHINFO_BASENAME) }}</a>
                            <br>Modified by: <img src="{{ Storage::url($association->user->profile_photo) }}"
                                class="rounded-circle" alt="{{ $association->user->name }}'s Profile Photo" width="20"
                                height="20"> {{ $association->user->name }}
                        @endforeach
                        @if ($file->is_active)
                            <form action="{{ route('files.associateJson', $file) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Generate JSON</button>
                            </form>
                        @else
                            <form action="{{ route('files.makeActive', $file) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Make Active</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>



            <h2>Messages</h2>
            <form action="{{ route('messages.store', $project) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="message">Leave a Message</label>
                    <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Message</button>
            </form>
            {{-- <ul>
                @foreach ($project->messages as $message)
                    <li>
                        <strong>{{ $message->user->name }}:</strong> {{ $message->message }}
                    </li>
                @endforeach
            </ul> --}}
            <ul>
                @foreach ($project->messages as $message)
                    <li>
                        <img src="{{ Storage::url($message->user->profile_photo) }}" class="rounded-circle"
                            alt="{{ $message->user->name }}'s Profile Photo" width="20" height="20">
                        <strong>{{ $message->user->name }}:</strong> {{ $message->message }}
                        <em>{{ $message->created_at->diffForHumans() }}</em>
                    </li>
                @endforeach
            </ul>
        @endif




    </div>
@endsection
