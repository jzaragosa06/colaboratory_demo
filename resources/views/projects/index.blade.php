@extends('layouts.app')

@section('content')
    <h1>Time Series Analysis & Forecasting</h1>
    <hr>
    <div class="container">
        <h2>Projects</h2>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a><br>
        <h3>Projects List</h3>
        <ul>
            @foreach ($projects as $project)
                <li>
                    <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                </li>
            @endforeach
        </ul>

    </div>
    <hr>

    <div class="container">
        <h2>Profile</h2>
        <a href="{{ route('my_profile') }}">My Profile</a>
        <a href="{{ route('invitations.index') }}">Invitations</a><br>
        <a href="{{ route('uploadedFiles') }}">Files</a><br>
        <a href="{{ route('results') }}">Results</a>

    </div>
    <hr>
    <div class="container">
        <h2>Analysis</h2>
        <a href="{{ route('analyze.analyze_data') }}">Analyze data</a>
    </div>
@endsection
