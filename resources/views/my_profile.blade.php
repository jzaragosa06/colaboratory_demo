@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}" class="rounded-circle" alt="Profile Photo" width="150" height="150">
                    </div>
                    <div>
                        <strong>Name:</strong> {{ Auth::user()->name }}
                    </div>
                    <div>
                        <strong>Email:</strong> {{ Auth::user()->email }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
