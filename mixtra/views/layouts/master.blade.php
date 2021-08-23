<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('mixtra::layouts.meta')

        <link rel="shortcut icon" href="{{ MITBooster::getSetting('favicon') ? asset(MITBooster::getSetting('favicon')) : asset('assets/images/logo/favicon.png') }}">
        <title>@yield('title', 'Admin Page') - {{MITBooster::getSetting('app_name')}}</title>
        
        @include('mixtra::layouts.styles')
		@stack('head')
    </head>
    <body class="@yield('body-class')">
    @yield('body')

    @include('mixtra::layouts.scripts')
    @stack('bottom')
    </body>
</html>
