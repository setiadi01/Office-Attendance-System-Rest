(function(){
	'use strict'

	angular.module('absensiApp')
	.controller('ReportCtrl', ReportCtrl);

	function ReportCtrl($scope, $auth, $state, $interval, $timeout, AbsensiService, dataModel, $window){
		var ui = $scope;
        ui.auth = true;

        var startPeriod = new Date();
        if(startPeriod.getDate()<21) {
            startPeriod.setDate(1);
            startPeriod.setMonth(startPeriod.getMonth()-1);
        }
        startPeriod.setDate(21);
        ui.startPeriod = startPeriod;

        var endPeriod = new Date();
        if(endPeriod.getDate()>20) {
            endPeriod.setDate(1);
            endPeriod.setMonth(endPeriod.getMonth()+1);
        }
        endPeriod.setDate(20);
        ui.endPeriod = endPeriod;

        var filterStartPeriod = startPeriod.getFullYear().toString()+("0" + (startPeriod.getMonth() + 1)).slice(-2)+("0" + startPeriod.getDate()).slice(-2);
        var filterEndPeriod = endPeriod.getFullYear().toString()+("0" + (endPeriod.getMonth() + 1)).slice(-2)+("0" + endPeriod.getDate()).slice(-2);

        var input = {
            start_date: filterStartPeriod,
            end_date: filterEndPeriod,
            limit: -99,
            offset: -99
        };

        AbsensiService.getDetailCheckinService(input)
            .then(function(response){
                if(response.data.status == 'OK'){
                    ui.detailCheckinList = response.data.response;
                    ui.auth = true;
                } else if(response.data.status == 'FAIL'){
                    ui.auth = false;
                }
            },function(response){
                ui.auth = false;
                console.log(response);
            });

        AbsensiService.getSummaryCheckinService(input)
            .then(function(response){
                if(response.data.status == 'OK'){
                    ui.summaryCheckinList = response.data.response;
                } else if(response.data.status == 'FAIL'){

                }
            },function(response){
                ui.auth = false;
                console.log(response);
            });

        $scope.logout = function () {
            localStorage.clear();
            $window.location.href = '/';
        }

		// user must relogin after 10 minutes
		var checkLastActive = function () {
            localStorage.clear();
			$window.location.href = '/';
        };
        $timeout(checkLastActive, 600000);
	}

})();