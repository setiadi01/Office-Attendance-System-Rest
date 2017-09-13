(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('LoginCtrl', LoginCtrl);

	function LoginCtrl($scope, $auth, $state, $interval){
		var ui = $scope;

		if (localStorage.getItem('user') != null){
			$state.go('home');
		}

		$scope.theTime = new Date();
		$interval(function () {
			$scope.theTime = new Date();
		}, 1000);

		ui.login = function(){
			$auth.login(ui.input)
			.then(function(response) {
				console.log(response);
				if (response.data.status == 'OK') {
					localStorage.setItem('user', response.data.user.username);
					$state.go('home');
				}else{
					alert('kombinasi password dan username salah')
				}
			})
			.catch(function(response) {
				console.log(response);
				alert('Maaf, server sedang mengalami gangguan')
			});
		}
	}

})();