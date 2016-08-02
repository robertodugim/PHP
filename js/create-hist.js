(function(){
	var app = angular.module("create-hist-section",[]);
	app.directive("createHistModal",[ "$http", "$sce", function($http, $sce){
		return {
			restrict:"E",
			templateUrl:"create-hist.html",
			controller:function($scope){
				this.hist = {};
				this.hist.idioma = window.localStorage.getItem("language");
				this.hist.cli = window.localStorage.getItem("id_cliente");
				this.hist.id_denuncia = '';
				$objCreateHist = this;
				$scope.SetHistIdDenuncia = function(idD){
					$objCreateHist.hist.id_denuncia = idD;
				};
				this.submit = function(){
					$scope.StartLoading();
					
					var fd = new FormData();
					if(typeof $scope.files.anexo !== 'undefined'){
						fd.append('anexo', $scope.files.anexo);
					}
					if(typeof $scope.files.anexo1 !== 'undefined'){
						fd.append('anexo2', $scope.files.anexo1);
					}
					if(typeof $scope.files.anexo2 !== 'undefined'){
						fd.append('anexo3', $scope.files.anexo2);
					}
					$.each($objCreateHist.hist, function(k,v){
						fd.append(k, v);
					});
					$http.post("http://www.cbrle.com.br/mobile/InseHist.php", fd, {
						transformRequest: angular.identity,
						headers: {'Content-Type': undefined}
					}).
					then(function(response) {
					  if(response.data.error === false){
							$scope.files.anexo = '';
							$scope.files.anexo1 = '';
							$scope.files.anexo2 = '';
							$objCreateHist.hist = {};
							$objCreateHist.hist.idioma = window.localStorage.getItem("language");
							$objCreateHist.hist.cli = window.localStorage.getItem("id_cliente");
							alert($scope.labels.lbl_mobile_info_adiconal);
							jQuery('#IncludeHist').modal('hide');
							$scope.EndLoading();
					  }else{
						$scope.EndLoading();
						alert(response.data.error_string);
					  }
					}, function(response) {
						$scope.EndLoading();
						alert("Check your internet connection and try again.");
					});
				};
			},
			controllerAs:"createHist"
		};
	}]);

})();