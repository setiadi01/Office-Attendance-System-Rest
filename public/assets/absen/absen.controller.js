(function(){
	
	'use strict';
	
	angular
	.module('absenApp')
	.controller('LoginCtrl', LoginCtrl)
	.controller('HomeCtrl', HomeCtrl);

	LoginCtrl.$inject   = ['$scope','$state', 'dataModel', '$timeout','$stateParams','$uibModal','$log','AbsenService'];
	HomeCtrl.$inject   = ['$scope','$state', 'dataModel', '$timeout','$stateParams','$uibModal','$log','AbsenService'];

	function LoginCtrl($scope,$state, dataModel ,$timeout,$stateParams,$uibModal,$log,AbsenService){
		
		document.title = 'Absen';

		$scope.tick = function() {
			$scope.clock = Date.now() // get the current time
			$timeout($scope.tick, 1000); // reset the timer
		}
		$scope.tick();
		// Get Data Supplier Sales Summary

		$scope.username = '';
		$scope.password = '';

		$scope.doLogin = function() {

			var inputdata = {
				'username': $scope.username,
				'password': $scope.password
			}

			console.log(inputdata);
			AbsenService.doLogin(inputdata)
				.then(function (response) {
					if (response.data.status == 'OK') {
						$scope.loader = false;
						console.log(response);
						dataModel.username = inputdata.username;
						$state.go('home');
						//$scope.dailySalesTotalAmount = response.data.daily_sales_total_amount.dailySalesTotalAmount;
						//$scope.dailySalesTotalAmountDate = response.data.daily_sales_total_amount.dailySalesTotalAmountDate;
					} else {
						$scope.loader = false;
						$scope.messageError = 'User login tidak sesuai';
					}
				}, function (response) {
					console.log('gagal jon');
					$scope.messageError = 'Sistem sedang dalam perbaikan';
					//alert('Mohon Maaf Server Sedang Mengalami Gangguan.');

				});
		}
	}
	function HomeCtrl($scope,$state, dataModel, $timeout,$stateParams,$uibModal,$log,AbsenService){
		$scope.user = dataModel.username;
		if ($scope.user == null ){
			$state.go('login');
		}
		document.title = 'Absen';

		$scope.tick = function() {
			$scope.clock = Date.now() // get the current time
			var n = $scope.clock.toString();
			$scope.qrcode = n;
			$timeout($scope.tick, 1000); // reset the timer
		}
		$scope.tick();
		$scope.qrcode = 'SELAMAT DATANG';
		// Get Data Supplier Sales Summary

		$scope.username = '';
		$scope.password = '';

		$scope.doLogin = function() {

			var inputdata = {
				'username': $scope.username,
				'password': $scope.password
			}

			console.log(inputdata);
			AbsenService.doLogin(inputdata)
				.then(function (response) {
					if (response.data.status == 'OK') {
						$scope.loader = false;
						console.log(response);
						$state.go('home');
						//$scope.dailySalesTotalAmount = response.data.daily_sales_total_amount.dailySalesTotalAmount;
						//$scope.dailySalesTotalAmountDate = response.data.daily_sales_total_amount.dailySalesTotalAmountDate;
					} else {
						$scope.loader = false;
						$scope.messageError = 'User login tidak sesuai';
					}
				}, function (response) {
					console.log('gagal jon');
					$scope.messageError = 'Sistem sedang dalam perbaikan';
					//alert('Mohon Maaf Server Sedang Mengalami Gangguan.');

				});
		}
	}
	
})();