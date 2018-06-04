@extends('layouts.modalView')

@section('form_start')
    {!! Form::model($user, ['id' => 'manager-form', 'route' => !$user->id ? 'admin.managers.store' : ['admin.managers.update', $user->id], 'files' => true, 'class' => 'jquery-validate ajax-form-submit']) !!}
@endsection

@section('modal-title')
    @if (!$user->id)
        {{ __('New Manager') }}
    @else
        {{ __('Edit Manager') }}
        {!! Form::hidden('_method', 'PATCH') !!}
    @endif
@endsection

@section('modal-body')
    <div class="form-group text-center">
        {!! Html::image($user->avatar, 'Avatar', ['id' => 'user_avatar_preview', 'class' => 'img-thumbnail img-circle img_upload', 'style' => 'height: 200px; width: 200px;']) !!}
        {!! Form::file('profile_picture', ['accept' => 'image/*', 'class' => 'hidden', 'id' => 'profile_picture', 'onchange' => "readURL(this, $('#user_avatar_preview'))", 'data-rule-regex' => '^.*\.(jpe?g|png|gif)$', 'data-msg-regex' => 'Image file is invalid.']) !!}
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('first_name', '* First Name', ['class' => 'form-label']) !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => '', 'minlength' => 2, 'maxlength' => 30, 'data-rule-regex' => "^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"]) !!}
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('last_name', '* Last Name', ['class' => 'form-label']) !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => '', 'minlength' => 2, 'maxlength' => 30, 'data-rule-regex' => "^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"]) !!}
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('email', '* E-Mail Address', ['class' => 'form-label']) !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'required' => '', 'data-rule-remote' => route('admin.managers.checkEmail', ['manager' => $user->id]), 'data-msg-remote' => __('Email already exists'), 'maxlength' => 100, 'autocomplete' => 'off']) !!}
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12">
            {!! Form::label('mobile_number', '* Phone Number', ['class' => 'form-label mobile-phone-number-label']) !!}
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-3">
            {!! Form::select('country_code', [], null, ['id' => 'country_code', 'class' => 'form-control show-tick selectpicker', 'data-live-search' => 'true', 'data-size' => 5]) !!}
        </div>
        <div class="col-xs-9">
            <div class="form-group form-float">
                <div class="form-line">
                    {!! Form::number('mobile_number', null, ['autocomplete' => 'off', 'id' => 'mobile_number', 'class' => 'form-control', 'required' => '', 'data-rule-digits' => 'true', 'minlength' => 10, 'maxlength' => 10, 'data-rule-remote' => route('admin.managers.checkMobile', ['manager' => $user->id]), 'data-msg-remote' => __('Phone number already exists'), 'placeholder' => 'Ex: 13XXXXX012']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal-footer')
    {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-link waves-effect']) !!}
    {!! Form::button('CLOSE', ['type' => 'button', 'class' => 'btn btn-link waves-effect', 'data-dismiss' => 'modal']) !!}
@endsection

@section('form_end')
    {!! Form::close() !!}
@endsection