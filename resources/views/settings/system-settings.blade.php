@extends('layouts.main')

@section('title')
    {{ __('System Settings') }}
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
        <form class="form" id="myForm" action="{{ url('set_settings') }}" data-parsley-validate method="POST" id="setting_form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-12">
                    <div class="card" style="height: 95%">

                        <div class="card-body">
                            <div class="divider pt-3">
                                <h6 class="divider-text">{{ __('Company Details') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    {{-- Company Name --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="form-label center">{{ __('Company Name') }}</label>
                                        <input name="company_name" type="text" class="form-control" id="company_name" placeholder="Company Name" value="{{ system_setting('company_name') != '' ? system_setting('company_name') : 'eBroker' }}">
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="form-label">{{ __('Email') }}</label>
                                        <input name="company_email" type="email" class="form-control" placeholder="Email" value="{{ system_setting('company_email') != '' ? system_setting('company_email') : '' }}">
                                    </div>

                                    {{-- Contact Number 1 --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="form-label">{{ __('Contact Number 1') }}</label>
                                        <input name="company_tel1" type="text" class="form-control" placeholder="Contact Number 1" value="{{ system_setting('company_tel1') != '' ? system_setting('company_tel1') : '' }}">
                                    </div>

                                    {{-- Contact Number 2 --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="form-label mt-1">{{ __('Contact Number 2') }}</label>
                                        <input name="company_tel2" type="text" class="form-control" placeholder="Contact Number 2" value="{{ system_setting('company_tel2') != '' ? system_setting('company_tel2') : '' }}">
                                    </div>

                                    {{-- Latitude --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="form-label">{{ __('Latitude') }}</label>
                                        <input name="latitude" type="text" class="form-control" placeholder="Latitude" value="{{ system_setting('latitude') != '' ? system_setting('latitude') : '' }}">
                                    </div>

                                    {{-- Longitude --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="form-label mt-1">{{ __('Longitude') }}</label>
                                        <input name="longitude" type="text" class="form-control" placeholder="Longitude" value="{{ system_setting('longitude') != '' ? system_setting('longitude') : '' }}">
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <label class="form-label-mandatory">{{ __('Company Address') }}</label>
                                    <div class="col-sm-12">
                                        <textarea name="company_address" class="form-control" id="" rows="3">{{ system_setting('company_address') != '' ? system_setting('company_address') : '' }}</textarea>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="divider pt-3">
                                <h6 class="divider-text">{{ __('More Settings') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group row mandatory">

                                    {{-- Default Language --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label-mandatory  mt-1">{{ __('Default Language') }}</label>
                                        <select name="default_language" id="default_language" class="choosen-select form-select form-control-sm">
                                            @foreach ($languages as $row)
                                                {{ $row }}
                                                <option value="{{ $row->code }}"
                                                    {{ system_setting('default_language') == $row->code ? 'selected' : '' }}>
                                                    {{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Currency Symbol --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label-mandatory  mt-3">{{ __('Currency Symbol') }}</label>
                                        <input name="currency_symbol" type="text" class="form-control" placeholder="Currency Symbol" value="{{ system_setting('currency_symbol') != '' ? system_setting('currency_symbol') : '' }}" data-parsley-required="true" maxlength="3">
                                    </div>

                                    {{-- Unsplash API Key --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label  mt-3">{{ __('Unsplash API Key') }}</label>
                                        <input name="unsplash_api_key" type="text" class="form-control" placeholder="Unsplash API Key" value="{{ system_setting('unsplash_api_key') != '' ? system_setting('unsplash_api_key') : '' }}">
                                    </div>

                                    {{-- Place API Key --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label  mt-3">{{ __('Place API Key') }}</label>
                                        <input name="place_api_key" type="text" class="form-control" placeholder="Place API Key" value="{{ system_setting('place_api_key') != '' ? system_setting('place_api_key') : '' }}">
                                    </div>

                                    {{-- Number With Suffix --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-4 form-check-label mandatory mt-3" for="switch_number_with_suffix">{{ __('Number With Suffix') }}</label>
                                        <div class="col-sm-1 col-md-1 mt-3 col-xs-12 ">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="number_with_suffix" id="number_with_suffix" value="{{ system_setting('number_with_suffix') != '' ? system_setting('number_with_suffix') : 0 }}">
                                                <input class="form-check-input" type="checkbox" role="switch" {{ system_setting('number_with_suffix') == '1' ? 'checked' : '' }} id="switch_number_with_suffix">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Change Icon Colors to theme Color --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-5 form-check-label mt-3" for="switch_svg_clr">{{ __('Change Icon Colors to theme Color?') }}</label>
                                        <div class="col-sm-1 mt-3">
                                            <div class="form-check form-switch ">
                                                <input type="hidden" name="svg_clr" id="svg_clr" value="{{ system_setting('svg_clr') != '' ? system_setting('svg_clr') : 0 }}">
                                                <input class="form-check-input" type="checkbox" role="switch" {{ system_setting('svg_clr') == '1' ? 'checked' : '' }} id="switch_svg_clr">
                                                <label class="form-check-label mandatory" for="switch_svg_clr"></label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Playstore App link --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label">{{ __('Playstore Id') }}</label>
                                        <input name="playstore_id" type="text" class="form-control" placeholder="Playstore Id" value="{{ system_setting('playstore_id') != '' ? system_setting('playstore_id') : '' }}">
                                    </div>

                                    {{-- Appstore App link --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label">{{ __('Appstore Id') }}</label>
                                        <input name="appstore_id" type="text" class="form-control" placeholder="Appstore Id" value="{{ system_setting('appstore_id') != '' ? system_setting('appstore_id') : '' }}">
                                    </div>

                                    {{-- System Color --}}
                                    <div class="col-sm-12 col-md-6 mt-2">
                                        <label class="col-sm-12 form-label mt-3">{{ __('System Color') }}</label>
                                        <input name="system_color" type="color" class="form-control" placeholder="System Color" value="{{ system_setting('system_color') != '' ? system_setting('system_color') : '#087C7C' }}" id="systemColor">
                                        <input type="hidden" id="hiddenRGBA" name="rgb_color">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    {{-- Paypal Settings --}}
                    <div class="divider pt-3 mt-3">
                        <h6 class="divider-text">{{ __('Paypal Setting') }}</h6>
                    </div>
                    <div class="form-group row">

                        {{-- Paypal Business ID --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Paypal Business ID') }}</label>
                            <input name="paypal_business_id" type="text" class="form-control" placeholder="Paypal Business ID" value="{{ system_setting('paypal_business_id') != '' ? system_setting('paypal_business_id') : '' }}">
                        </div>

                        {{-- Paypal Webhook URL --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Paypal Webhook URL') }}</label>
                            <input name="paypal_webhook_url" type="text" class="form-control" placeholder="Paypal Webhook URL" value="{{ system_setting('paypal_webhook_url') != '' ? system_setting('paypal_webhook_url') : url('/webhook/paypal') }}" readonly>
                        </div>

                        {{-- Sandbox Mode --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="col-sm-2 form-label mt-3 ">{{ __('Sandbox Mode') }}</label>
                            <div class="col-sm-2 col-md-4 col-xs-12 mt-3 ">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="sandbox_mode" id="sandbox_mode" value="{{ system_setting('sandbox_mode') != '' ? system_setting('sandbox_mode') : 0 }}">
                                    <input class="form-check-input" type="checkbox" role="switch" {{ system_setting('sandbox_mode') == '1' ? 'checked' : '' }} id="switch_sandbox_mode">
                                    <label class="form-check-label" for="switch_sandbox_mode"></label>
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="col-sm-2 form-check-label  mt-3 " id='lbl_paypal'>{{ system_setting('paypal_gateway') != '' ? (system_setting('paypal_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                            <div class="col-sm-2 col-md-4 col-xs-12 mt-3 ">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="paypal_gateway" id="paypal_gateway" value="{{ system_setting('paypal_gateway') != '' ? system_setting('paypal_gateway') : 0 }}">
                                    <input class="form-check-input" type="checkbox" role="switch" class="switch-input" name='op' {{ system_setting('paypal_gateway') == '1' ? 'checked' : '' }} id="switch_paypal_gateway">
                                    <label class="form-check-label" for="switch_paypal_gateway"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payu Settings --}}
                    <div class="divider pt-3 mt-3">
                        <h6 class="divider-text">{{ __('Payu Setting') }}</h6>
                    </div>
                    <div class="form-group row">

                        {{-- Payu ID --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Payu Merchant ID') }}</label>
                            <input name="payu_merchant_id" type="text" class="form-control"  value="{{ system_setting('payu_merchant_id') != '' ? system_setting('payu_merchant_id') : '' }}">
                        </div>

                       {{-- Payu key --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Payu Api key') }}</label>
                            <input name="payu_api_key" type="text" class="form-control"  value="{{ system_setting('payu_api_key') != '' ? system_setting('payu_api_key') : '' }}">
                        </div>


                        {{-- Paypal Webhook URL --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Payu Confirm URL') }}</label>
                            <input name="payu_confirm_url" type="text" class="form-control"  value="{{ system_setting('payu_confirm_url') != '' ? system_setting('payu_confirm_url') : url('/webhook/payu') }}" readonly>
                        </div>


                    </div>

                    {{-- Razorpay Setting --}}
                    <div class="divider pt-3 mt-3">
                        <h6 class="divider-text">{{ __('Razorpay Setting') }}</h6>
                    </div>

                    <div class="form-group row">

                        {{-- Razorpay key --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Razorpay key') }}</label>
                            <input name="razor_key" type="text" class="form-control" placeholder="Razorpay Key" value="{{ system_setting('razor_key') != '' ? system_setting('razor_key') : '' }}">
                        </div>

                        {{-- Razorpay Webhook URL --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Razorpay Webhook URL') }}</label>
                            <input name="razorpay_webhook_url" type="text" class="form-control" placeholder="Razorpay Webhook URL" value="{{ system_setting('razorpay_webhook_url') != '' ? system_setting('razorpay_webhook_url') : url('/webhook/razorpay') }}" readonly>
                        </div>

                        {{-- Razorpay Secret --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Razorpay Secret') }}</label>
                            <input name="razor_secret" type="text" class="form-control" placeholder="Razorpay Secret" value="{{ system_setting('razor_secret') != '' ? system_setting('razor_secret') : '' }}">
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="col-sm-2 form-label  mt-3" id='lbl_razorpay'>{{ system_setting('razorpay_gateway') != '' ? (system_setting('razorpay_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                            <div class="col-sm-2 col-md-4 col-xs-12  mt-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="razorpay_gateway" id="razorpay_gateway" value="{{ system_setting('razorpay_gateway') != '' ? system_setting('razorpay_gateway') : 0 }}">
                                    <input class="form-check-input" type="checkbox" role="switch" class="switch-input" name='op' {{ system_setting('razorpay_gateway') == '1' ? 'checked' : '' }} id="switch_razorpay_gateway">
                                    <label class="form-check-label" for="switch_razorpay_gateway"></label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Paystack Setting --}}
                    <div class="divider pt-3 mt-3">
                        <h6 class="divider-text">{{ __('Paystack Setting') }}</h6>
                    </div>

                    <div class="form-group row">

                        {{-- Paystack Secret key --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Paystack Secret key') }}</label>
                            <input name="paystack_secret_key" type="text" class="form-control" placeholder="Paystack Secret Key" value="{{ system_setting('paystack_secret_key') != '' ? system_setting('paystack_secret_key') : '' }}">
                        </div>

                        {{-- Paystack Webhook URL --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Paystack Webhook URL') }}</label>
                            <input name="paystack_webhook_url" type="text" class="form-control" placeholder="Paystack Webhook URL" value="{{ system_setting('paystack_webhook_url') != '' ? system_setting('paystack_webhook_url') : url('/webhook/paystack') }}" readonly>
                        </div>

                        {{-- Paystack Currency Symbol --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Paystack Currency Symbol') }}</label>
                            <select name="paystack_currency" id="paystack_currency" class="choosen-select form-select form-control-sm">
                                <option value="GHS" {{ system_setting('paystack_currency') == 'GHS' ? 'selected' : '' }}> GHS</option>
                                <option value="NGN" {{ system_setting('paystack_currency') == 'NGN' ? 'selected' : '' }}> NGN</option>
                                <option value="USD" {{ system_setting('paystack_currency') == 'USD' ? 'selected' : '' }}> USD</option>
                                <option value="ZAR" {{ system_setting('paystack_currency') == 'ZAR' ? 'selected' : '' }}> ZAR</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="col-sm-2 form-check-label  mt-3" id='lbl_paystack'>{{ system_setting('paystack_gateway') != '' ? (system_setting('paystack_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                            <div class="col-sm-2 col-md-4 col-xs-12  mt-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="paystack_gateway" id="paystack_gateway" value="{{ system_setting('paystack_gateway') != '' ? system_setting('paystack_gateway') : 0 }}">
                                    <input class="form-check-input" type="checkbox" role="switch" class="switch-input" name='op' {{ system_setting('paystack_gateway') == '1' ? 'checked' : '' }} id="switch_paystack_gateway">
                                </div>
                            </div>
                        </div>

                        {{-- Paystack Public key --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Paystack Public key') }}</label>
                            <input name="paystack_public_key" type="text" class="form-control" placeholder="Paystack Public Key" value="{{ system_setting('paystack_public_key') != '' ? system_setting('paystack_public_key') : '' }}">
                        </div>

                    </div>

                    {{-- Stripe Setting --}}
                    <div class="divider pt-3 mt-3">
                        <h6 class="divider-text">{{ __('Stripe Setting') }}</h6>
                    </div>

                    <div class="form-group row">
                        {{-- Stripe publishable key --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Stripe publishable key') }}</label>
                            <input name="stripe_publishable_key" type="text" class="form-control" placeholder="Stripe publishable  Key" value="{{ system_setting('stripe_publishable_key') != '' ? system_setting('stripe_publishable_key') : '' }}">
                        </div>

                        {{-- Stripe Webhook URL --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Stripe Webhook URL') }}</label>
                            <input name="stripe_webhook_url" type="text" class="form-control" placeholder="Stripe Webhook URL" value="{{ system_setting('stripe_webhook_url') != '' ? system_setting('stripe_webhook_url') : url('/webhook/stripe') }}" readonly>
                        </div>

                        {{-- Stripe Currency Symbol --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-check-label">{{ __('Stripe Currency Symbol') }}</label>
                            <select name="stripe_currency" id="stripe_currency" class="choosen-select form-select form-control-sm">
                                @foreach ($stripe_currencies as $value)
                                <option value={{ $value }}
                                {{ system_setting('stripe_currency') == $value ? 'selected' : '' }}>
                                {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="col-sm-2 form-check-label  mt-3" id='lbl_stripe'>{{ system_setting('stripe_gateway') != '' ? (system_setting('stripe_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                            <div class="col-sm-2 col-md-4 col-xs-12  mt-3">
                                <div class="form-check form-switch ">
                                    <input type="hidden" name="stripe_gateway" id="stripe_gateway" value="{{ system_setting('stripe_gateway') != '' ? system_setting('stripe_gateway') : 0 }}">
                                    <input class="form-check-input" type="checkbox" role="switch" class="switch-input" name='op' {{ system_setting('stripe_gateway') == '1' ? 'checked' : '' }} id="switch_stripe_gateway">
                                </div>
                            </div>
                        </div>

                        {{-- Stripe Secret key --}}
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-check-label-mandatory">{{ __('Stripe Secret key') }}</label>
                            <input name="stripe_secret_key" type="text" class="form-control" placeholder="Stripe Secret Key" value="{{ system_setting('stripe_secret_key') != '' ? system_setting('stripe_secret_key') : '' }}">
                        </div>

                    </div>


                    {{-- Stripe Setting --}}
                    <div class="divider pt-3 mt-3">
                        <h6 class="divider-text">{{ __('Asesores') }}</h6>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Comision Asesores') }}</label>
                            <input name="comision_asesores" type="text" class="form-control"  value="{{ system_setting('comision_asesores') }}">
                        </div>
                        <div class="col-sm-12 col-md-6 mt-2">
                            <label class="form-label">{{ __('Presupuesto de ventas asesores') }}</label>
                            <input name="meta_asesores" type="text" class="form-control"  value="{{ system_setting('meta_asesores') }}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="divider pt-3">
                            <h6 class="divider-text">{{ __('Images') }}</h6>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Favicon Icon') }}</label>
                                <button class="bottomleft btn btn-primary fav_icon_btn" type="button">+</button>

                                <input accept="image/*" name='favicon_icon' type='file' id="fav_image"
                                    style="display: none" />
                                <img id="blah_fav" height="100" width="110"
                                    style="margin-left: 5%;background: #f7f7f7"
                                    src="{{ url('assets/images/logo/favicon.png') }}" />

                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Comapany Logo') }}</label>
                                <button class="bottomleft btn btn-primary btn_comapany_logo" type="button">+</button>

                                <input accept="image/*" name='company_logo' type='file' id="company_logo"
                                    style="display: none" />
                                <img id="blah_comapany_logo"
                                    style="margin: 5%;background: #f7f7f7;height:70px;width:200px;"
                                    src="{{ url('assets/images/logo/' . (system_setting('company_logo') ? system_setting('company_logo') : 'logo.png')) }}" />

                            </div>
                            <div class="col-md-4">

                                <label class="form-label ">{{ __('Login Page Image') }}</label>

                                <button class="bottomleft btn btn-primary btn_login_image" type="button">+</button>

                                <input accept="image/*" name='login_image' type='file' id="login_image"
                                    style="display: none" />
                                <img id="blah_login_image" height="100" width="110"
                                    style="margin-left: 5%;background: #f7f7f7"
                                    src="{{ url('assets/images/bg/' . (system_setting('login_image') ? system_setting('login_image') : 'Login_BG.jpg')) }}" />

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" name="btnAdd" value="btnAdd"
                    class="btn btn-primary me-1 mb-1">{{ __('Save') }}</button>
            </div>

            </div>

        </form>

    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on('click', '#favicon_icon', function(e) {

            $('.favicon_icon').hide();

        });
        $(document).on('click', '#company_logo', function(e) {

            $('.company_logo').hide();

        });
        $(document).on('click', '#login_image', function(e) {

            $('.login_image').hide();

        });




        const checkboxes = document.querySelectorAll('input[type=checkbox][role=switch][name=op]', );
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', (event) => {
                if (event.target.checked) {
                    checkboxes.forEach((checkbox) => {
                        if (checkbox !== event.target) {
                            checkbox.checked = false;

                            $("#switch_paypal_gateway").is(':checked') ? $("#paypal_gateway").val(
                                    1) : $("#paypal_gateway")
                                .val(0);

                            $("#switch_paypal_gateway").is(':checked') ? $("#lbl_paypal").text(
                                    "Enable") : $("#lbl_paypal")
                                .text("Disable");

                            $("#switch_razorpay_gateway").is(':checked') ? $("#razorpay_gateway")
                                .val(1) : $("#razorpay_gateway")
                                .val(0);
                            $("#switch_razorpay_gateway").is(':checked') ? $("#lbl_razorpay").text(
                                    "Enable") : $("#lbl_razorpay")
                                .text("Disable");

                            $("#switch_paystack_gateway").is(':checked') ? $("#lbl_paystack").text(
                                    "Enable") : $("#lbl_paystack")
                                .text("Disable");
                            $("#switch_paystack_gateway").is(':checked') ? $("#paystack_gateway")
                                .val(1) : $("#paystack_gateway")
                                .val(0);



                            $("#switch_stripe_gateway").is(':checked') ? $("#lbl_stripe").text(
                                    "Enable") : $("#lbl_stripe")
                                .text("Disable");
                            $("#switch_stripe_gateway").is(':checked') ? $("#stripe_gateway")
                                .val(1) : $("#stripe_gateway")
                                .val(0);

                        }
                    });
                }
            });
        });



        // $("#switch_seo_settings").on('change', function() {
        //     $("#switch_seo_settings").is(':checked') ? $("#seo_settings").val(1) : $("#seo_settings")
        //         .val(0);
        // });

        $("#switch_svg_clr").on('change', function() {
            $("#switch_svg_clr").is(':checked') ? $("#svg_clr").val(1) : $("#svg_clr")
                .val(0);
        });


        $("#switch_force_update").on('change', function() {
            $("#switch_force_update").is(':checked') ? $("#force_update").val(1) : $("#force_update")
                .val(0);
        });
        $("#switch_number_with_suffix").on('change', function() {
            $("#switch_number_with_suffix").is(':checked') ? $("#number_with_suffix").val(1) : $(
                    "#number_with_suffix")
                .val(0);
        });
        $("#switch_sandbox_mode").on('change', function() {
            $("#switch_sandbox_mode").is(':checked') ? $("#sandbox_mode").val(1) : $("#sandbox_mode")
                .val(0);
        });
        $("#switch_paypal_gateway").on('change', function() {

            $("#switch_paypal_gateway").is(':checked') ? $("#paypal_gateway").val(1) : $("#paypal_gateway")
                .val(0);

            $("#switch_paypal_gateway").is(':checked') ? $("#lbl_paypal").text("Disable") : $("#lbl_paypal")
                .text("Enable");
        });
        $("#switch_razorpay_gateway").on('change', function() {

            $("#switch_razorpay_gateway").is(':checked') ? $("#razorpay_gateway").val(1) : $("#razorpay_gateway")
                .val(0);

            $("#switch_razorpay_gateway").is(':checked') ? $("#lbl_razorpay").text("Disable") : $("#lbl_razorpay")
                .text("Enable");
        });




        $("#switch_stripe_gateway").on('change', function() {

            $("#switch_stripe_gateway").is(':checked') ? $("#stripe_gateway").val(1) : $("#stripe_gateway")
                .val(0);

            $("#switch_stripe_gateway").is(':checked') ? $("#lbl_stripe").text("Disable") : $("#lbl_stripe")
                .text("Enable");
        });


        $("#switch_paystack_gateway").on('change', function() {

            $("#switch_paystack_gateway").is(':checked') ? $("#paystack_gateway").val(1) : $("#paystack_gateway")
                .val(0);

            $("#switch_paystack_gateway").is(':checked') ? $("#lbl_paystack").text("Disable") : $("#lbl_paystack")
                .text("Enable");
        });

        function hexToRgb(hex) {
            const bigint = parseInt(hex.slice(1), 16);
            const r = (bigint >> 16) & 255;
            const g = (bigint >> 8) & 255;
            const b = bigint & 255;
            return `rgb(${r}, ${g}, ${b},0.15)`;
        }


        const colorForm = document.getElementById("setting_form");
        const systemColorInput = document.getElementById("systemColor");

        const hiddenRGBAInput = document.getElementById("hiddenRGBA");


        systemColorInput.addEventListener("change", function() {
            const selectedColor = systemColorInput.value;
            const alpha = 0.15; // You can adjust the alpha value as needed (1 for fully opaque)
            const rgba = hexToRgb(selectedColor);
            hiddenRGBAInput.value = rgba; // Update the hidden input with the new RGBA value
        });



        $(document).ready(function() {

            var companyname = $('#company_name').val();
            sessionStorage.setItem('comapanyname', $('#company_name').val());
            const newValue = `"${companyname}"`;
            const rgba = hexToRgb(systemColorInput.value);
            hiddenRGBAInput.value = rgba;
        });

        $('.fav_icon_btn').click(function() {
            $('#fav_image').click();


        });
        fav_image.onchange = evt => {
            const [file] = fav_image.files
            console.log(file);
            if (file) {
                blah_fav.src = URL.createObjectURL(file)

            }
        }
        $('.btn_comapany_logo').click(function() {
            $('#company_logo').click();


        });
        company_logo.onchange = evt => {
            const [file] = company_logo.files
            console.log(file);
            if (file) {
                blah_comapany_logo.src = URL.createObjectURL(file)

            }
        }



        $('.btn_login_image').click(function() {
            $('#login_image').click();


        });
        login_image.onchange = evt => {
            const [file] = login_image.files
            console.log(file);
            if (file) {
                blah_login_image.src = URL.createObjectURL(file)

            }
        }
    </script>
@endsection

