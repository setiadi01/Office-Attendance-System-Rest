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

        ui.doSearch = function() {
            ui.disableBtnSearch = true;
            var input = {
                start_date: moment(ui.startPeriod, 'DD/MM/YYYY').format('YYYYMMDD'),
                end_date: moment(ui.endPeriod, 'DD/MM/YYYY').format('YYYYMMDD'),
                limit: -99,
                offset: -99
            };
            AbsensiService.getDetailCheckinService(input)
                .then(function(response){
                    if(response.data.status == 'OK'){
                        ui.detailCheckinList = response.data.response;
                        ui.auth = true;
                        ui.disableBtnSearch = false;
                    } else if(response.data.status == 'FAIL'){
                        ui.auth = false;
                        ui.disableBtnSearch = false;
                    }
                },function(response){
                    ui.auth = false;
                    ui.disableBtnSearch = false;
                    console.log(response);
                });

            AbsensiService.getSummaryCheckinService(input)
                .then(function(response){
                    if(response.data.status == 'OK'){
                        ui.summaryCheckinList = response.data.response;
                        ui.disableBtnSearch = false;
                    } else if(response.data.status == 'FAIL'){
                        ui.disableBtnSearch = false;
                    }
                },function(response){
                    ui.auth = false;
                    ui.disableBtnSearch = false;
                    console.log(response);
                });

        }

        ui.doSearch();

        $scope.logout = function () {
            localStorage.clear();
            $window.location.href = '/';
        }

		// user must relogin after 10 minutes
		// var checkLastActive = function () {
        //     localStorage.clear();
		// 	$window.location.href = '/';
        // };
        // $timeout(checkLastActive, 600000);

        $scope.getTotal = function (amount1, amount2) {
            return parseInt(amount1)+parseInt(amount2);
        }

        $scope.getGolongan = function (totalTelat, flgAbsenKosong, flgEscapeDenda) {

            if(flgEscapeDenda=='N') {
                if(flgAbsenKosong=='Y') return 'Golongan 4';
                if(parseInt(totalTelat) <= 15) return 'Golongan 0';
                if(parseInt(totalTelat) > 15 && parseInt(totalTelat) <= 30) return 'Golongan 1';
                if(parseInt(totalTelat) > 30 && parseInt(totalTelat) <= 45) return 'Golongan 2';
                if(parseInt(totalTelat) > 45 && parseInt(totalTelat) <= 60) return 'Golongan 3';
                if(parseInt(totalTelat) > 60) return 'Golongan 4';
            }
            return '-';
        }
	}

})();
