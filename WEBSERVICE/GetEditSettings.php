<?php
/*
	Get Seetings for the Complaint page. (e.g: Dropdown values per client, hide show fields by cliente) 
	Response:JSONP
	method:GET
	Receive Params:
	idioma = Language Selected By the User in the App
	cli =  Client ID
*/
require_once("../variavelglobal.php");
require_once("is-app.php");
require_once(DiretorioController."classes.php");
$retorno = array(
	"settings" => array(),
	"error" => false,
	"error_string" => ""
);
$idioma = $_REQUEST['idioma'];
$cli = $_REQUEST['cli'];
if($idioma == 'es'){
	$idioma = 3;
}else if($idioma == 'en'){
	$idioma = 2;
}else{
	$idioma = 1;
}
if(!empty($cli)){
	$classPergunta = new perguntas();
	$classLocais = new locais_clientes();
	$classCargos = new cargos();
	$perguntas = $classPergunta->Pesquisa(0,0," usa = 1 and id_cliente = '{$cli}' ");
	$categorias = $classPergunta->Pesquisa(0,0," usa = 1 and id_cliente = '{$cli}' "," id_categoria asc ",""," id_categoria ", " id_categoria ");
	$locais = $classLocais->Pesquisa(0,0," idioma = {$idioma} and id_cliente = '{$cli}' ");
	$cargos = $classCargos->Pesquisa(0,0," idioma = {$idioma} and id_cliente = '{$cli}' ");
	$classDenuncias = new denuncias();
	$retorno['settings']['protocolo'] = $classDenuncias->GeraProtocolo();
	$ObjAjuda = new ajudas();
	$ArrAjuda = $ObjAjuda->Pesquisa(0,0," modulo = 'denuncias_denunciante' and idioma = ".$idioma);
	$retorno['settings']['conteudo'] = $ArrAjuda[0]['conteudo'];
	$parametros_classe = new parametros();
	
	//Indentificar
	$parametros = $parametros_classe->PorCodTabela(4);
	foreach($parametros as $param){
		$retorno['settings']['indentificar'][$param['argumento']]['value'] = $param['argumento'];
		$retorno['settings']['indentificar'][$param['argumento']]['label'] = $param['descricao1'];
	}
	
	//tipo_denunciante
	$parametros = $parametros_classe->PorCodTabela(11);
	foreach($parametros as $param){
		$retorno['settings']['tipo_denunciante'][$param['argumento']]['value'] = $param['argumento'];
		$retorno['settings']['tipo_denunciante'][$param['argumento']]['label'] = $param['descricao1'];
	}
	
	$retorno['settings']['locais'] = array();
	foreach($locais as $param){
		$retorno['settings']['locais'][$param['id_local']]['value'] = $param['id_local'];
		$retorno['settings']['locais'][$param['id_local']]['label'] = $param['local'];
	}
	
	$retorno['settings']['cargos'] = array();
	foreach($cargos as $param){
		$retorno['settings']['cargos'][$param['id_cargo']]['value'] = $param['id_cargo'];
		$retorno['settings']['cargos'][$param['id_cargo']]['label'] = $param['cargo'];
	}
	
	//categoria
	$parametros = $parametros_classe->PorCodTabela(6);
	foreach($parametros as $param){
		if(recursive_array_search($param['argumento'],$categorias) !== false){
			$retorno['settings']['categorias'][$param['argumento']]['value'] = $param['argumento'];
			$retorno['settings']['categorias'][$param['argumento']]['label'] = $param['descricao1'];
		}
	}
	
	//subCategoria
	$parametros = $parametros_classe->PorCodTabela(7);
	foreach($parametros as $param){
		$temPergunta = recursive_array_search_key($param['argumento'],'id_subcategoria',$perguntas);
		if($temPergunta !== false){
			if($idioma == 2){
				$NomePerguntaIdioma = 'pergunta_en';
			}elseif($idioma == 3){
				$NomePerguntaIdioma = 'pergunta_es';
			}else{
				$NomePerguntaIdioma = 'pergunta_br';
			}
			$retorno['settings']['subcategorias'][$perguntas[$temPergunta]['id_categoria']][$param['argumento']]['value'] = $param['argumento'];
			$retorno['settings']['subcategorias'][$perguntas[$temPergunta]['id_categoria']][$param['argumento']]['label'] = $param['descricao1'];
			$retorno['settings']['subcategorias'][$perguntas[$temPergunta]['id_categoria']][$param['argumento']]['id_pergunta'] = $perguntas[$temPergunta]['id_pergunta'];
			$retorno['settings']['subcategorias'][$perguntas[$temPergunta]['id_categoria']][$param['argumento']]['risco'] = $perguntas[$temPergunta]['risco'];
		}
	}
	$retorno['settings']['status'] = 1;
	$retorno['settings']['canal'] = 5;
}else{
	$retorno['error'] = true;
	$retorno['error_string'] = "Account not found";
}

echo $_GET['callback']. "(" . json_encode($retorno) . ")";
?>