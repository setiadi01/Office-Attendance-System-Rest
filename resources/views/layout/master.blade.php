<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield("title")</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{URL::asset('css/bootstrap.css')}}" rel="stylesheet">
		<link href="{{URL::asset('css/style.css')}}" rel="stylesheet">
		<script src="{{URL::asset('js/jquery-2.1.1.js')}}"></script>
		<script src="{{URL::asset('js/angular.min.js')}}"></script>
		<script src="{{URL::asset('js/angular-animate.min.js')}}"></script>
		<script src="{{URL::asset('js/angular-leaf.js')}}"></script>
		<script src="{{URL::asset('js/angular-ui-router.min.js')}}"></script>
		<script src="{{URL::asset('js/bootstrap.js')}}"></script>
		<script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
		<script src="{{URL::asset('js/ui-bootstrap-tpls-1.3.3.min.js')}}"></script>
		<script src="{{URL::asset('js/jquery-3.1.1.min.js')}}"></script>
		<script src="{{URL::asset('js/qrcode.js')}}"></script>
		<script src="{{URL::asset('js/angular-qr.js')}}"></script>
		{{--<script>--}}
		{{--window.Laravel = {--}}
				{{--csrfToken: '{{ csrf_token() }}',--}}
				{{--suppType: '{{ Session::get("sessUser")["role_default_name"] }}'--}}
			{{--};--}}

		{{--</script>--}}

		@yield("head")
		
    </head>
	<body class="body-login">
    	<div class="container-fluid main-container">
            <div class="page-content">
            	@yield("content")
            </div>
        </div>
        
        @yield("end-body")
        <div class="loading" id="loading" ng-if="loader">
        	<div class="loading-wheel" id="loading-wheel">
   
        	</div>
        </div>

        <div class="sc-footer">
        	
        </div>

    </body>
	@yield("feet")
</html>
