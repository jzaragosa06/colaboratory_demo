@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Time Series Analysis & Forecasting</h1>
                <div class="card">

                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif



                        <div class="container">
                            <h2>Profile</h2>
                            <a href="{{ route('profile.my_profile') }}">My Profile</a><br>
                            <a href="{{ route('invitations.index') }}">Invitations</a><br>
                            <a href="{{ route('profile.uploadedFiles') }}">Files</a><br>
                            <a href="{{ route('profile.results') }}">Results</a>
                        </div>
                        <hr>



                        <div class="container">
                            <h2>Projects</h2>
                            <div class="row">
                                <div class = "col-lg-6">
                                    <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a><br>

                                </div>
                                <div class = "col-lg-6">
                                    <a href="{{ route('projects.index') }}">Project List</a>
                                </div>
                            </div>
                        </div>
                        <hr>


                        <div class="container">
                            <h2>Analysis</h2>
                            <a href="{{ route('analyze.analyze_data') }}">Analyze data</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
