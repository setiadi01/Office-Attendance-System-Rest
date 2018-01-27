(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('HomeCtrl', HomeCtrl);

	function HomeCtrl($scope, $auth, $state, $interval, $timeout, AbsensiService, dataModel){

        $scope.theTime = new Date();

		// check user logged, jika tidak ada atau sudah berbeda hari maka user harus login ulang
		if(localStorage.userLogged) {
            var userLogged = new Date(localStorage.getItem('userLogged'));
            var userLoggedDate = userLogged.getDate();

            if(userLoggedDate != $scope.theTime.getDate()) {
                localStorage.clear();
                $state.go('login');
			}

		} else {
            localStorage.clear();
            $state.go('login');
		}

        localStorage.setItem('lastActive', $scope.theTime);

		// user must relogin after 10 minutes
		var checkLastActive = function () {
            localStorage.clear();
            $state.go('login');
        };
        $timeout(checkLastActive, 600000);

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