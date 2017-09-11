(function(){
	
	'use strict';

	angular
	.module('absenApp',[
	    'ui.bootstrap',
	    'ui.router',
		'ja.qr'
	 ])
		.config(myConfig).factory('dataModel',dataModel);
	
	function myConfig($stateProvider, $urlRouterProvider){
		// For any unmatched url, redirect to /state1
		$urlRouterProvider.otherwise("/login");
	  
		$stateProvider
		.state('login', {
		      url: "/login",
		      templateUrl: "/html/absen/login.html",
		      controller: 'LoginCtrl'
		})
		.state('home', {
			url: "/home",
			templateUrl: "/html/absen/home.html",
			controller: 'HomeCtrl'
		});
	}
	function dataModel(){
		return {

		}
	}
	
})();