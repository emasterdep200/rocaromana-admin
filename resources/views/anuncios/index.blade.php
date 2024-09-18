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
                        <table class="table table-borderless" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('anuncios_listing') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                            data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true" data-align="center">ID</th>
                                    <th scope="col" data-field="titulo" data-sortable="true" data-align="center">Titulo</th>
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

                    <form method="post" action="{{ url('anuncio_create') }}" enctype="multipart/form-data">
                    @csrf

                        <!-- Campo Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingresa el título" required>
                        </div>

                        <!-- Campo Imagen -->
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="image" id="imagen" accept="image/*" required>
                        </div>

                        <!-- Campo Link -->
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="url" class="form-control" name="link" id="link" placeholder="Ingresa el link" required>
                        </div>

                        <!-- Campo Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
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


    <!-- Editar Anuncio -->

    <div id="viewEditAnuncio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">EDITAR ANUNCIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="post" action="{{ url('anuncio_update') }}" enctype="multipart/form-data" name="update">
                    @csrf   

                        <input type="hidden" name="id" id="id">

                        <!-- Campo Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingresa el título" required>
                        </div>

                        <!-- Campo Imagen -->
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="image" id="imagen" accept="image/*" required>
                            <p>
                                <img src rel="current_image" heigth="200" width="200" class="rounded m-3">
                            </p>
                        </div>

                        <!-- Campo Link -->
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="url" class="form-control" name="link" id="link" placeholder="Ingresa el link" required>
                        </div>

                        <!-- Campo Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
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

                $("form[name=update] #titulo").val(row.titulo);
                $("form[name=update] #link").val(row.link);
                $("form[name=update] #estado").val(row.estado);
                $("form[name=update] #id").val(row.id);

                $('form[name=update] img[rel=current_image]').attr('src', `https://admin.rocaromana.com/images/publicity/${row.imagen}`);


            }

        }
    </script>
@endsection
