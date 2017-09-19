(function(){

	'use strict'

	angular.module('absensiApp', ['ui.router', 'satellizer', 'ja.qr'])
	.config(function($stateProvider, $urlRouterProvider, $authProvider){

		$authProvider.loginUrl = '/api/loginWeb';

		$stateProvider
		.state('login', {
			url : '/login',
			templateUrl : 'app/html/login.html',
			controller : 'LoginCtrl'
		})
		.state('home', {
			url : '/home',
			templateUrl : 'app/html/home.html',
			controller : 'HomeCtrl'
		});

		$urlRouterProvider.otherwise('/login');

	}).factory('dataModel',dataModel);
	function dataModel(){
		return {

		}
	}

})();