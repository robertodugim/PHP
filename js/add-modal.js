(function(){
	var app = angular.module("include-protocol-section",[]);
	app.directive("addModal",[ "$http", "$window", function($http, $window){
		return {
			restrict:"E",
			templateUrl:"add-modal.html",
			controller:function($scope){
				this.number = '';
				$scope.IncludeCacheProtocol = function(number,refreshPage,StorageProtocol){
					if(typeof StorageProtocol == 'undefined'){
						StorageProtocol = 1;
					}
					$http.jsonp("http://www.cbrle.com.br/mobile/GetProtocol.php?callback=JSON_CALLBACK", {
						params: {
							'number':number,
							'type':'lista',
							'idioma':window.localStorage.getItem("language"),
							'cli': window.localStorage.getItem("id_cliente")
						}
					}).
					then(function(response) {
					  if(response.data.error === false){
						
						$scope.haveProtocols = true;
						jQuery('#IncludeProtocol').modal('hide');
						if(StorageProtocol == 1){
							if($scope.yourProtocols == null){
								$scope.yourProtocols = new Array();
								$scope.yourProtocols.push(response.data.protocol);
								window.localStorage.setArray("cachedProtocols",$scope.yourProtocols);
							}else {
								$scope.yourProtocols.push(response.data.protocol);
								window.localStorage.setArray("cachedProtocols",$scope.yourProtocols);
							}
						}
					  }else{
						alert(response.data.error_string);
					  }
					}, function(response) {
						alert("Check your internet connection and try again.");
					}).then(function() {
						if(refreshPage == 1){
							$window.location.href = "index.html";
						}
						$scope.EndLoading();
					});
				};
				this.IncludeProtocol = function(){
					if(this.number){
						if(jQuery('a[protocolo="'+this.number+'"]').text() != 'Acessar'){
							$scope.StartLoading();
							$scope.IncludeCacheProtocol(this.number,2);
						}else{
							alert($scope.labels.lbl_mobile_ja_existe);
							jQuery('#IncludeProtocol').modal('hide');
						}
						this.number = '';
					}
				}
			},
			controllerAs:"minclude"
		};
	}]);

})();