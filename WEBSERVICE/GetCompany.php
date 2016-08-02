<?php
/*
	Get and manage the data of a client. 
	Response:JSONP
	method:GET
	Receive Params:
	urlc = Keyword
*/

//This is PHP where are all the declared constants of the custom framework 
require_once("../variavelglobal.php");
//This Check if is a valid App
require_once("is-app.php");
//This is like a autoloader for classes
require_once(DiretorioController."classes.php");

$retorno = array(
	"cli" => array(),
	"error" => false,
	"error_string" => ""
);
if(!empty($_REQUEST['urlc'])){
	$cliente = new clientes();
	$cliente = $cliente->Pesquisa(0,0," url = '{$_REQUEST['urlc']}' ");
	
	if(!empty($cliente[0]['id_cliente'])){
		$retorno['cli'] = $cliente[0];
		$languages = array( 1 => "br","en","es");
		
		$classe = new textos_site_clientes();
		$classe2 = new avisos_clientes();

		$id_reg = RelatIdAnexoEncripted($cliente[0]['id_cliente']);
		foreach($languages as $kl => $lang){

			$cliente[0]['logo_'.$lang] = trim($cliente[0]['logo_'.$lang]);
			if(!empty($cliente[0]['logo_'.$lang])){
				$ext = pathinfo($cliente[0]['logo_'.$lang], PATHINFO_EXTENSION);
				$retorno['cli']['logo_'.$lang] = SiteRedirecionamento."/upload/clientes_logo_{$lang}_{$retorno['cli']['id_cliente']}.{$ext}";
			}else{
				$ext = pathinfo($cliente[0]['logo_br'], PATHINFO_EXTENSION);
				$retorno['cli']['logo_'.$lang] = SiteRedirecionamento."/upload/clientes_logo_br_{$retorno['cli']['id_cliente']}.{$ext}";
			}
			$cliente[0]['dconduta_'.$lang] = trim($cliente[0]['conduta_'.$lang]);
			if(!empty($cliente[0]['dconduta_'.$lang])){
				$retorno['cli']['dconduta_'.$lang] = SiteRedirecionamento."/index.php?action=download&module=clientes&campo=conduta_{$lang}&record={$id_reg}";
			}else{
				$retorno['cli']['dconduta_'.$lang] = "nada";
			}

			$inicial = $classe->Pesquisa(0,0," idioma = $kl and id_cliente = '{$retorno['cli']['id_cliente']}' ");
			$retorno['cli']['texto_'.$lang] = str_ireplace(array("font-size:", "font-family:"),"",str_ireplace("strong","span",$inicial[0]['inicial']));
			
			
			$aviso = $classe2->Pesquisa(0,0," idioma = $kl and id_cliente = {$cliente[0]['id_cliente']} ");
			$retorno['cli']['taviso_'.$lang] = str_ireplace(array("font-size:", "font-family:"),"",str_ireplace("strong","span",str_ireplace("<br><br>","<br>",str_ireplace("\n","<br>",$aviso[0]['texto']))));

			$retorno['cli']['ttaviso_'.$lang] = trim($aviso[0]['titulo']);
			$retorno['cli']['b1aviso_'.$lang] = trim($aviso[0]['botao1']);
			$retorno['cli']['b2aviso_'.$lang] = trim($aviso[0]['botao2']);
		}
		
	}else{
		$retorno['error'] = true;
		$retorno['error_string'] = "Cliente não encontrado";
	}
}else{
	$retorno['error'] = true;
	$retorno['error_string'] = "O campo está vazio";
}

echo $_GET['callback']. "(" . json_encode($retorno) . ")";
?>