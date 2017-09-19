(function(){
	'use strict'

	angular.module('absensiApp')
	.service('AbsensiService', AbsensiService);

	function AbsensiService($http){
		return {
			getUuid : function(input){
				var request = $http({
					method: "GET",
					url: "/api/get-qrcode/"+input.username+"/"+input.user_id
				});
				return (request);
			},
		}
	}
})();