@extends('layouts.auth')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div align="center">
            <img src="{{ asset('img/logo.png') }}" style="width:60%; height: auto; margin-bottom:1%" alt="">
        </div>
        <div class="card mx-4">
            <div class="card-body p-4">
                <h1>{{ trans('panel.site_title') }}</h1>

                <p class="text-muted">{{ trans('global.reset_password') }}</p>

                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email') }}">

                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button style="background-color:#e35000" type="submit" class="btn btn-primary btn-flat btn-block" style="width: 100%; background-color: #e35000; color: white">
                                Reinicio de contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

