@extends('layouts.main')

@section('title')
    {{ __('Web Settings') }}
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

        <form class="form" id="myForm" action="{{ url('web-settings') }}" method="POST" enctype="multipart/form-data"
            data-parsley-validate>
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6 ">
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="divider pt-3">
                                        <h6 class="divider-text">{{ __('Image Settings') }}</h6>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Main Logo') }}</label>
                                            <button class="bottomleft btn btn-primary web_logo_btn"
                                                type="button">+</button>

                                            <input accept="image/*" name='web_logo' type='file' id="web_logo"
                                                style="display: none" />
                                            <img id="blah_web_logo" height="80" width="150"
                                                style="margin-left: 5%;background: #f7f7f7; padding:10px"
                                                src="{{ url('assets/images/logo/' . (system_setting('web_logo') ? system_setting('web_logo') : 'web_logo.png')) }}" />

                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Placeholder Image') }}</label>
                                            <button class="bottomleft btn btn-primary btn_placeholder_logo"
                                                type="button">+</button>

                                            <input accept="image/*" name='web_placeholder_logo' type='file'
                                                id="web_placeholder_logo" style="display: none" />
                                            <img id="blah_placeholder_logo" height="80" width="80"
                                                style="margin-left: 5%;background: #f7f7f7"
                                                src="{{ url('assets/images/logo/' . (system_setting('web_placeholder_logo') ? system_setting('web_placeholder_logo') : 'placeholder.svg')) }}" />

                                        </div>
                                        <div class="col-md-6 mt-5">
                                            <label class="form-label">{{ __('Footer Logo') }}</label>
                                            <button class="bottomleft btn btn-primary web_footer_logo_btn"
                                                type="button">+</button>

                                            <input accept="image/*" name='web_footer_logo' type='file'
                                                id="web_footer_logo" style="display: none" />
                                            <img id="blah_web_footer_logo" height="80" width="150"
                                                style="margin-left: 5%;background: #f7f7f7; padding:10px"
                                                src="{{ url('assets/images/logo/' . (system_setting('web_footer_logo') ? system_setting('web_footer_logo') : 'web_footer_logo.png')) }}" />

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="divider pt-3">
                                        <h6 class="divider-text">{{ __('Iframe Link For Web') }}</h6>
                                    </div>
                                    <div class="form-group mandatory row mt-3">
                                        <label class="form-label-mandatory">{{ __('Link') }}</label>
                                        <textarea id="iframe_tag" class="form-control" rows="4" data-parsley-required="true" placeholder="Iframe Link">{{ system_setting('iframe_link') != '' ? system_setting('iframe_link') : '' }}</textarea>
                                        <input type="hidden" name="iframe_link" id="iframe_link" value="{{ system_setting('iframe_link') != '' ? system_setting('iframe_link') : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="divider pt-3">
                                        <h6 class="divider-text">{{ __('Social Media Links') }}</h6>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group row">

                                            <label class="form-label">{{ __('Facebook Id') }}</label>
                                            <div class="col-sm-12">
                                                <input name="facebook_id" type="text" class="form-control"
                                                    id="facebook_id" placeholder="Facebook Id"
                                                    value="{{ system_setting('facebook_id') != '' ? system_setting('facebook_id') : '' }}">
                                            </div>
                                            <label class="form-label mt-2">{{ __('Instagram Id') }}</label>
                                            <div class="col-sm-12">
                                                <input name="instagram_id" type="text" class="form-control"
                                                    placeholder="Instagram Id"
                                                    value="{{ system_setting('instagram_id') != '' ? system_setting('instagram_id') : '' }}">
                                            </div>

                                        </div>

                                        <div class="row form-group">
                                            <label class=" form-label">{{ __('Twitter Id') }}</label>
                                            <div class="col-sm-12">
                                                <input name="twitter_id" type="text" class="form-control"
                                                    placeholder="Twitter Id"
                                                    value="{{ system_setting('twitter_id') != '' ? system_setting('twitter_id') : '' }}">

                                            </div>
                                            <label class="form-label mt-2">{{ __('Pintrest Id') }}</label>
                                            <div class="col-sm-12">
                                                <input name="pintrest_id" type="text" class="form-control"
                                                    placeholder="Pintrest Id"
                                                    value="{{ system_setting('pintrest_id') != '' ? system_setting('pintrest_id') : '' }}">

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="divider pt-3">
                                                <h6 class="divider-text">{{ __('More Settings') }}</h6>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <div class="col-md-6">
                                                    <label
                                                        class="col-sm-12 form-label ">{{ __('Type Background') }}</label>

                                                    <input name="sell_background" type="color" class="form-control"
                                                        placeholder="System Color"
                                                        value="{{ system_setting('sell_background') != '' ? system_setting('sell_background') : '#e8aa42' }}"
                                                        id="systemColor">

                                                </div>

                                            </div>
                                            <div class="form-group  row mt-2">
                                                <div class="col-md-6">

                                                    <label
                                                        class="col-sm-12 form-label ">{{ __('Facility Background') }}</label>

                                                    <input name="category_background" type="color"
                                                        class="form-control mt-1" placeholder="System Color"
                                                        value="{{ system_setting('category_background') != '' ? system_setting('category_background') : '#087cc7c14' }}"
                                                        id="systemColor">

                                                </div>
                                                <div class="col-md-6">

                                                    <label
                                                        class="col-sm-4 form-check-label mt-3 ">{{ __('Maintenance Mode') }}</label>

                                                    <div class="form-check form-switch ">

                                                        <input type="hidden" name="web_maintenance_mode"
                                                            id="web_maintenance_mode"
                                                            value="{{ system_setting('web_maintenance_mode') != '' ? system_setting('web_maintenance_mode') : 0 }}">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            {{ system_setting('web_maintenance_mode') == '1' ? 'checked' : '' }}
                                                            id="switch_maintenance_mode">
                                                        <label class="form-check-label mandatory"
                                                            for="switch_maintenance_mode"></label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" name="btnAdd1" value="btnAdd" id="btnAdd1"
                    class="btn btn-primary me-1 mb-1">Save</button>
            </div>

            </div>
        </form>

    </section>
