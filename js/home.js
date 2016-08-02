(function(){
	var app = angular.module("home-section",[]);
	
	app.directive("homeTab",[ "$window", "$sce", function($window, $sce){
		return {
			restrict:"E",
			templateUrl:"home.html",
			controller:function(){
				this.icon = window.localStorage.getItem("logo_" + window.localStorage.getItem("language"));
				this.redirectTab = function (url){
					$window.location.href = url;
				};
				this.company = {
					text: $sce.trustAsHtml(window.localStorage.getItem("texto_" + window.localStorage.getItem("language")))
				};
			},
			controllerAs:"CtrlCompany"
		};
	}]);
})();