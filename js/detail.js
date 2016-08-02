(function(){
	var app = angular.module("detail-section",["hist-page-section","create-hist-section"]);
	app.directive("detailTab",[ "$http", function($http){
		return {
			restrict:"E",
			templateUrl:"detail.html",
			controller:function($scope){
				this.protocol = '';
				$objCaseD = this;
				$scope.detailProtocol = function(det){
					
					if(det){
						$scope.StartLoading();
						$http.jsonp("http://www.cbrle.com.br/mobile/GetProtocol.php?callback=JSON_CALLBACK", {
							params: {
								'number':det,
								'idioma':window.localStorage.getItem("language"),
								'cli': window.localStorage.getItem("id_cliente")
							}
						}).
						then(function(response) {
						  if(response.data.error === false){
								
								$objCaseD.protocol = response.data.protocol;
								
						  }else{
							alert(response.data.error_string);
						  }
						}, function(response) {
							alert("Check your internet connection and try again.");
						}).then(function(){
							$scope.EndLoading();
						});
					}
				};
				this.OpenHistory = function(){
					jQuery('#ListHistory').modal('show');
					$scope.ListHist(this.protocol.id_denuncia);
				};
				this.OpenCreateHistory = function(){
					jQuery('#IncludeHist').modal('show');
					$scope.SetHistIdDenuncia(this.protocol.id_denuncia);
				};
			},
			controllerAs:"case"
		};
	}]);

})();