(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('HomeCtrl', HomeCtrl);

	function HomeCtrl($scope, $auth, $state, $interval, $timeout, AbsensiService, dataModel){
		if(localStorage.lastActive){
			var lastActive = new Date(localStorage.getItem('lastActive'));
			var tmp = $scope.theTime - lastActive;
			tmp = Math.floor(((tmp/1000) - (tmp % 60))/60);
			var totMin = tmp % 60;

			if (totMin > 10){
				localStorage.clear();
				$state.go('login');
			};
		}
		if (!localStorage.user){
			$state.go('login');
		}

		$scope.theTime = new Date();
		localStorage.setItem('lastActive', $scope.theTime);

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
		AbsensiService.getQrCode(input)
			.then(function(response){
				if(response.data.status == 'OK'){
					console.log(response);
					$scope.code = response.data.data.toString();
					localStorage.setItem('qrcode', $scope.code);
				}
			},function(){
				alert('Mohon maaf server sedang mengalami gangguan.');
			});

		}
		$scope.generateKey();

		$scope.tick = function() {
			$scope.newSeconds = $scope.theTime.getSeconds().toString();
			if ($scope.newSeconds == 0){
				$scope.generateKey();
			}
			$scope.qrcode = localStorage.getItem('qrcode');
			console.log($scope.qrcode);
			$timeout($scope.tick,1000); // reset the barcode
		}
		$scope.tick();

		$scope.logout = function() {
			if (confirm("Are you sure?")) {
				localStorage.clear();
				location.reload();
				$state.go('login');
			}
		}
	}

})();