@extends('layouts.modalView')

@section('form_start')
    {!! Form::model($user, ['route' => !$user->id ? 'admin.managers.store' : ['admin.managers.update', $user->id], 'files' => true, 'class' => 'jquery-validate']) !!}
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
        <img id="user_avatar_preview" style="height: 200px; width: 200px;" src="{{$user->avatar}}" class="img-thumbnail img-circle img_upload" />
        <input type="file" class="hidden" accept="image/*" name="profile_picture" id="profile_picture" onchange="readURL(this, $('#user_avatar_preview'))" data-rule-regex="^.*\.(jpe?g|png|gif)$" data-msg-regex="Image file is invalid." />
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('first_name', '* First Name', ['class' => 'form-label']) !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => '', 'minlength' => 2, 'maxlength' => 30]) !!}
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('last_name', '* Last Name', ['class' => 'form-label']) !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => '', 'minlength' => 2, 'maxlength' => 30]) !!}
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('email', '* E-Mail Address', ['class' => 'form-label']) !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'required' => '', 'data-rule-remote' => route('admin.managers.checkEmail', ['manager' => $user->id]), 'data-msg-remote' => 'Email already exists', 'maxlength' => 100, 'autocomplete' => 'off']) !!}
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            {!! Form::label('mobile_number', '* Phone Number', ['class' => 'form-label']) !!}
            {!! Form::text('mobile_number', null, ['class' => 'form-control', 'required' => '', 'data-rule-remote' => route('admin.managers.checkMobile', ['manager' => $user->id]), 'data-msg-remote' => 'Phone number already exists', 'maxlength' => 20, 'minlength' => 10]) !!}
        </div>
    </div>
@endsection

@section('modal-footer')
    {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-link waves-effect']) !!}
    {!! Form::button('CLOSE', ['type' => 'reset', 'class' => 'btn btn-link waves-effect', 'data-dismiss' => 'modal']) !!}
@endsection

@section('form_end')
    {!! Form::close() !!}
@endsection