@extends('layouts.app')

@section('body_classes') theme-red @endsection

@push('scripts')
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js') !!}
@endpush

@push('styles')
    {!! Html::style('node_modules/adminbsb-materialdesign/css/themes/theme-red.css') !!}
    {!! Html::style('css/main.css') !!}
@endpush

@section('content')
    <!-- Page Loader -->
    {!!
        Html::tag('div', [
            Html::tag('div', [
                Html::tag('div', [
                    Html::tag('div', [
                        Html::tag('div', [
                            Html::tag('div', '', ['class' => 'circle'])
                        ], ['class' => 'circle-clipper left']),
                        Html::tag('div', [
                            Html::tag('div', '', ['class' => 'circle'])
                        ], ['class' => 'circle-clipper right'])
                    ], ['class' => 'spinner-layer pl-red'])
                ], ['class' => 'preloader']),
                Html::tag('p', __('Please wait...'))
            ], ['class' => 'loader'])
        ], ['class' => 'page-loader-wrapper'])
    !!}

    {!! Html::tag('div', '', ['class' => 'overlay']) !!}

    @include('layouts.navigation')

    {!! Form::open(['route' => 'logout', 'class' => 'hidden no-validate', 'id' => 'logout-form']) !!}{!! Form::close() !!}

    {!!
        Html::tag('section', [
            Html::tag('div', [
                Html::tag('div', [
                    Html::tag('h2', [$__env->yieldContent('page_title')], ['class' => 'text-uppercase'])
                ], ['class' => 'block-header']),
                $__env->yieldContent('page_content')
            ], ['class' => 'container-fluid'])
        ], ['class' => 'content'])
    !!}
@endsection