/*
	Primary JS to the application
*/
(function(){
	// create the primary module and dependecies
	var app = angular.module("linhaetica",["home-section","protocol-section","detail-section","create-page-section","create-case-section","footer-section","header-section","possave-section"]);
	
	if(window.localStorage.getItem("language") === null){
		window.localStorage.setItem("language",'br');
	}
	app.controller("Primary",[ "$scope", "$http", "$window", "$sce", function($scope, $http, $window, $sce){
		//When controller is called get the translations of the fields, buttons, dropdowns option and etc by current language
		$http.jsonp("http://www.cbrle.com.br/mobile/GetLabels.php?callback=JSON_CALLBACK", {
			params: {
				'idioma':window.localStorage.getItem("language"),
				'cli': window.localStorage.getItem("id_cliente")
			}
		}).
		then(function(response) {
		  if(response.data.error === false){
			$scope.labels = response.data.labels;
			$scope.labels.lbl_possave_fim = $sce.trustAsHtml($scope.labels.lbl_possave_fim);
		  }else{
			alert(response.data.error_string);
		  }
		}, function(response) {
			// alert("Confira sua conexão com a internet e tente novamente.");
			$window.location.href = "no-connection.html";
		});
		
		//Tabs-Pages
		$scope.homeShow = true;
		$scope.protocolsPage = false;
		$scope.details = false;
		$scope.createPage = false;
		$scope.createCasePage = false;
		if(window.localStorage.getItem("ProtocolSave") === null){
			$scope.posSave = false;
		}else{
			$scope.homeShow = false;
			$scope.posSave = true;
			$scope.ProtocolSave = window.localStorage.getItem("ProtocolSave");
			window.localStorage.removeItem("ProtocolSave");
		}
		$scope.redirectTab = function (tab){
			$scope.homeShow = false;
			$scope.protocolsPage = false;
			$scope.details = false;
			$scope.createPage = false;
			$scope.createCasePage = false;
			$scope.posSave = false;
			$scope[tab] = true;
			window.scrollTo(0,0);
		};
		
		$scope.ResetCompany = function(){
			if(confirm("You sure want to change your company?")){
				window.localStorage.clear();
				$window.location.href = "index.html";
			}
		};
		$scope.StartLoading = function(){
			$('body').addClass("load-plugin");
		};
		$scope.EndLoading = function(){
			$('body').removeClass("load-plugin");
		};
		//Parse to $scope type object the file element find when a input type=file is onchange
		$scope.files = {};
		$scope.SelectFile = function(field,element){
			$scope.files[field] = element.files[0];
		};
		
		// convert base64/URLEncoded data component to raw binary data held in a string
		$scope.dataURItoBlob = function (dataURI) {
			
			var byteString;
			if (dataURI.split(',')[0].indexOf('base64') >= 0)
				byteString = atob(dataURI.split(',')[1]);
			else
				byteString = unescape(dataURI.split(',')[1]);

			// separate out the mime component
			var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

			// write the bytes of the string to a typed array
			var ia = new Uint8Array(byteString.length);
			for (var i = 0; i < byteString.length; i++) {
				ia[i] = byteString.charCodeAt(i);
			}

			return new Blob([ia], {type:mimeString});
		};
		//Make user interact tutorial function
		$scope.Tutorial = function(){
			window.localStorage.setItem("hasTutorial", true);
			var textPopover = "Nesta Tab você pode inserir um novo relato.";
			var htmlPopover = "<p class='almost-black-color'>"+textPopover+"</p><p><button onclick='jQuery(\"#CreateTab\").popover(\"hide\")' class='btn btn-default btn-success'>Entendi</button></p>";
			jQuery('#CreateTab').popover({title: "Faça seu Relato",html: true, content: htmlPopover, trigger: "manual", placement:"top"}).popover('show').on('hidden.bs.popover', function(){
				var textPopover = "Nesta Tab pesquisar ou/e acompanhar seu(s) relato(s).";
				var htmlPopover = "<p class='almost-black-color'>"+textPopover+"</p><p><button onclick='jQuery(\"#SeachTab\").popover(\"hide\")' class='btn btn-default btn-success'>Entendi</button></p>";
				jQuery('#SeachTab').popover({title: "Acompanhe seu Relato",html: true, content: htmlPopover, trigger: "manual", placement:"top"}).popover('show').on('hidden.bs.popover', function(){
					var textPopover = "Nesta Tab você pode fazer o download do código de conduta.";
					var htmlPopover = "<p class='almost-black-color'>"+textPopover+"</p><p><button onclick='jQuery(\"#CodTab\").popover(\"hide\")' class='btn btn-default btn-success'>Entendi</button></p>";
					jQuery('#CodTab').popover({title: "Código de Conduta",html: true, content: htmlPopover, trigger: "manual", placement:"top"}).popover('show').on('hidden.bs.popover', function(){
						var textPopover = "Clique nas bandeiras para alterar o idioma.";
						var htmlPopover = "<p class='almost-black-color'>"+textPopover+"</p><p><button onclick='jQuery(\"#LangPop\").popover(\"hide\")' class='btn btn-default btn-success'>Entendi</button></p>";
						jQuery('#LangPop').popover({title: "Idiomas",html: true, content: htmlPopover, trigger: "manual", placement:"bottom"}).popover('show').on('hidden.bs.popover', function(){
							var textPopover = "Clique neste icone para trocar de empresa. Cuidado seus protocolos salvos serão perdidos!";
							var htmlPopover = "<p class='almost-black-color'>"+textPopover+"</p><p><button onclick='jQuery(\"#RstC\").popover(\"hide\")' class='btn btn-default btn-success'>Entendi</button></p>";
							jQuery('#RstC').popover({title: "Troca de Empresa",html: true, content: htmlPopover, trigger: "manual", placement:"top"}).popover('show').on('hidden.bs.popover', function(){
								var textPopover = "Clique neste icone para rever o tutorial.";
								var htmlPopover = "<p class='almost-black-color'>"+textPopover+"</p><p><button onclick='jQuery(\"#TutRst\").popover(\"hide\")' class='btn btn-default btn-success'>Entendi</button></p>";
								jQuery('#TutRst').popover({title: "Tutorial",html: true, content: htmlPopover, trigger: "manual", placement:"top"}).popover('show');
							});
						});
					});
				});
			});
		};
		
	}]);
})();