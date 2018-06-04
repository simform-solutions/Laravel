@extends('layouts.main')

@section('title') {{ __('Managers') }} @endsection

@section('page_title') {{ __('List Of Managers') }} @endsection

@push('styles')
    {!! Html::style('node_modules/lightbox2/dist/css/lightbox.min.css') !!}
    {!! Html::style('node_modules/adminbsb-materialdesign/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! Html::style('node_modules/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.min.css') !!}
    <style type="text/css">
        .mobile-phone-number-label {
            font-weight: normal;
            color: #aaa;
        }
    </style>
@endpush

@push('scripts')
    {!! Html::script('node_modules/lightbox2/dist/js/lightbox.min.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-validation/jquery.validate.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-datatable/jquery.dataTables.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.min.js') !!}
    {!! Html::tag('script', [
        'var managersListURL = "' . route('admin.managers.anyData') . '"'
    ], ['type' => 'text/javascript']) !!}
    {!! Html::script('js/managers-list.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-inputmask/jquery.inputmask.bundle.js') !!}
    {!! Html::script('js/manager-form.js') !!}
@endpush

@section('page_options')
    <ul class="header-dropdown m-r--5">
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </a>
            <ul class="dropdown-menu pull-right">
                <li>
                    <a href="{{route('admin.managers.create')}}" data-toggle="modal" data-target="#defaultModal">
                        <i class="material-icons">person_add</i> {{ __('New Manager') }}
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
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable" id="managers-list">
                            <thead>
                                <tr>
                                    <th class="width-50"></th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Last Modified On</th>
                                    <th></th>
                                    <th class="width-10"></th>
                                    <th class="width-10"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.modal-default')
@endsection
