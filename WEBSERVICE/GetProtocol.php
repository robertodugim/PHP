<?php
/*
	Get a Complaint by Protocol
	Response:JSONP
	method:GET
	Receive Params:
	idioma = Language Selected By the User in the App
	cli =  Client ID
	type = Page Type
	number = protocol
*/
require_once("../variavelglobal.php");
require_once("is-app.php");
require_once(DiretorioController."classes.php");
$retorno = array(
	"protocol" => array(),
	"error" => false,
	"error_string" => ""
);
$idioma = $_REQUEST['idioma'];
$type = $_REQUEST['type'];
if($idioma == 'es'){
	$idioma = 3;
}else if($idioma == 'en'){
	$idioma = 2;
}else{
	$idioma = 1;
}
// file_put_contents('ios.txt',serialize($_SERVER));
if(!empty($_REQUEST['number'])){
	$denuncias = new denuncias();
	$denuncias = $denuncias->Pesquisa(0,0," protocolo = '{$_REQUEST['number']}' and id_cliente = '{$_REQUEST['cli']}' ");
	
	if(!empty($denuncias[0]['id_denuncia'])){
		$retorno["protocol"] = $denuncias[0];
		$parametros_classe = new parametros();
		$traducoes = new traducoes();
		$classLocais = new locais_clientes();
		$classCargos = new cargos();
		
		$retorno["protocol"]['categoria'] = $parametros_classe->DescricaoPorCodTabelaeArgumento(6,$retorno["protocol"]['categoria']);
		$retorno["protocol"]['subcategoria'] = $parametros_classe->DescricaoPorCodTabelaeArgumento(7,$retorno["protocol"]['subcategoria']);
		$retorno["protocol"]['canal'] = $parametros_classe->DescricaoPorCodTabelaeArgumento(9,$retorno["protocol"]['canal']);
		if($retorno["protocol"]['tipo_denunciante']){
			$retorno["protocol"]['tipo_denunciante'] = $parametros_classe->DescricaoPorCodTabelaeArgumento(11,$retorno["protocol"]['tipo_denunciante']);
		}else{
			$retorno["protocol"]['tipo_denunciante'] = '';
		}
		$retorno["protocol"]['data_criacao'] = date('d/m/Y H:i:s',strtotime(str_ireplace('.000','',$retorno["protocol"]['data_criacao'])));
		$retorno["protocol"]['data_alteracao'] = date('d/m/Y H:i:s',strtotime(str_ireplace('.000','',$retorno["protocol"]['data_alteracao'])));
		$retorno["protocol"]['data_cliente'] = date('d/m/Y H:i:s',strtotime(str_ireplace('.000','',$retorno["protocol"]['data_cliente'])));
		
		if($retorno["protocol"]['status'] < 8){
			$retorno["protocol"]['status'] = $traducoes->SelecionaNoSection('lbl_status_denunciante_anda',$idioma);
		}else{
			$retorno["protocol"]['status'] = $traducoes->SelecionaNoSection('lbl_status_denunciante_concluido',$idioma);
		}
		
		$locais = array();
		$cargos = array();
		if($retorno["protocol"]['id_local']){
			$locais = $classLocais->Pesquisa(0,0," id_local = '{$retorno["protocol"]['id_local']}' ");
		}
		if($retorno["protocol"]['id_cargo']){
			$cargos = $classCargos->Pesquisa(0,0," id_cargo = '{$retorno["protocol"]['id_cargo']}' ");
		}
		$retorno["protocol"]['id_cargo'] = $cargos[0]['cargo'];
		$retorno["protocol"]['id_local'] = $locais[0]['local'];
		foreach($retorno["protocol"] as $campo => $valor){
			$retorno["protocol"][$campo] = trim($valor);
		}
		
		if($type == 'lista'){
			foreach($retorno["protocol"] as $campo => $valor){
				if($campo != 'categoria' 
				&& $campo != 'subcategoria' 
				&& $campo != 'status' 
				&& $campo != 'data_criacao'
				&& $campo != 'protocolo'
				&& $campo != 'id_denuncia'){
					unset($retorno["protocol"][$campo]);
				}
			}
		}
	}else{
		$retorno['error'] = true;
		$retorno['error_string'] = "Protocol not Found";
	}
}else{
	$retorno['error'] = true;
	$retorno['error_string'] = "Protocol Number is empty";
}

echo $_GET['callback']. "(" . json_encode($retorno) . ")";
?>