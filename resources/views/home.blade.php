@extends('layouts.app')
@section('content')
    <div class="container">
        @if($message)
        <div class="alert alert-success text-center" role="alert">
            {{ $message }}
        </div>
        @endif
        <div class="alert alert-success text-center" role="alert">
            We sent you a confirmation SMS message.<br>
            <strong>Welcome to SMSBump!</strong>
        </div>
    </div>
@endsection
