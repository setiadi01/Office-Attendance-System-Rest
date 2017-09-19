(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('HomeCtrl', HomeCtrl);

	function HomeCtrl($scope, $auth, $state, $interval, $timeout, AbsensiService, dataModel){
		$scope.theTime = new Date();

		if (localStorage.getItem('user') == null){
			$state.go('login');
		};

		$scope.time = function() {
			$scope.theTime = new Date() // get the current time
			$timeout($scope.time, 1000); // reset the timer
		}
		$scope.time();

		var currentUser = JSON.parse(localStorage.getItem('user'));
		$scope.fullname = currentUser.full_name.toUpperCase();
		var input = {};
		input.username = currentUser.username;
		input.user_id = currentUser.user_id;

		$scope.generateKey = function() {
		AbsensiService.getUuid(input)
			.then(function(response){
				if(response.data.status == 'OK'){
					console.log(response);
					$scope.code = response.data.data.toString();
					dataModel.qrcode = $scope.code;
					localStorage.setItem('qrcode', $scope.code);
				}
			},function(){
				alert('Mohon maaf server sedang mengalami gangguan.');
			});
			$scope.qrcode = localStorage.getItem('qrcode');
			console.log($scope.qrcode);
		}
		$scope.generateKey();

		$scope.tick = function() {
			$scope.newSeconds = $scope.theTime.getSeconds().toString();
			if ($scope.newSeconds == 0){
				$scope.generateKey();
			}
			$timeout($scope.tick,1000); // reset the barcode
		}
		$scope.tick();

		$scope.logout = function() {
			localStorage.removeItem('user');
			localStorage.removeItem('qrcode');
			localStorage.removeItem('satellizer_token');
			$state.go('login');
		}
	}

})();