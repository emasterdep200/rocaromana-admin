@extends('layouts.main')

@section('title')
    Anuncios
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

                    <a class="btn btn-primary me-1 mb-1" data-bs-toggle="modal" data-bs-target="#addAnuncio">Agregar Anuncio</a>

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
    <div id="addAnuncio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">DETALLE DEL ANUNCIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form>
                        <!-- Campo Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" placeholder="Ingresa el título" required>
                        </div>

                        <!-- Campo Imagen -->
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" accept="image/*" required>
                        </div>

                        <!-- Campo Link -->
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="url" class="form-control" id="link" placeholder="Ingresa el link" required>
                        </div>

                        <!-- Campo Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" required>
                                <option value="">Selecciona un estado</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <!-- Botón Enviar -->
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>                    
                        
                </div>
            </div>
        </div>
    </div>


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
