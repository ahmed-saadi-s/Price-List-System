@extends('layouts.dashboard') 

@section('content') 
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
    <h1>{{ __('messages.welcome_to_dashboard') }}</h1>
  
@endsection
