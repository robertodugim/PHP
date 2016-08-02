(function(){
	var app = angular.module("header-section",[]);
	app.directive("headerTab",[ "$window", "$timeout", function($window, $timeout){
		return {
			restrict:"E",
			templateUrl:"header.html",
			controller:function($scope){
				this.collapse = true;
				this.icon = window.localStorage.getItem("logo_" + window.localStorage.getItem("language"));
				this.SetLanguage = function(lang){
					window.localStorage.setItem("language",lang);
					window.location.reload(true);
				};
				this.languages = EnableLangs;
				
			},
			controllerAs:"Ctrlheader"
		};
	}]);
	var EnableLangs = new Array();
	var cl = 0;
	if(window.localStorage.getItem("idioma_br") == "1"){
		EnableLangs[cl] = {
			image: "images/icon_brasil.jpg",
			lng:"br"
		};
		cl++;
	}
	if(window.localStorage.getItem("idioma_en") == "1"){
		EnableLangs[cl] = {
			image: "images/icon_eua.jpg",
			lng:"en"
		};
		cl++;
	}
	if(window.localStorage.getItem("idioma_es") == "1"){
		EnableLangs[cl] = {
			image: "images/icon_espanha.jpg",
			lng:"es"
		};
	}
})();