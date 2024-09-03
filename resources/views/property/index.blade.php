@extends('layouts.main')

@section('title')
    {{ __('Property') }}
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">

            </div>
        </div>
    </div>
@endsection

@section('content')

    <section class="section">
        <div class="card">
            @if (has_permissions('create', 'property'))
                <div class="card-header">

                    <div class="row ">
                        <div class="col-12 col-xs-12 d-flex justify-content-end">

                            {!! Form::open(['route' => 'property.create']) !!}
                            {{ method_field('get') }}
                            {{ Form::submit(__('Add Property'), ['class' => 'btn btn-primary']) }}
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            @endif

            <hr>
            <div class="card-body">

                <div class="row " id="toolbar">

                    <div class="col-sm-6">

                        <select class="form-select form-control-sm" id="filter_category">
                            <option value="">{{ __('Select Category') }}</option>
                            @if (isset($category))
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}">{{ $row->category }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">

                        <select id="status" class="form-select form-control-sm">
                            <option value="">{{ __('Select Status') }} </option>
                            <option value="0">{{ __('InActive') }}</option>
                            <option value="1">{{ __('Active') }}</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless" aria-describedby="mydesc" class='table-striped'
                            id="table_list" data-toggle="table" data-url="{{ url('getPropertyList') }}"
                            data-click-to-select="true" data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-search-align="right"
                            data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-trim-on-search="false" data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead class="thead-dark">

                                <tr>
                                    <th scope="col" data-field="id" data-align="center" data-sortable="true">
                                        {{ __('ID') }}</th>
                                    <th scope="col" data-field="added_by" data-align="center" data-sortable="false">
                                        {{ __('Client Name') }}</th>
                                    <th scope="col" data-field="mobile" data-align="center" data-sortable="false">
                                        {{ __('Mobile') }}
                                    </th>
                                    <th scope="col" data-field="client_address" data-align="center"
                                        data-sortable="false">{{ __('Client Address') }}</th>
                                    <th scope="col" data-field="title" data-align="center" data-sortable="false">Title
                                    </th>
                                    <th scope="col" data-field="address" data-align="center" data-sortable="false">
                                        {{ __('Address') }}</th>

                                    <th scope="col" data-field="category.category" data-align="center"
                                        data-sortable="true">
                                        {{ __('Category') }}</th>
                                    <th scope="col" data-field="propery_type" data-formatter="property_type"
                                        data-align="center" data-sortable="true">
                                        {{ __('Type') }}</th>
                                    <th scope="col" data-field="status" data-align="center" data-sortable="false">
                                        {{ __('Status') }}</th>
                                    <th scope="col" data-field="title_image" data-formatter="imageFormatter"
                                        data-align="center" data-sortable="false">
                                        {{ __('Image') }}</th>
                                    <th scope="col" data-field="3d_image" data-formatter="imageFormatter"
                                        data-align="center" data-sortable="false">
                                        {{ __('3D Image') }}</th>
                                    <th scope="col" data-field="interested_users" data-align="center"
                                        data-sortable="false" data-events="actionEvents">
                                        {{ __('Total Interested Users') }}</th>
                                    <th scope="col" data-field="status" data-sortable="false" data-align="center"
                                        data-width="5%" data-formatter="status_switch">
                                        {{ __('Enable/Disable') }}</th>

                                    <th scope="col" data-field="is_premium" data-formatter="premium_status_switch"
                                        data-align="center" data-sortable="false">
                                        {{ __('Private/Public') }}</th>

                                    @if (has_permissions('update', 'property_inquiry'))
                                        <th scope="col" data-field="operate" data-align="center"
                                            data-sortable="false">
                                            {{ __('Action') }}</th>
                                    @endif

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="myModalLabel1">{{ __('Interested Users') }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless" aria-describedby="mydesc" class='table-striped'
                            id="customer_table_list" data-toggle="table" data-url="{{ url('customerList') }}"
                            data-click-to-select="true" data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-columns="true"
                            data-show-refresh="true" data-trim-on-search="false" data-responsive="true"
                            data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="3"
                            data-query-params="customerqueryParams" data-show-export="true"
                            data-export-options='{ "fileName": "data-list-<?= date(' d-m-y') ?>"
                            }'>
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-align="center">
                                        {{ __('ID') }}</th>
                                    <th scope="col" data-field="profile" data-sortable="false" data-align="center">
                                        {{ __('Profile') }}</th>
                                    <th scope="col" data-field="name" data-sortable="true" data-align="center">
                                        {{ __('Name') }}</th>
                                    <th scope="col" data-field="mobile" data-sortable="true" data-align="center">
                                        {{ __('Number') }}</th>
                                    <th scope="col" data-field="email" data-sortable="false" data-align="center">
                                        {{ __('Email') }}</th>

                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

            </div>

        </div>
        <input type="hidden" id="property_id">

    </section>

@endsection

@section('script')
    <script>
        $('#status').on('change', function() {
            $('#table_list').bootstrapTable('refresh');

        });

        $('#filter_category').on('change', function() {
            $('#table_list').bootstrapTable('refresh');

        });


        $(document).ready(function() {
            var params = new window.URLSearchParams(window.location.search);
            if (params.get('status') != 'null') {
                $('#status').val(params.get('status')).trigger('change');
            }
            if (params.get('type') != 'null') {
                $('#type').val(params.get('type'));
            }
        });


        function queryParams(p) {

            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,
                status: $('#status').val(),
                // type: $('#type').val(),
                category: $('#filter_category').val(),
                // customer_id: $('#customerid').val(),
            };
        }

        window.actionEvents = {
            'click .editdata': function(e, value, row, index) {

                $('#property_id').val(row.id);
                $('#customer_table_list').bootstrapTable('refresh');

            }
        }

        function customerqueryParams(p) {

            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,
                property_id: $('#property_id').val(),
            };
        }
    </script>
@endsection
