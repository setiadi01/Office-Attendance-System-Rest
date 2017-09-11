(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('LoginCtrl', LoginCtrl);

	function LoginCtrl($scope, $auth, $state){
		var ui = $scope;

		ui.login = function(){
			$auth.login(ui.input)
			.then(function(response) {
				console.log(response);
				if (response.data.status == 'OK') {
					localStorage.setItem('user', JSON.stringify(response.data.user));
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