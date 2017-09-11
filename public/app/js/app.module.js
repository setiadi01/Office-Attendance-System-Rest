(function(){

	'use strict'

	angular.module('absensiApp', ['ui.router', 'satellizer'])
	.config(function($stateProvider, $urlRouterProvider, $authProvider){

		$authProvider.loginUrl = '/api/login';

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

	})

})();