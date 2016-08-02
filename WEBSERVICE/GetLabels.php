<?php
/*
	Get all labels by language
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
	"labels" => array(),
	"error" => false,
	"error_string" => ""
);
$cli = $_REQUEST['cli'];
$idioma = $_REQUEST['idioma'];
if($idioma == 'es'){
	$idioma = 3;
}else if($idioma == 'en'){
	$idioma = 2;
}else{
	$idioma = 1;
}
$traducoes = new traducoes();
$labelsMobile = array(
'lbl_faca_relato',
'lbl_acompanha_relato',
'lbl_protocolo',
'lbl_status',
'lbl_canal',
'lbl_id_local',
'lbl_nome_denunciante',
'lbl_email_denunciante',
'lbl_tipo_denunciante',
'lbl_id_cargo',
'lbl_telefone',
'lbl_nr_documento',
'lbl_categoria',
'lbl_subcategoria',
'lbl_descricao',
'lbl_msg_prot_nao_encontrado',
'lbl_data_criacao',
'lbl_btn_acessar',
'lbl_acompanhar_relato',
'lbl_indentificar',
'lbl_anexo',
'lbl_anexo2',
'lbl_anexo3',
'lbl_selecione_combo',
'lbl_aviso_leia',
'lbl_relato_sucesso',
'lbl_protocolo_gerado_relato',
'lbl_protocolo_acompanhar',
'lbl_historico',
'lbl_data_alteracao',
'lbl_botao_criar_historico',
'lbl_botao_salvar',
'lbl_relato'
);
foreach($labelsMobile as $label){
	$retorno["labels"][$label] = $traducoes->SelecionaNoSection($label,$idioma);
}
$classe = new textos_site_clientes();
$fim = $classe->Pesquisa(0,0," idioma = {$idioma} and id_cliente = '{$cli}' ");
$retorno["labels"]['lbl_possave_fim'] = trim($fim[0]['fim']);

echo $_GET['callback']. "(" . json_encode($retorno) . ")";
?>