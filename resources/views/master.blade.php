<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.5/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/1.0.3/angular-ui-router.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/satellizer/0.14.1/satellizer.min.js"></script>
        
        <script src="{{ URL::asset('app/js/app.module.js') }}"></script>
        <script src="{{ URL::asset('app/js/app.service.js') }}"></script>

        <script src="{{ URL::asset('app/js/login.controller.js') }}"></script>
        <script src="{{ URL::asset('app/js/home.controller.js') }}"></script>

        <style>
            @import url(http://fonts.googleapis.com/css?family=Roboto:400);
            body {
              background-color:#fff;
              -webkit-font-smoothing: antialiased;
              font: normal 14px Roboto,arial,sans-serif;
            }

            .container {
                padding: 25px;
                position: fixed;
            }

            .form-login {
                background-color: #EDEDED;
                padding-top: 10px;
                padding-bottom: 20px;
                padding-left: 20px;
                padding-right: 20px;
                border-radius: 15px;
                border-color:#d2d2d2;
                border-width: 5px;
                box-shadow:0 1px 0 #cfcfcf;
            }

            h4 { 
             border:0 solid #fff; 
             border-bottom-width:1px;
             padding-bottom:10px;
             text-align: center;
            }

            .form-control {
                border-radius: 10px;
            }

            .wrapper {
                text-align: center;
            }

        </style>
    </head>
    <body ng-app="absensiApp">
        <div class="container">
            <ui-view></ui-view>
        </div>
    </body>
</html>
