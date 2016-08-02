<?php
/*
	Insert a Note to a Complaint
	Response:JSONP
	method:GET
	Receive Params:
	idioma = Language Selected By the User in the App
	cli =  Client ID
	id_denuncia = Complaint ID
	
*/
require_once("../variavelglobal.php");
require_once("is-app.php");
require_once(DiretorioController."classes.php");
require_once(DiretorioController."controller_traducoes.php");
$_SESSION['estancia'] = 'etica';
$idioma = $_REQUEST['idioma'];
if($idioma == 'es'){
	$idioma = 3;
}else if($idioma == 'en'){
	$idioma = 2;
}else{
	$idioma = 1;
}
$_SESSION['idioma_usuario'] = $idioma;
define('MODULO','historicos');
define('ACAO','index_emp');
require_once(DiretorioController."controller_layout.php");
require_once(DiretorioController."controller_index.php");
	$retorno = array(
		"ret" => array(),
		"error" => false,
		"error_string" => ""
	);
class historicos_mobile extends index
{
	var $retornoM = array();
	function XtplHtml(){
	}
	function EndHtml(){
	}
	function GetMetadata(){
		include_once(DiretorioModules.$this->modulo."/metadata/indexview_emp.php");
		$this->metadata = $metadata;
	}
	function Busca(){
	}
	function Lista(){
		$this->ListaRegistros();
	}
	function Variaveis(){
		$traducoes = new traducoes();
		$denuncia = new denuncias();
		$denuncia->id_denuncia = ($_REQUEST['id_denuncia']);
		$denuncia->Seleciona();
	}
	function Where(){
		$where = array();
		$_REQUEST['id_denuncia'] = ($_REQUEST['id_denuncia']);
		$where[] = " id_denuncia = {$_REQUEST['id_denuncia']} ";
		$where[] = " denunciante = 1 ";
		$this->condicao = implode(" and ",$where);
	}
	function ListaRegistros(){
		$modulo = new $this->modulo();
		$html = "";
		foreach($this->registros as $registro){
			$id_registros = $registro[$modulo->chave];
			$this->id_registro = $id_registros;
			foreach($this->metadata['listar']['campos'] as $campo => $variaveis){
				$this->retornoM[$id_registros][$campo] = trim($this->FormataValor($registro[$campo],$variaveis,$campo));
				if($variaveis['tipo'] == 'arquivo'){
					$this->retornoM[$id_registros][$campo] = str_ireplace('index.php',SiteRedirecionamento."/index.php",$this->retornoM[$id_registros][$campo]);
					$explodeField = explode('-',str_ireplace(array('<a href=\'','\'>','</a>'),array('','-',''),$this->retornoM[$id_registros][$campo]));
					$this->retornoM[$id_registros][$campo] = trim($explodeField[1]);
					$this->retornoM[$id_registros]["link_".$campo] = trim($explodeField[0])."&m=An423";
				}
			}
		}
	}
}	
$mobile_save = new historicos_mobile();
$retorno['ret'] = $mobile_save->retornoM;
// $retorno['error'] = true;
// $retorno['error_string'] = "Field is empty";
echo $_GET['callback']. "(" . json_encode($retorno) . ")";

?>