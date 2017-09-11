@extends('layout.master')

@section('head')
    <script src="{{URL::asset('/assets/absen/absen.module.js')}}"></script>
    <script src="{{URL::asset('/assets/absen/absen.service.js')}}"></script>
    <script src="{{URL::asset('/assets/absen/absen.controller.js')}}"></script>
@endsection

@section("title")
    Absen
@endsection

@section('content')
    <div ng-app="absenApp">
        <div ui-view></div>
    </div>
@endsection

@section('feet')
    <script src="{{URL::asset('js/jquery-2.1.1.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
@endsection