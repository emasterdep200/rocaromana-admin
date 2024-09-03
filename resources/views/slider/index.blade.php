@extends('layouts.main')

@section('title')
    {{ __('Slider') }}
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
            <div class="card-content">
                <div class="card-body">
                    {!! Form::open(['url' => route('slider.store'), 'data-parsley-validate', 'files' => true]) !!}
                    <div class="row mandatory mt-1">
                        <div class="col-sm-12 col-md-4 form-group mandatory">

                            {{-- <div class="form-group mandatory row"> --}}
                            {{ Form::label('image', __('Image'), [
                                'class' => 'col-md-12 col-sm-12 form-label text-start',
                            ]) }}

                            {{ Form::file('image', ['class' => 'form-control', 'accept' => 'image/*', 'data-parsley-required' => true]) }}

                        </div>
                        <div class="col-sm-12 col-md-4 form-group mandatory">

                            {{ Form::label('category', __('Category'), [
                                'class' => 'col-md-12 col-sm-12 form-label text-start',
                            ]) }}

                            <select name="category" class="choosen-select form-select form-control-sm" id="categories"
                                required="required">
                                <option value="" selected disabled>Choose Category</option>

                                @if (isset($category))
                                    @foreach ($category as $row)
                                        <option value="{{ $row->id }}">{{ $row->category }} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 form-group mandatory">

                            {{ Form::label('property', __('Property'), [
                                'class' => 'col-md-12 col-sm-12 form-label text-start',
                            ]) }}

                            <select name="property" id="property" class="choosen-select form-select form-control-sm"
                                required="required">

                                <option value="" selected disabled>Choose Property</option>

                            </select>
                        </div>
                        {{-- </div> --}}
                        <div class="col-12 d-flex justify-content-end" style="padding: 1% 2%;">

                            {{ Form::submit(__('Save'), ['class' => 'btn btn-primary me-1 mb-1']) }}
                        </div>

                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="card">
            <form class="form" action="{{ route('slider.slider-order') }}" method="post">
                {{ csrf_field() }}

                <div class="card-content">

                    <div class="row mt-1">
                        <div class="card-body">
                            <div class="form-group row ">

                                <div class="col-12">
                                    <table class="table table-borderless" aria-describedby="mydesc" class='table-striped'
                                        id="table_list" data-toggle="table" data-url="{{ url('sliderList') }}"
                                        data-click-to-select="true" data-side-pagination="server" data-pagination="true"
                                        data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true"
                                        data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                                        data-trim-on-search="false" data-responsive="true" data-sort-name="id"
                                        data-sort-order="desc" data-pagination-successively-size="3"
                                        data-query-params="queryParams" data-id-field="id"
                                        data-editable-emptytext="Default empty text."
                                        data-editable-url="{{ route('slider.slider-order') }}">

                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" data-field="id" data-align="center" data-sortable="true">
                                                    {{ __('ID') }}</th>
                                                <th scope="col" data-field="image" data-align="center"
                                                    data-formatter="imageFormatter" data-sortable="false">
                                                    {{ __('Image') }}</th>
                                                <th scope="col" data-field="category.category" data-sort-name="category"
                                                    data-align="center" data-sortable="false">{{ __('Category') }}</th>
                                                <th scope="col" data-field="title" data-align="center"
                                                    data-sortable="false">{{ __('Property') }}</th>

                                                <th scope="col" data-field="operate" data-align="center"
                                                    data-sortable="false">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
        </div>

        </div>
    </section>
@endsection

@section('script')
    <script>


        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }
        $(function() {
            $("#categories").click(function() {
                console.log("load");
            });
            $("#categories").change(function() {
                console.log('on');
                var id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('slider.getpropertybycategory') }}",
                    dataType: 'json',
                    data: {
                        id: id
                    },

                    success: function(response) {
                        $('#property').empty();

                        if (response.error == false) {
                            $('#property').append($('<option>', {
                                value: '',
                                text: 'Choose option'

                            }));
                            $.each(response.data, function(i, item) {

                                var text_name = item.title + " - " + item.name;
                                $('#property').append($('<option>', {
                                    value: item.id,
                                    text: text_name

                                }));
                            });
                        } else {
                            $('#property').empty();
                        }
                    }
                });

            });
        });
    </script>
@endsection

