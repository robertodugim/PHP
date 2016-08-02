(function(){
	var app = angular.module("possave-section",[]);
	
	app.directive("posSaveTab",[ "$window", "$sce", function($window, $sce){
		return {
			restrict:"E",
			templateUrl:"posSave.html",
			controller:function(){
				
			},
			controllerAs:"CtrlPosSave"
		};
	}]);
})();