(function(){
	var app = angular.module("create-case-section",[]);
	app.directive("createCaseTab",[ "$http", "$sce", function($http, $sce){
		return {
			restrict:"E",
			templateUrl:"create-case.html",
			controller:function($scope){
				this.ccase = {};
				$objCase = this;
				
				$http.jsonp("http://www.cbrle.com.br/mobile/GetEditSettings.php?callback=JSON_CALLBACK", {
					params: {
						'idioma':window.localStorage.getItem("language"),
						'cli': window.localStorage.getItem("id_cliente")
					}
				}).
				then(function(response) {
				  if(response.data.error === false){
						$objCase.settings = response.data.settings;
						$objCase.settings.conteudo = $sce.trustAsHtml(response.data.settings.conteudo);		
						$objCase.ccase.status = response.data.settings.status;
						$objCase.ccase.canal = response.data.settings.canal;
						$objCase.ccase.idioma = window.localStorage.getItem("language");
						$objCase.ccase.cli = window.localStorage.getItem("id_cliente");
				  }else{
					alert(response.data.error_string);
				  }
				}, function(response) {
					alert("Check your internet connection and try again.");
				});
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
					$.each($objCase.ccase, function(k,v){
						fd.append(k, v);
					});
					// $http.jsonp("http://www.cbrle.com.br/mobile/InseDenu.php?callback=JSON_CALLBACK", {
						// params: $objCase.ccase
					// }).
					$http.post("http://www.cbrle.com.br/mobile/InseDenun.php", fd, {
						transformRequest: angular.identity,
						headers: {'Content-Type': undefined}
					}).
					then(function(response) {
					  if(response.data.error === false){
							$scope.files.anexo = '';
							$scope.files.anexo1 = '';
							$scope.files.anexo2 = '';
							window.localStorage.setItem("ProtocolSave",response.data.ret.protocolo);
							$scope.IncludeCacheProtocol(response.data.ret.protocolo,1,$objCase.ccase.armazenar);
					  }else{
						$scope.EndLoading();
						alert(response.data.error_string);
					  }
					}, function(response) {
						$scope.EndLoading();
						alert("Check your internet connection and try again.");
					});
				};
				this.RequiredIdentification = function(){
					if(this.ccase.identificar == '2' || this.ccase.identificar == 2){
						$('#nome,#email').removeAttr('required');
						
					}else{
						$('#nome,#email').attr('required','required');
					}
				};
				this.ValidForm = function(){
					if($scope.case_form.$valid === true){
						if(this.ccase.identificar == '2' || this.ccase.identificar == 2){
							return true;
						}else{
							if($scope.case_form.nome.$valid === true && $scope.case_form.email.$valid === true && this.ccase.nome && this.ccase.email){
								return true;
							}else{
								return false;
							}
							
						}
					}else{
						return false;
					}
				};
				this.GetIdentification = function(){
					if(this.ccase.identificar == '2' || this.ccase.identificar == 2){
						return false;
					}else{
						return true;
					}
				};
				this.SubCategoriaDisable = function(){
					if(this.ccase.categoria){
						return false;
					}else{
						return true;
					}
				};
				this.SelectSubC = function(){
					$objCase.ccase.id_pergunta = this.settings.subcategorias[this.ccase.categoria][this.ccase.subcategoria].id_pergunta;
					$objCase.ccase.risco = this.settings.subcategorias[this.ccase.categoria][this.ccase.subcategoria].risco;
				};
			},
			controllerAs:"createCase"
		};
	}]);

})();