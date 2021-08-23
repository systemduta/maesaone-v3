@extends('mixtra::layouts.master')

@section('body-class', 'account-page')
@section('title', 'Login Page')

@section('body')
<!-- Main Wrapper -->
<div class="main-wrapper">
    <div class="account-content">
        <div class="container">
            <div class="account-box">
                <div class="account-wrapper">
                    <!-- Account Logo -->
                    <div class="account-logo">
                        <img src="{{ MITBooster::getSetting('logo_text') ? asset(MITBooster::getSetting('logo_text')) : asset('assets/images/logo/logo_text.png') }}" alt="{{ MITBooster::getSetting('app_name') }}"/>
                    </div>
                    <!-- /Account Logo -->
                    <h3 class="account-title">Welcome to {{ MITBooster::getSetting('app_name') }}! 👋</h3>
                    <p class="account-subtitle">Please sign-in to your account</p>
                    
                    <!-- Account Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email / NIK / Phone</label>
                            <input type="text" class="form-control" id="email" name="email" autofocus value="{{ old('email') }}" />
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label>Password</label>
                                </div>
                                {{--
                                <div class="col-auto">
                                    <a class="text-muted" href="forgot-password">
                                        Forgot password?
                                    </a>
                                </div>
                                --}}
                            </div>
                            <input type="password" class="form-control" id="password" name="password" />
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary account-btn" type="submit">Login</button>
                        </div>
                        {{--
                        <div class="account-footer">
                            <p>Don't have an account yet? <a href="register">Register</a></p>
                        </div>
                        --}}
                    </form>
                    <!-- /Account Form -->
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->

@endsection
		