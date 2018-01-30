<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Absen</title>
        <link href='../img/logo.png' rel='shortcut icon'>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script src="{{ URL::asset('js/angular.min.js') }}"></script>
        <script src="{{ URL::asset('js/angular-animate.min.js') }}"></script>
        <script src="{{ URL::asset('js/angular-qr.js') }}"></script>
        <script src="{{ URL::asset('js/angular-ui-router.min.js') }}"></script>
        <script src="{{ URL::asset('js/satellizer.min.js') }}"></script>
        <script src="{{ URL::asset('app/js/admin/app.module.js') }}"></script>
        <script src="{{ URL::asset('app/js/admin/app.service.js') }}"></script>

        <script src="{{ URL::asset('app/js/login.controller.js') }}"></script>
        <script src="{{ URL::asset('app/js/admin/report.controller.js') }}"></script>

    </head>
    <body ng-app="absensiApp" class="body-login">
        <div class="container">
            <ui-view></ui-view>
        </div>
    </body>
</html>
