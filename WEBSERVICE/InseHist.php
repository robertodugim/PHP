<?php

/*
	Insert a Note to a Complaint
	Response:JSONP
	method:POST
	Receive Params:
	idioma = Language Selected By the User in the App
	cli =  Client ID
	id_denuncia = Complaint ID
	#Note Data	
	descricao
	anexo = FILE
	anexo2 = FILE
	anexo3 = FILE
	
*/

header('Access-Control-Allow-Origin: *'); 
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
require_once(DiretorioController."controller_save.php");
	$retorno = array(
		"ret" => array(),
		"error" => false,
		"error_string" => ""
	);
if(empty($_POST['descricao']) || empty($_SESSION['idioma_usuario']) || empty($_POST['id_denuncia']) || empty($_POST['cli'])){
	$retorno['error'] = true;
	$retorno['error_string'] = "Campos Obrigatórios sem preenchimento.";
}else{
	$hist = new historicos();
	$hist->id_denuncia = "'".$_POST['id_denuncia']."'";
	$hist->descricao = "'".$_POST['descricao']."'";
	if(!empty($_FILES['anexo']['name'])){
		$hist->anexo = $_FILES['anexo']['name'];
		$path_parts = pathinfo($hist->anexo);
		if(empty($path_parts['extension'])){
			$hist->anexo .= ".". TypeImageMb($_FILES["anexo"]['tmp_name']);
		}
		$hist->anexo = "'".$hist->anexo."'";
	}
	if(!empty($_FILES['anexo2']['name'])){
		$hist->anexo_2 = $_FILES['anexo2']['name'];
		$path_parts = pathinfo($hist->anexo_2);
		if(empty($path_parts['extension'])){
			$hist->anexo_2 .= ".". TypeImageMb($_FILES["anexo2"]['tmp_name']);
		}
	}
	if(!empty($_FILES['anexo3']['name'])){
		$hist->anexo_3 = $_FILES['anexo3']['name'];
		$path_parts = pathinfo($hist->anexo_3);
		if(empty($path_parts['extension'])){
			$hist->anexo_3 .= ".". TypeImageMb($_FILES["anexo3"]['tmp_name']);
		}
	}
	$hist->denunciante = "1";
	$hist->oculta_kpmg = "2";
	$hist->usuario_criador = "9999";
	$hist->usuario_alteracao = "9999";
	$hist->data_criacao = "'".date('Y-m-d H:i:s')."'";
	$hist->data_alteracao = "'".date('Y-m-d H:i:s')."'";
	$hist->Insere();
	if(!empty($_FILES['anexo']['name'])){
		$path_parts = pathinfo($_FILES["anexo"]['name']);
		$chave = $hist->chave;
		if(empty($path_parts['extension'])){
			$path_parts['extension'] = TypeImageMb($_FILES["anexo"]['tmp_name']);
		}
		$nomeArquivo = "historicos_anexo_".$hist->$chave.".".$path_parts['extension'];
		move_uploaded_file($_FILES["anexo"]['tmp_name'],DiretorioUpload.$nomeArquivo);
	}
	if(!empty($_FILES['anexo2']['name'])){
		$path_parts = pathinfo($_FILES["anexo2"]['name']);
		$chave = $hist->chave;
		if(empty($path_parts['extension'])){
			$path_parts['extension'] = TypeImageMb($_FILES["anexo2"]['tmp_name']);
		}
		$nomeArquivo = "historicos_anexo_2_".$hist->$chave.".".$path_parts['extension'];
		move_uploaded_file($_FILES["anexo2"]['tmp_name'],DiretorioUpload.$nomeArquivo);
	}
	if(!empty($_FILES['anexo3']['name'])){
		$path_parts = pathinfo($_FILES["anexo3"]['name']);
		$chave = $hist->chave;
		$nomeArquivo = "historicos_anexo_3_".$hist->$chave.".".$path_parts['extension'];
		if(empty($path_parts['extension'])){
			$path_parts['extension'] = TypeImageMb($_FILES["anexo3"]['tmp_name']);
		}
		move_uploaded_file($_FILES["anexo3"]['tmp_name'],DiretorioUpload.$nomeArquivo);
	}
}

echo json_encode($retorno);

?>