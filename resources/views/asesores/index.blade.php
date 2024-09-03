@extends('layouts.main')

@section('title')
    Asesores
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

                    <a class="btn btn-primary me-1 mb-1" data-bs-toggle="modal" data-bs-target="#addAsesoreditModal">Agregar Asesor</a>

                </div>
            </div>
            <hr>

            <div class="card-body">

                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('asesores_listing') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                            data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-align="center">ID</th>
                                    <th scope="col" data-field="nombre" data-sortable="true" data-align="center">Name</th>
                                    <th scope="col" data-field="email" data-sortable="true" data-align="center">Email</th>
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



    <!-- EDIT USER MODEL MODEL -->
    <div id="viewDetailAsesor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">DETALLE ASESOR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">Nombre                  <span class="float-right" id="nombre"></span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">Cedula                  <span class="float-right" id="cedula"></span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">Correo electronico      <span class="float-right" id="email"></span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">Telefono                <span class="float-right" id="telefono"></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="divider pt-3">
                        <h6 class="divider-text">Metricas</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-center">Presupuesto</h6>
                            <div id="presupuesto"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                        
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- EDIT USER MODEL -->


@endsection

@section('script')
    <script src="{{ url('assets/js/custom/users/users.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        window.actionEvents = {
            'click .editdata': function(e, value, row, index) {

                console.log(row);

                $("#nombre").text(row.nombres+' '+row.apellidos);
                $("#email").text(row.email);
                $("#cedula").text(row.cedula);
                $("#telefono").text(row.celular);


                var options = {
                  series: [row.presupuesto],
                    chart: {
                      type: 'radialBar',
                      offsetY: -20,
                      sparkline: {
                        enabled: true
                      }
                    },
                    plotOptions: {
                      radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                          background: "#d4b516",
                          strokeWidth: '97%',
                          margin: 5, // margin is in pixels
                          dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            color: '#d4b516',
                            opacity: 1,
                            blur: 2
                          }
                        },
                        dataLabels: {
                          name: {
                            show: false
                          },
                          value: {
                            offsetY: -6,
                            fontSize: '22px'
                          }
                        }
                      }
                    },
                    grid: {
                      padding: {
                        top: -10
                      }
                    },
                    fill: {
                      type: 'gradient',
                      gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.4,
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91]
                      },
                    },
                    labels: ['Presupuesto'],
                };

                var chart = new ApexCharts(document.querySelector("#presupuesto"), options);
                chart.render();
      


            }

        }
    </script>
@endsection
