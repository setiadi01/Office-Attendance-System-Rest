(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('LoginCtrl', LoginCtrl);

	function LoginCtrl($scope, $auth, $state, $interval, $window){
		var ui = $scope;
		$scope.loader = false;

		if (localStorage.getItem('user') != null){
			if(JSON.parse(localStorage.getItem('user')).role == 'admin') {
				$window.location.href = '/dashboard/#/report';
			} else {
				$state.go('home');	
			}
		}

		$scope.theTime = new Date();
		$interval(function () {
			$scope.theTime = new Date();
		}, 1000);

		ui.login = function(){
			$scope.loader = true;
			$auth.login(ui.input)
			.then(function(response) {
				if (response.data.status == 'OK') {
					// localStorage.setItem('user', response.data.user.username);
					localStorage.setItem('user', JSON.stringify(response.data.user));
                    localStorage.setItem('userLogged', new Date());
					$scope.loader = false;
					if(response.data.user.role == 'admin') {
						$window.location.href = '/dashboard/#/report';
					} else {
						$state.go('home');	
					}
				}else{
					$scope.loader = false;
					alert('kombinasi password dan username salah')
				}
			})
			.catch(function(response) {
				$scope.loader = false;
				alert('Maaf, server sedang mengalami gangguan')
			});
		}
	}

})();