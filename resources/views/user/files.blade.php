<!-- resources/views/user/files.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Files</h1>
    <ul>
        @foreach ($files as $file)
        <li>
            <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
            <br>Associated JSONs:
            <ul>
                @foreach ($file->associations as $association)
                <li>
                    <a href="{{ Storage::url($association->associated_file_path) }}">{{ pathinfo($association->associated_file_path, PATHINFO_BASENAME) }}</a>
                    <br>Modified by: {{ $association->user->name }}
                </li>
                @endforeach
            </ul>
            <form action="{{ route('user.files.associateJson', $file) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Generate JSON</button>
            </form>
        </li>
        @endforeach
    </ul>
</div>
@endsection
