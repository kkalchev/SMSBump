<div class="card">
    <div class="card-header">{{ __('Verify Your Phone Number') }}</div>
    <div class="card-body">
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{session('error')}}
            </div>
        @endif
        Please enter the verification code sent to your number: {{session('phone')}}
        <form action="{{route('verify')}}" method="post">
            @csrf
            <div class="form-group row">
                <label for="verification_code"
                       class="col-md-4 col-form-label text-md-right">{{ __('Verification code') }}</label>
                <div class="col-md-6">
                    <input type="hidden" name="phone" value="{{session('phone')}}">
                    <input id="verification_code" type="tel"
                           class="form-control @error('verification_code') is-invalid @enderror"
                           name="verification_code" value="{{ old('verification_code') }}" required>
                    @error('verification_code')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Verify Phone Number') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
