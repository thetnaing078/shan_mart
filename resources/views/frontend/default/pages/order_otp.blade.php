@extends('frontend.default.auth.layouts.app')
@section('styles')

<link rel="stylesheet" href="{{asset(asset_path('frontend/default/css/page_css/otp.css'))}}" />

@endsection
@section('title')
{{__('otp.otp')}}
@endsection
@section('content')
<section class="login_area register_part">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 col-xl-4">
                <div class="register_form_iner">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <div class="login_logo text-center mb-3">
                        <img src="{{showImage(app('general_setting')->logo)}}" alt="">
                    </div>
                    <h2>{{ __('otp.otp_is_sent') }} </h2>

                    <form method="POST" class="register_form" action="{{ route('order_otp_check') }}">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6">
                                <br>
                                <br>
                                <label for="email">{{ __('otp.otp') }}</label>
                            </div>
                            <div class="col-md-6 float-right">
                                <div class="float-right">
                                    <div id="app"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <input type="text" id="otp" name="otp" placeholder="{{ __('otp.enter_otp') }}" required value="{{ old('otp') }}" class="@error('otp') is-invalid @enderror"
                                    onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = ''">

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <input type="hidden" id="code_validation_time" name="code_validation_time" value="">
                            <div class="col-md-12 text-center">
                                <div class="register_area">
                                    <button type="submit" id="btnSubmit" class="btn_1">{{ __('common.submit') }}</button>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <p> <a href="{{route('order_resend_otp')}}">{{ __('otp.resend_otp') }}</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@include('frontend.default.partials._otp_script')
