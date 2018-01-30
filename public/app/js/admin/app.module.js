(function(){

	'use strict'

	angular.module('absensiApp', ['ui.router', 'satellizer'])
	.config(function($stateProvider, $urlRouterProvider, $authProvider){

		$authProvider.loginUrl = '/api/loginWeb';

		$stateProvider.state('report', {
			url : '/report',
			templateUrl : '/app/html/report.html',
			controller : 'ReportCtrl'
		});

		$urlRouterProvider.otherwise('/report');

	}).factory('dataModel',dataModel);
	function dataModel(){
		return {

		}
	}

})();