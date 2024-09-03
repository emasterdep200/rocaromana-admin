@extends('layouts.main')

@section('title')
    App Settings
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

        <form class="form" action="{{ url('app-settings') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    {{-- More Setting Section --}}
                                    <div class="divider pt-3">
                                        <h6 class="divider-text">{{ __('More Settings') }}</h6>
                                    </div>

                                    {{-- IOS Version --}}
                                    <label class="col-sm-2 form-label-mandatory ">{{ __('IOS Version') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="ios_version" type="text" class="form-control" placeholder="App Version" value="{{ system_setting('ios_version') != '' ? system_setting('ios_version') : '' }}" data-parsley-required="true">
                                    </div>

                                    {{-- Android Version --}}
                                    <label class="col-sm-12 form-label-mandatory ">{{ __('Android Version') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="android_version" type="text" class="form-control" placeholder="App Version" value="{{ system_setting('android_version') != '' ? system_setting('android_version') : '' }}" data-parsley-required="true">
                                    </div>

                                    {{-- Force Update --}}
                                    <div class="form-check form-switch">
                                        <label class="form-check-label">{{ __('Force Update') }}</label>
                                        <input type="hidden" name="force_update" id="force_update" value="{{ system_setting('force_update') != '' ? system_setting('force_update') : 0 }}">
                                        <input class="form-check-input" type="checkbox" role="switch" {{ system_setting('force_update') == '1' ? 'checked' : '' }} id="switch_force_update">
                                        <label class="form-check-label mandatory" for="switch_force_update"></label>
                                    </div>

                                    {{-- Maintenance Mode --}}
                                    <div class="col-sm-12 form-check form-switch">
                                        <label class="form-check-label ">{{ __('Maintenance Mode') }}</label>
                                        <input type="hidden" name="maintenance_mode" id="maintenance_mode" value="{{ system_setting('maintenance_mode') != '' ? system_setting('maintenance_mode') : 0 }}">
                                        <input class="form-check-input" type="checkbox" role="switch" {{ system_setting('maintenance_mode') == '1' ? 'checked' : '' }} id="switch_maintenance_mode">
                                        <label class="form-check-label mandatory" for="switch_maintenance_mode"></label>
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
                                    {{-- Light Mode Colors --}}
                                    <div class="col-sm-12">
                                        <div class="divider pt-3">
                                            <h6 class="divider-text">{{ __('Light Mode Colors') }}</h6>
                                        </div>
                                        <div class="row">

                                            {{-- Tertiary Light--}}
                                            <div class="col-md-4">
                                                <label class="form-label ">{{ __('Tertiary') }}</label>
                                                <input name="light_tertiary" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('light_tertiary') != '' ? system_setting('light_tertiary') : '#087C7C' }}" id="systemColor">
                                                <script>
                                                    // Initialize the color picker
                                                    new jscolor('.color', {
                                                        format: 'hex' // Set the format to hexadecimal
                                                    });
                                                </script>
                                            </div>

                                            {{-- Secondary Light--}}
                                            <div class="col-md-4">
                                                <label class="form-label ">{{ __('Secondary') }}</label>
                                                <input name="light_secondary" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('light_secondary') != '' ? system_setting('light_secondary') : '#FFFFFF' }}" id="systemColor">
                                            </div>

                                            {{-- Primary Light--}}
                                            <div class="col-md-4">
                                                <label class="form-label ">{{ __('Primary') }}</label>
                                                <input name="light_primary" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('light_primary') != '' ? system_setting('light_primary') : '#FAFAFA' }}" id="systemColor">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dark Mode Colors --}}
                                    <div class="col-sm-12 mt-1">
                                        <div class="divider pt-3">
                                            <h6 class="divider-text">{{ __('Dark Mode Colors') }}</h6>
                                        </div>

                                        <div class="row">
                                            {{-- Tertiary Dark --}}
                                            <div class="col-md-4">
                                                <label class="form-label ">{{ __('Tertiary') }}</label>
                                                <input name="dark_tertiary" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('dark_tertiary') != '' ? system_setting('dark_tertiary') : '#53ADAE' }}" id="systemColor">
                                            </div>

                                            {{-- Secondary Dark --}}
                                            <div class="col-md-4">
                                                <label class="form-label ">{{ __('Secondary') }}</label>
                                                <input name="dark_secondary" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('dark_secondary') != '' ? system_setting('dark_secondary') : '#1C1C1C' }}" id="systemColor">
                                            </div>

                                            {{-- Primary Dark --}}
                                            <div class="col-md-4">
                                                <label class="form-label ">{{ __('Primary') }}</label>
                                                <input name="dark_primary" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('dark_primary') != '' ? system_setting('dark_primary') : '#0C0C0C' }}" id="systemColor">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Images Section --}}
                        <div class="divider pt-3">
                            <h6 class="divider-text">{{ __('Images') }}</h6>
                        </div>

                        <div class="row">

                            {{-- Splash Screen Logo --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Splash Screen Logo') }}</label>
                                <button class="bottomleft btn btn-primary splash_logo_btn" type="button">+</button>
                                <input accept="image/*" name='splash_logo' type='file' id="splash_logo" style="display: none" />
                                <img id="blah_splash_logo" height="100" width="110" style="margin-left: 5%;background: #f7f7f7" src="{{ url('assets/images/logo/' . (system_setting('splash_logo') ? system_setting('splash_logo') : 'splash.svg')) }}" />
                            </div>

                            {{-- Home Screen Logo --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Home Screen Logo') }}</label>
                                <button class="bottomleft btn btn-primary btn_app_home_screen" type="button">+</button>
                                <input accept="image/*" name='app_home_screen' type='file' id="app_home_screen" style="display: none" />
                                <img id="blah_app_home_screen" height="100" width="110" style="margin-left: 5%;background: #f7f7f7" src="{{ url('assets/images/logo/' . (system_setting('app_home_screen') ? system_setting('app_home_screen') : 'homeLogo.png')) }}" />
                            </div>

                            {{-- Placeholder Image --}}
                            <div class="col-md-4">
                                <label class="form-label ">{{ __('Placeholder Image') }}</label>
                                <button class="bottomleft btn btn-primary btn_placeholder_logo" type="button">+</button>
                                <input accept="image/*" name='placeholder_logo' type='file' id="placeholder_logo" style="display: none" />
                                <img id="blah_placeholder_logo" height="100" width="110" style="margin-left: 5%;background: #f7f7f7" src="{{ url('assets/images/logo/' . (system_setting('placeholder_logo') ? system_setting('placeholder_logo') : 'placeholder.svg')) }}" />
                            </div>

                            <br>
                            &nbsp;&nbsp;({{ __('Only svg files are allowed') }})

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{-- Ads Section --}}
                        <div class="form-check form-switch">
                            <label class="form-check-label">{{ __('Show Ad Mob Ads') }}</label>
                            <input type="hidden" name="show_admob_ads" id="show_admob_ads" value="{{ system_setting('show_admob_ads') != '' ? system_setting('show_admob_ads') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch" {{ system_setting('show_admob_ads') == '1' ? 'checked' : '' }} id="switch_admob_ads">
                            <label class="form-check-label mandatory" for="switch_admob_ads"></label>
                        </div>

                        <div id="admobs">

                            {{-- Banner Ads --}}
                            <div class="divider pt-3">
                                <h6 class="divider-text">{{ __('Banner Ad') }}</h6>

                            </div>

                            <div class="form-group mandatory row mt-3">
                                {{-- Banner Android Ads --}}
                                <div class="col-md-6">
                                    <label class="col-sm-12 form-label-mandatory ">{{ _('Android') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="android_banner_ad_id" type="text" class="form-control" placeholder="Android" id="android_banner_ad_id" value="{{ system_setting('android_banner_ad_id') != '' ? system_setting('android_banner_ad_id') : '' }}" data-parsley-required="true">
                                    </div>
                                </div>
                                {{-- Banner IOS Ads --}}
                                <div class="col-md-6">
                                    <label class="col-sm-12 form-label-mandatory ">{{ __('IOS') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="ios_banner_ad_id" type="text" class="form-control" placeholder="IOS" id="ios_banner_ad_id" value="{{ system_setting('ios_banner_ad_id') != '' ? system_setting('ios_banner_ad_id') : '' }}" data-parsley-required="true">
                                    </div>
                                </div>
                            </div>

                            {{-- Interstitial Ads --}}
                            <div class="divider pt-3">
                                <h6 class="divider-text">{{ __('Interstitial Ad') }}</h6>
                            </div>
                            <div class="form-group mandatory row mt-3">
                                {{-- Interstitial Android Ads --}}
                                <div class="col-md-6">
                                    <label class="col-sm-12 form-label-mandatory ">{{ _('Android') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="android_interstitial_ad_id" type="text" class="form-control" id='android_interstitial_ad_id' placeholder="Android" value="{{ system_setting('android_interstitial_ad_id') != '' ? system_setting('android_interstitial_ad_id') : '' }}">
                                    </div>
                                </div>
                                {{-- Interstitial IOS Ads --}}
                                <div class="col-md-6">
                                    <label class="col-sm-12 form-label-mandatory ">{{ __('IOS') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="ios_interstitial_ad_id" type="text" class="form-control" id="ios_interstitial_ad_id" placeholder="IOS" value="{{ system_setting('ios_interstitial_ad_id') != '' ? system_setting('ios_interstitial_ad_id') : '' }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Native Ads --}}
                            <div class="divider pt-3">
                                <h6 class="divider-text">{{ __('Native Ad') }}</h6>
                            </div>

                            <div class="form-group mandatory row mt-3">
                                {{-- Native Android Ads --}}
                                <div class="col-md-6">
                                    <label class="col-sm-12 form-label-mandatory ">{{ _('Android') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="android_native_ad_id" type="text" class="form-control" id='android_native_ad_id' placeholder="Android" value="{{ system_setting('android_native_ad_id') != '' ? system_setting('android_native_ad_id') : '' }}">
                                    </div>
                                </div>
                                {{-- Native IOS Ads --}}
                                <div class="col-md-6">
                                    <label class="col-sm-12 form-label-mandatory ">{{ __('IOS') }}</label>
                                    <div class="col-sm-12 form-group mandatory">
                                        <input name="ios_native_ad_id" type="text" class="form-control" id="ios_native_ad_id" placeholder="IOS" value="{{ system_setting('ios_native_ad_id') != '' ? system_setting('ios_native_ad_id') : '' }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" name="btnAdd1" value="btnAdd" id="btnAdd1" class="btn btn-primary me-1 mb-1">Save</button>
            </div>

            </div>
        </form>

    </section>
@endsection
@section('script')
    a
    <script>
        $("#switch_maintenance_mode").on('change', function() {
            $("#switch_maintenance_mode").is(':checked') ? $("#maintenance_mode").val(1) : $("#maintenance_mode") .val(0);
        });
        $("#switch_force_update").on('change', function() {
            $("#switch_force_update").is(':checked') ? $("#force_update").val(1) : $("#force_update") .val(0);
        });
        $("#switch_admob_ads").on('change', function() {
            $("#switch_admob_ads").is(':checked') ? $("#show_admob_ads").val(1) : $("#show_admob_ads") .val(0);

            if ($("#switch_admob_ads").is(':checked')) {
                $('#admobs').show();
                $('#android_interstitial_banner_ad_id').attr('data-parsley-required', true);
                $('#ios_interstitial_banner_ad_id').attr('data-parsley-required', true);
                $('#android_banner_ad_id').attr('data-parsley-required', true);
                $('#ios_banner_ad_id').attr('data-parsley-required', true);


            } else {
                $('#admobs').hide();
                $('#android_interstitial_banner_ad_id').attr('data-parsley-required', false);
                $('#ios_interstitial_banner_ad_id').attr('data-parsley-required', false);
                $('#android_banner_ad_id').attr('data-parsley-required', false);
                $('#ios_banner_ad_id').attr('data-parsley-required', false);

            }
        });
        $(document).ready(function() {
            if ($("#switch_admob_ads").is(':checked')) {
                $('#admobs').show();

            } else {
                $('#admobs').hide();

            }
        });




        $('.splash_logo_btn').click(function() {
            $('#splash_logo').click();


        });
        splash_logo.onchange = evt => {
            console.log("click");
            const [file] = splash_logo.files
            console.log(file);
            if (file) {
                blah_splash_logo.src = URL.createObjectURL(file)

            }


        }
        $('.btn_app_home_screen').click(function() {
            $('#app_home_screen').click();


        });
        app_home_screen.onchange = evt => {
            console.log("click");
            const [file] = app_home_screen.files
            console.log(file);
            if (file) {
                blah_app_home_screen.src = URL.createObjectURL(file)

            }


        }



        $('.btn_placeholder_logo').click(function() {
            $('#placeholder_logo').click();


        });
        placeholder_logo.onchange = evt => {
            console.log("click");
            const [file] = placeholder_logo.files
            console.log(file);
            if (file) {
                blah_placeholder_logo.src = URL.createObjectURL(file)

            }


        }
    </script>
@endsection
