@extends('layouts.app')

@section('body_classes') theme-red @endsection

@push('scripts')
    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('node_modules/adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
@endpush

@push('styles')
    <!-- Red Theme -->
    <link href="{{ asset('node_modules/adminbsb-materialdesign/css/themes/theme-red.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

    @include('layouts.navigation')

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden no-validate">@csrf</form>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2 class="text-uppercase">@yield('page_title')</h2>
            </div>
            @yield('page_content')
        </div>
    </section>
@endsection