<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen | Login</title>
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/style.css')}}" rel="stylesheet">
</head>
<body class="body-login">
<div class="middle-box text-center form-login">
    <div>
        <div>
            <h1 class="logo-name" style="color: white">
                LOGIN
            </h1>
        </div>
        @if (Session::has('successMessage'))
            <div class="alert alert-success">
                {{Session::get('successMessage')}}
            </div>
        @endif
        @if (Session::has('errormsg'))
            <div class="alert alert-danger">
                {{Session::get('errormsg')}}
            </div>
        @endif

        <form class="m-t" role="form" action="{{URL::to('getlogins')}}" method="post">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" required="" name="txtUser">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" required="" name="txtPass">
            </div>
            <div class="form-group">
                <td><input type="checkbox" name="chkRem" value='Y'></td>
                <td><label data-toggle="tooltip" data-placement="left" title="Remember Me ?" style="color: #d5d5d5"> Remember Me</label></td>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">LOGIN</button>
            {{csrf_field()}}
        </form>
    </div>
</div>
</body>
<script src="{{URL::asset('js/jquery-2.1.1.js')}}"></script>
<script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
</html>