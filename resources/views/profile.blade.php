{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>My Profile</h1>
        <h2>Upload File to My Account</h2>
        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <h2>My Files</h2>
        <ul>
            @foreach (Auth::user()->files as $file)
                <li>
                    <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection --}}


@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>My Profile</h1>
        <h2>Upload File to My Account</h2>
        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <h2>My Files</h2>
        <ul>
            @foreach (Auth::user()->files->whereNull('project_id') as $file)
                <li>
                    <a href="{{ Storage::url($file->path) }}">{{ $file->filename }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