@endsection
@section('script')
    <script>
        $("#switch_maintenance_mode").on('change', function() {
            $("#switch_maintenance_mode").is(':checked') ? $("#web_maintenance_mode").val(1) : $("#web_maintenance_mode")
                .val(0);
        });
        $(document).on('click', '#web_logo', function(e) {

            $('.web_logo').hide();

        });
        $(document).on('click', '#web_favicon', function(e) {

            $('.web_favicon').hide();

        });
        $(document).on('click', '#web_placeholder_logo', function(e) {

            $('.web_placeholder_logo').hide();

        });
        $(document).on('click', '#web_footer_logo', function(e) {

            $('.web_footer_logo').hide();

        });





        document.getElementById('myForm').addEventListener('submit', function(event) {


            const iframeContent = document.getElementById('iframe_tag').value;

            // Create a temporary element to extract the src attribute
            const tempElement = document.createElement('div');
            tempElement.innerHTML = iframeContent;

            // Get the src attribute value from the parsed HTML
            const srcValue = tempElement.querySelector('iframe').getAttribute('src');

            if (srcValue != '') {

                // Set the src value to a hidden element
                const hiddenElement = document.getElementById('iframe_link');
                hiddenElement.value = srcValue;
            }
            console.log(srcValue);
            // If you want to set the src as an attribute
            // hiddenElement.setAttribute('src', srcValue);

        });
        $('.web_logo_btn').click(function() {
            $('#web_logo').click();


        });
        web_logo.onchange = evt => {
            console.log("click");
            const [file] = web_logo.files
            console.log(file);
            if (file) {
                blah_web_logo.src = URL.createObjectURL(file)

            }


        }



        $('.btn_placeholder_logo').click(function() {
            $('#web_placeholder_logo').click();


        });
        web_placeholder_logo.onchange = evt => {
            console.log("click");
            const [file] = web_placeholder_logo.files
            console.log(file);
            if (file) {
                blah_placeholder_logo.src = URL.createObjectURL(file)

            }


        }



        $('.web_footer_logo_btn').click(function() {
            $('#web_footer_logo').click();


        });
        web_footer_logo.onchange = evt => {
            console.log("click");
            const [file] = web_footer_logo.files
            console.log(file);
            if (file) {
                blah_web_footer_logo.src = URL.createObjectURL(file)

            }


        }
    </script>
@endsection
