@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Uploaded Files</h2>
        <ul>
            @foreach (Auth::user()->files->whereNull('project_id') as $file)
                <li>
                    <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
                    {{-- @if ($file->associated_file_path)
                        <br>Associated JSON: <a
                            href="{{ Storage::url($file->associated_file_path) }}">{{ pathinfo($file->associated_file_path, PATHINFO_BASENAME) }}</a>
                    @endif --}}
                </li>
            @endforeach
        </ul>
    </div>
@endsection
