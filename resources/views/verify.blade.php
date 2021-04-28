@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if(session('phone'))
                <div class="col-md-6 mb-3">
                    @include('partials.verify_form')
                </div>
                <div class="col-md-6">
                    <div class="smartphone">
                        <div class="content">
                            {{ session("otp") ? "Your validation code is ".session("otp") : "Please try again!" }}
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    @include('partials.verify_form')
                </div>
            @endif
        </div>
    </div>
@endsection
