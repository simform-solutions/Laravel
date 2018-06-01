@extends('layouts.main')

@section('title') {{ __('Managers') }} @endsection

@section('page_title') {{ __('List Of Managers') }} @endsection

@push('styles')
    {!! Html::style('node_modules/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.min.css') !!}
@endpush

@push('scripts')
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-datatable/jquery.dataTables.js') !!}
    {!! Html::script('node_modules/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.min.js') !!}
    <script type="text/javascript">
      var managersListURL = "{!! route('admin.managers.anyData') !!}"
    </script>
    {!! Html::script('js/managers-list.js') !!}
@endpush

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
@endsection
