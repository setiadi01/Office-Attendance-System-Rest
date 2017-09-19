<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">--}}
        {{--<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>--}}
        {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
        {{--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.5/angular.min.js"></script>--}}
        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/1.0.3/angular-ui-router.min.js"></script>--}}
        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/satellizer/0.14.1/satellizer.min.js"></script>--}}

        <link href="{{URL::asset('css/style.css')}}" rel="stylesheet">
        <link href="{{URL::asset('css/app.css')}}" rel="stylesheet">
        <link href="{{URL::asset('css/bootstrap.css')}}" rel="stylesheet">
        <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="{{ URL::asset('js/jquery-3.1.1.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery-2.1.1.js') }}"></script>
        <script src="{{ URL::asset('js/qrcode.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('js/bootstrap.js') }}"></script>
        <script src="{{ URL::asset('js/angular.min.js') }}"></script>
        <script src="{{ URL::asset('js/angular-animate.min.js') }}"></script>
        <script src="{{ URL::asset('js/angular-qr.js') }}"></script>
        <script src="{{ URL::asset('js/angular-ui-router.min.js') }}"></script>
        <script src="{{ URL::asset('js/satellizer.min.js') }}"></script>

        <script src="{{ URL::asset('app/js/app.module.js') }}"></script>
        <script src="{{ URL::asset('app/js/app.service.js') }}"></script>

        <script src="{{ URL::asset('app/js/login.controller.js') }}"></script>
        <script src="{{ URL::asset('app/js/home.controller.js') }}"></script>

        {{--<script type="text/javascript" src="/bower_components/angular/angular.js"></script>--}}
        {{--<script type="text/javascript" src="/bower_components/qrcode/lib/qrcode.min.js"></script>--}}
        {{--<script type="text/javascript" src="/bower_components/angular-qr/angular-qr.min.js"></script>--}}

    </head>
    <body ng-app="absensiApp" class="body-login">
        <div class="container">
            <ui-view></ui-view>
        </div>
    </body>
</html>
