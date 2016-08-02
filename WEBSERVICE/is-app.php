<?php
	if($_SERVER[SERVER_APP_ID] != APP_ID){
		$retorno = array();
		$retorno['error'] = true;
		$retorno['error_string'] = "You have no access to this area or you attempt to hack the system.";
		if($_GET['callback']){
			echo $_GET['callback']. "(" . json_encode($retorno) . ")";
		}else{
			echo json_encode($retorno);
		}
		exit;
	}
?>