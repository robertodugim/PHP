(function(){
	var app = angular.module("hist-page-section",[]);
	app.directive("histModal",[ "$http", "$window", function($http, $window){
		return {
			restrict:"E",
			templateUrl:"hist-page.html",
			controller:function($scope){
				this.histories = {};
				$objHist = this;
				$scope.ListHist = function(number){
					$scope.StartLoading();
					$http.jsonp("http://www.cbrle.com.br/mobile/ListHist.php?callback=JSON_CALLBACK", {
						params: {
							'id_denuncia':number,
							'idioma':window.localStorage.getItem("language")
						}
					}).
					then(function(response) {
					  if(response.data.error === false){
						$objHist.histories = response.data.ret;
						
					  }else{
						alert(response.data.error_string);
					  }
					}, function(response) {
						alert("Check your internet connection and try again.");
					}).then(function() {
						$scope.EndLoading();
					});
				};
				this.OpenUrl = function (url){
					window.open(url, '_system');
				}
			},
			controllerAs:"CtrlHistory"
		};
	}]);

})();