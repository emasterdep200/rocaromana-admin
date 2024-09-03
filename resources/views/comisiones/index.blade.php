@extends('layouts.main')

@section('title')
    Comisiones
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
            <div class="card-header">
                <div class="col-sm-12 d-flex justify-content-end">


                </div>
            </div>
            <hr>

            <div class="card-body">

                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('list_comisiones') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                            data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-align="center">ID</th>
                                    <th scope="col" data-field="asesor" data-sortable="true" data-align="center">Asesor</th>
                                    <th scope="col" data-field="comision" data-sortable="true" data-align="center">Comision</th>
                                    <th scope="col" data-field="estado" data-sortable="false" data-align="center">Estado</th>
                                    <th scope="col" data-field="operate" data-sortable="false"
                                        data-events="actionEvents" data-align="center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </section>



@endsection


@section('script')
    <script src="{{ url('assets/js/custom/users/users.js') }}"></script>
        <script>
        window.actionEvents = {
            'click .editdata': function(e, value, row, index) {


                $('#edit_name').val(row.name);
                $('#edit_email').val(row.email);
                $('#edit_id').val(row.id);
                $.each(row.permissions, function(index, value) {
                    // console.log(index);
                    $.each(value, function(key, value) {
                        el = document.getElementsByName('Editpermissions[' + index + '][' + key +
                            ']')[
                            0];
                        if (el) {

                            el.setAttribute('checked', true);
                        }
                    });
                });


            }

        }
    </script>
@endsection
