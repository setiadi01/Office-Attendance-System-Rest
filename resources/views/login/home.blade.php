@extends('layout.master')

@section('head')
    <script src="{{URL::asset('/assets/absen/absen.module.js')}}"></script>
    <script src="{{URL::asset('/assets/absen/absen.service.js')}}"></script>
    <script src="{{URL::asset('/assets/absen/absen.controller.js')}}"></script>
@endsection

@section("title")
    Absen
@endsection

<div class="fixed-top-right">
    <div class="col">
        <div class="col-md-12 col-sm-12">
            <h1>{{clock | date:'HH:mm:ss'}}</h1>
            {{clock | date:'EEEE, dd MMMM'}}
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="middle-box text-center form-login">
                <div>
                    <h1 class="logo-name" style="color: white">
                        SCAN ME
                    </h1>
                </div>
                <hr>

            </div>

        </div>
    </div>
</div>


@section('feet')
    <script src="{{URL::asset('js/jquery-2.1.1.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
@endsection