(function(){
	
	'use strict';
	
	angular
	.module('absenApp')
	.service('AbsenService', ['$http', http]);
	
	function http($http){
		return {
			doLogin : function(input){
				return $http.post("/api/login/", input);
			},
		}
	}
	
})();