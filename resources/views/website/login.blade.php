@extends('layouts.website')

@section('content')
    @push('styles')
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    @endpush

    <div class="login-title">{{ __('messages.login') }}</div>
    @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="input-group">
            <label for="username" class="input-label">{{ __('messages.username') }}</label>
            <input type="text" name="username" id="username" class="input-field" value="{{ old('username') }}" required>
          
        </div>

        <div class="input-group">
            <label for="password" class="input-label">{{ __('messages.password') }}</label>
            <input type="password" name="password" id="password" class="input-field" required>
         
        </div>



        <button type="submit" class="submit-btn">
            {{ __('messages.login') }}
        </button>
    </form>
@endsection
