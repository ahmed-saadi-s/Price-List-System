@extends('layouts.website')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1>{{ __('messages.welcome') }}</h1>
    <p>{{ __('messages.tagline') }}</p>
    <a href="{{ route('login.form') }}" class="btn-primary">
        {{ __('messages.start_now') }}
    </a>
@endsection
