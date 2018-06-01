@extends('layouts.app')

@section('title') {{ __('Sign in') }} @endsection

@section('body_classes') login-page @endsection

@push('scripts')
    <!-- JQuery Validation Js -->
    <script type="text/javascript" src="{{ asset('node_modules/adminbsb-materialdesign/plugins/jquery-validation/jquery.validate.js') }}"></script>
@endpush

@section('content')
    <div class="login-box">
        <div class="logo text-center">
            <div>
                <img src="{{ asset(env('APP_LOGO')) }}" width="40%" class="img-circle img-thumbnail" />
            </div>
            <br />
            <a href="javascript:void(0);">{{ __(env('APP_NAME')) }}</a>
            <small><i>{{ __(env('APP_TAG_LINE')) }}</i></small>
        </div>
        <div class="card">
            <div class="body">
                {!! Form::open(['route' => 'login', 'id' => 'sign_in', 'method' => 'POST', 'class' => 'jquery-validate']) !!}
                    <div class="msg">{{ __('Sign in to your account.') }}</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line focused{{ $errors->has('email') ? ' error' : '' }}">
                            {!! Form::email('email', null, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => __('E-Mail Address'), 'required', 'autofocus', 'autocomplete' => 'off', 'id' => 'email']) !!}
                        </div>
                        @if ($errors->has('email'))
                            {!! Form::label('email', $errors->first('email'), ['class' => 'error', 'id' => 'email-error']) !!}
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line{{ $errors->has('password') ? ' error' : '' }}">
                            {!! Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'placeholder' => __('Password'), 'required', 'id' => 'password']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            {!! Form::checkbox('remember', null, null, ['class' => 'filled-in chk-col-red', 'id' => 'remember']) !!}
                            {!! Form::label('remember', __('Remember Me')) !!}
                        </div>
                        <div class="col-xs-4">
                            {!! Form::button(__('SIGN IN'), ['type' => 'submit', 'class' => 'btn btn-block bg-red waves-effect']) !!}
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-12 align-center">
                            <a href="#">Forgot Password?</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
