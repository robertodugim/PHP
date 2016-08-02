<?php

/*
	Insert Complaint
	Response:JSONP
	method:POST
	Receive Params:
	idioma = Language Selected By the User in the App
	cli =  Client ID
	#Complaint Data	
	nome = Name
	email
	tipo_denunciante = User Type
	id_cargo= Job ID
	categoria= Category
	subcategoria= SubCategory
	descricao = description
	protocolo = protocol
	pergunta_id = Question ID
	risco = Risk
	status
	canal = Channel
	anexo = FILE
	anexo2 = FILE
	anexo3 = FILE
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
require_once(DiretorioController."controller_save.php");
	$retorno = array(
		"ret" => array(),
		"error" => false,
		"error_string" => ""
	);
class mobile_save extends save
{
	var $classDenuncia;
	var $classCliente;
	var $modulo = 'denuncias';
	var $mandouEmail = 2;
	function BeforeSave(){
		$_SESSION['id_usuario'] = 9999;
		$_POST['id_cliente'] = $_POST['cli'];
		$classDenuncias = new denuncias();
		$_POST['protocolo'] = $classDenuncias->GeraProtocolo();
		$_POST['busca'][$this->modulo] = $_POST;
		$this->classCliente = new clientes();
		$this->classCliente->id_cliente = $_POST['busca'][$this->modulo]['id_cliente'];
		$this->classCliente->Seleciona();
		$descricaoMinuscula = RetornaMinusculo($_POST['busca'][$this->modulo]['descricao']);
		if(!empty($this->classCliente->palavras_chave)){
			$obs_chaves = "<b>{$_SESSION['BotoesLabel']['lbl_msg_denuncia_descr_palavras']}:</b><br>";
			$temPalavras = 0;
			$palavras_split = explode(",",$this->classCliente->palavras_chave);
			foreach($palavras_split as $chave){
				$chave = RetornaMinusculo($chave);
				if(strpos($descricaoMinuscula,$chave) !== false){
					$obs_chaves .= "$chave<br>";
					$temPalavras = 1;
				}
			}
		}
		$obs_usuarios = "<b>{$_SESSION['BotoesLabel']['lbl_msg_denuncia_descr_nomes']}:</b><br>";
		$temNomes = 0;
		$usuariosCliente = new usuarios();
		$usuarios = $usuariosCliente->Pesquisa(0,0," id_cliente = '{$this->classCliente->id_cliente}' ");
		foreach($usuarios as $usuario){
			$needle = RetornaMinusculo(trim($usuario['nome']));
			if(strpos($descricaoMinuscula,$needle) !== false){
				$obs_usuarios .= "{$usuario['nome']}<br>";
				$temNomes = 1;
			}
			$needle = RetornaMinusculo(trim($usuario['apelido']));
			if(strpos($descricaoMinuscula,$needle) !== false){
				$obs_usuarios .= "{$usuario['nome']}<br>";
				$temNomes = 1;
			}
		}
		$_POST['busca'][$this->modulo]['obs_palavras_chave'] = '';
		if($temPalavras > 0){
			$_POST['busca'][$this->modulo]['obs_palavras_chave'] .= $obs_chaves;
		}
		if($temNomes > 0){
			$_POST['busca'][$this->modulo]['obs_palavras_chave'] .= $obs_usuarios;
		}
		if(!empty($_POST['busca'][$this->modulo]['risco'])){
		
		}
	}
	function AfterSave(){
		$this->classDenuncia = new denuncias();
		$this->classDenuncia->id_denuncia = $this->idPrincipal;
		$this->classDenuncia->Seleciona();
		unset($_SESSION['id_usuario']);
	}
	function MandaEmail(){
		$protocolo = substr($this->classDenuncia->protocolo,0,-2);
		$razao_social = $this->classCliente->razao_social;
		$assinatura = $this->classCliente->assinatura;
		$traducoes = new traducoes;
		$lbl1 = $traducoes->SelecionaLabel("lbl_relato_registrado");
		$lbl2 = $traducoes->SelecionaLabel("lbl_acompanhar_relato_empresa");
		if(empty($_POST['busca'][$this->modulo][$this->primary]) && !empty($_POST['busca'][$this->modulo]['email'])){
			$classEmail = new envia_emails;
			$classEmail->remetente = 'noreply@xxx.com.br';
			$classEmail->NomeRemetente = 'XXX';
			$classEmail->destinatarios = $this->classDenuncia->email;
			$classEmail->assunto = $_SESSION['BotoesLabel']['lbl_msg_denuncia_aberta'].' - '.$protocolo.' - '.$razao_social;
			$classEmail->mensagem_texto = "{$_SESSION['BotoesLabel']['lbl_msg_denuncia_prezado']} <br><br><b>{$_SESSION['BotoesLabel']['lbl_email_automatico']}</b><br> {$lbl1} {$protocolo}. <br>";
			$classEmail->mensagem_texto .= "{$lbl2} <br>";
			$classEmail->assinatura = $assinatura;
			$classEmail->EnviaEmail();
			$this->mandouEmail = 1;
		}
	}
	function Redireciona(){
		if(!empty($_FILES['anexo']['name']) || !empty($_FILES['anexo2']['name']) || !empty($_FILES['anexo3']['name'])){
			$this->AnexoHist();
		}
	}
	function AnexoHist(){
		$hist = new historicos();
		$hist->id_denuncia = "'".$this->idPrincipal."'";
		$hist->descricao = "'.'";
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
		$hist->oculta = "2";
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
	function SalvaIndividual(){
		$this->PopularZeros();
		$Recebe = $this->Recebe;
		
		foreach($Recebe as $campo => $valor){
			$this->classeAtual->$campo = FormataCampoBanco($valor,$this->TypesFields[$campo]);
		}
		
		if(!empty($Recebe[$this->classeAtual->chave])){
			$this->classeAtual->usuario_alteracao = $_SESSION['id_usuario'];
			$this->classeAtual->data_alteracao = FormataCampoBanco($this->dataAgora,'date');
			$this->classeAtual->Altera();
		}else{
			$this->classeAtual->usuario_criador = $_SESSION['id_usuario'];
			$this->classeAtual->usuario_alteracao = $_SESSION['id_usuario'];
			$this->classeAtual->data_criacao = FormataCampoBanco($this->dataAgora,'date');
			$this->classeAtual->data_alteracao = FormataCampoBanco($this->dataAgora,'date');
			$this->classeAtual->Insere();
		}
	
		$primary = $this->primary;
		if(!empty($this->classeAtual->$primary)){
			$this->idPrincipal = $this->classeAtual->$primary;
		}
	}
}	
$mobile_save = new mobile_save();
$retorno['ret']['protocolo'] = trim($mobile_save->classDenuncia->protocolo);

echo json_encode($retorno);

?>