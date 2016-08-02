(function(){
	var app = angular.module("footer-section",[]);
	app.directive("footerTab",[ "$window", "$timeout", function($window, $timeout){
		return {
			restrict:"E",
			templateUrl:"footer.html",
			controller:function($scope){
				this.collapse = true;
				this.icon = window.localStorage.getItem("logo_" + window.localStorage.getItem("language"));
				this.conduta = window.localStorage.getItem("dconduta_" + window.localStorage.getItem("language"));
				this.condutaName = window.localStorage.getItem("conduta_" + window.localStorage.getItem("language"));
				
				this.OpenUrl = function (url){
					window.open(url, '_system');
				}
				this.footerCollapse = function(){
					if(this.collapse === true){
						this.collapse = false;
					}else{
						this.collapse = true;
					}
				};
				this.SetLanguage = function(lang){
					window.localStorage.setItem("language",lang);
					window.location.reload(true);
				};
				this.languages = EnableLangs;
				
				if(window.localStorage.getItem("hasTutorial") === null){
					$timeout($scope.Tutorial, "2000");
					
				}
			},
			controllerAs:"tab"
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