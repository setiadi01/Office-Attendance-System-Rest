(function(){
	'use strict'

	angular.module('absensiApp')
	.service('AbsensiService', AbsensiService);

	function AbsensiService($http){
		return {
			getUuid : function(){
				var request = $http({
					method: "GET",
					url: "/api/get-qrcode"
				});
				return (request);
			},
		}
	}
})();