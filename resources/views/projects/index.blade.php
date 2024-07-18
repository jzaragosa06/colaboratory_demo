@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Projects</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
        <ul>
            @foreach ($projects as $project)
                <li>
                    <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
