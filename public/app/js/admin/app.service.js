(function(){
	'use strict'

	angular.module('absensiApp')
	.service('AbsensiService', AbsensiService);

	function AbsensiService($http){
		return {
			getDetailCheckinService : function(input){
				var request = $http({
					method: "POST",
                	params: input,
					url: "/api/get-detail-checkin-list"
				});
				return (request);
			},

			getSummaryCheckinService : function(input){
				var request = $http({
					method: "POST",
                	params: input,
					url: "/api/get-summary-checkin-list"
				});
				return (request);
			},
		}
	}

})();