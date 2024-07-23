<!-- resources/views/user/files.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Analye Data </h2>
        <form action="{{ route('store_not_from_project') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Upload from Device</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <hr>


        or Select from previously upload data
        <form action="{{ route('analyze.analyze_data.associateJson') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="file_id">Select File</label>
                <select name="file_id" id="file_id" class="form-control">
                    @foreach ($files as $file)
                        <option value="{{ $file->id }}">{{ $file->filename }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Generate New JSON</button>
        </form>

    </div>
@endsection
