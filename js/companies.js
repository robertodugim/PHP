/*
This JS is called in the index page

- Search a company with the keyword informed
if exists redirect the user for the page.html page
else call alert
*/

(function(){
	if(window.localStorage.getItem("hasCompany") === 'true'){
		window.location.href = "page.html";
	}
	var app = angular.module("companies-section",[]);
	
	app.directive("companiesTab",[ "$window", "$http", function($window,$http){
		return {
			restrict:"E",
			templateUrl:"companies.html",
			controller:function(){
				this.keyword = "";
				this.url = "page.html";
				this.SearchCompany = function(){
					if(this.keyword.length > 0){
						var companyOnj = this;
						$http.jsonp("http://www.cbrle.com.br/mobile/GetCompany.php?callback=JSON_CALLBACK", {
							params: {
								'urlc':companyOnj.keyword
							}
						}).
						then(function(response) {
						  if(response.data.error === false){
							//Storage company data in the device
							window.localStorage.setItem("hasCompany", true);
							$.each(response.data.cli,function(k,v){
								window.localStorage.setItem(k, v);
							});
							//redirect
							$window.location.href = companyOnj.url;
						  }else{
							//if have some error in the response show then
							alert(response.data.error_string);
						  }
						}, function(response) {
						    alert("Confira sua conexão com a internet e tente novamente.");
						});
					}
				}
			},
			controllerAs:"Company"
		};
	}]);
})();