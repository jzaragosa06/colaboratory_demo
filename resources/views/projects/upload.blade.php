@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>{{ $project->name }}</h1>
        <p>{{ $project->description }}</p>

        <h2>Upload File to Project</h2>
        <form action="{{ route('upload_file_to_project') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="form-group">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <select name="type" class="form-control" required>
                    <option value="univariate">Univariate</option>
                    <option value="multivariate">Multivariate</option>
                </select>
            </div>
            <div class="form-group">
                <label for="freq">Frequency:</label>
                <select name="freq" class="form-control" required>
                    <option value="D">Day</option>
                    <option value="W">Week</option>
                    <option value="M">Month</option>
                    <option value="Q">Quarter</option>
                    <option value="Y">Yearly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" name="description" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>




    </div>
@endsection
