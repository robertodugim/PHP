(function(){
	var app = angular.module("create-page-section",[]);
	
	app.directive("createPageTab",[ "$window", "$sce", function($window, $sce){
		return {
			restrict:"E",
			templateUrl:"create-page.html",
			controller:function(){
				this.company = {
					taviso: $sce.trustAsHtml(window.localStorage.getItem("taviso_" + window.localStorage.getItem("language"))),
					ttaviso: $sce.trustAsHtml(window.localStorage.getItem("ttaviso_" + window.localStorage.getItem("language"))),
					b1aviso: $sce.trustAsHtml(window.localStorage.getItem("b1aviso_" + window.localStorage.getItem("language"))),
					b2aviso: $sce.trustAsHtml(window.localStorage.getItem("b2aviso_" + window.localStorage.getItem("language")))
				};
			},
			controllerAs:"CtrlcreatePage"
		};
	}]);
})();