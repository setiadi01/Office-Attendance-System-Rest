(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('HomeCtrl', HomeCtrl);

	function HomeCtrl($scope, $auth, $state, $interval, $timeout, AbsensiService){
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
		$scope.generateKey = function() {
			var year = $scope.theTime.getFullYear();
			var hour = $scope.theTime.getHours();
			var minute = $scope.theTime.getMinutes();
			var seconds = $scope.theTime.getSeconds();
			var month = $scope.theTime.getMonth()+1;
			if(month<10){month = "0" + month;};
			var day = new Date().getDay();
			if(day<10){day = "0" + day;};
			$scope.datetime = year.toString()+month.toString()+day.toString()+hour.toString()+minute.toString()+seconds.toString();
			$scope.code = currentUser.username+'-'+$scope.datetime.toString();
		}
		$scope.generateKey();

		$scope.tick = function() {
			$scope.newSeconds = $scope.theTime.getSeconds().toString();
			console.log($scope.newSeconds);
			if ($scope.newSeconds == 0){
				$scope.generateKey();
			}
			$timeout($scope.tick,1000); // reset the barcode
		}
		$scope.tick();

		AbsensiService.getUuid()
			.then(function(response){
				if(response.data.status == 'OK'){
					console.log(response);

				}
			},function(){
				alert('Mohon maaf server sedang mengalami gangguan.');
			});

	}

})();