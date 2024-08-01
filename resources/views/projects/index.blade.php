@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Projects List</h2>
        <ul>
            @foreach ($projects as $project)
                <li>
                    <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                </li>
            @endforeach
        </ul>

    </div>
@endsection
