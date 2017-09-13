(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('HomeCtrl', HomeCtrl);

	function HomeCtrl($scope, $auth, $state, $interval, $timeout){
		$scope.theTime = new Date();

		if (localStorage.getItem('user') == null){
			$state.go('login');
		};

		$scope.tick = function() {
			var user = localStorage.getItem('user');
			var year = $scope.theTime.getFullYear();
			var hour = $scope.theTime.getHours();
			var minute = $scope.theTime.getMinutes();
			var second = $scope.theTime.getSeconds();
			var month = $scope.theTime.getMonth()+1;
			if(month<10)
			{
				month = "0" + month;
			};
			var day = new Date().getDay();
			if(day<10)
			{
				day = "0" + day;
			};

			$scope.datetime = year.toString()+month.toString()+day.toString()+hour.toString()+minute.toString()+second.toString();

			$scope.code = user+'-'+$scope.datetime.toString();

			$timeout($scope.tick, 30000); // reset the timer
		}
		$scope.tick();


	}

})();