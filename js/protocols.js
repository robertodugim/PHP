(function(){
	var app = angular.module("protocol-section",["include-protocol-section"]);
	app.directive("protocolTab",[ "$window", function($window){
		return {
			restrict:"E",
			templateUrl:"protocols.html",
			controller:function($scope){
				$scope.haveProtocols = false;
				$scope.yourProtocols = new Array();
				$scope.yourProtocols =  window.localStorage.getArray("cachedProtocols");
				
				if($scope.yourProtocols){
					$scope.haveProtocols = true;
				}
				this.IncludeProtocol = function(){
					jQuery('#IncludeProtocol').modal('show');
				};
				
				this.DetailProtocol = function(det){
					$scope.detailProtocol(det); 
					$scope.redirectTab('details');
				}
			},
			controllerAs:"protocol"
		};
	}]);

})();