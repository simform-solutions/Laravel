@extends('layouts.main')

@section('title') {{ __('Restaurants') }} @endsection

@section('page_title') {{ __('List Of Restaurants') }} @endsection

@push('styles')
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('node_modules/lightbox2/dist/css/lightbox.min.css') !!}
    {!! Html::style('node_modules/adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    <style type="text/css">
        .mobile-phone-number-label {
            font-weight: normal;
            color: #aaa;
        }
    </style>
@endpush

@push('scripts')
    {!! Html::script('js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('node_modules/datatables.net/js/jquery.dataTables.min.js') !!}
    {!! Html::script('node_modules/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('node_modules/datatables.net-responsive/js/dataTables.responsive.min.js') !!}
    {!! Html::script('node_modules/datatables.net-responsive-bs/js/responsive.bootstrap.min.js') !!}

    {!! Html::script('node_modules/lightbox2/dist/js/lightbox.min.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-validation/jquery.validate.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-inputmask/jquery.inputmask.bundle.js') !!}
    {!! Html::tag('script', [
        'var managersListURL = "' . route('admin.restaurants.anyData') . '"'
    ], ['type' => 'text/javascript']) !!}
    {!! Html::script('js/restaurants-list.js') !!}
    {!! Html::script('js/restaurant-form.js') !!}
@endpush

@section('page_options')
    <ul class="header-dropdown m-r--5">
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </a>
            <ul class="dropdown-menu pull-right">
                <li>
                    <a href="{{route('admin.restaurants.create')}}" data-toggle="modal" data-target="#defaultModal">
                        <i class="material-icons">add_circle</i> {{ __('New Restaurant') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
@endsection

@section('page_content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <table class="table table-striped table-hover dt-responsive nowrap" id="managers-list" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact Information</th>
                            <th>Manager</th>
                            <th>Price Range</th>
                            <th>Current Status</th>
                            <th>Last Modified On</th>
                            <th></th>
                            <th class="width-10"></th>
                            <th class="width-10"></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.modal-default')
@endsection
