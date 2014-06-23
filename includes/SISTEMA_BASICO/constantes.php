<?php
//CONTROLE DE SISTEMA
ob_start();
ignore_user_abort(true);
error_reporting(E_ALL); //mostrará todos os erros exceto os Notices
ini_set('display_errors', true); //Habilita a exibição de erros
ini_set("memory_limit","512M"); //Altera a memória limite para post e gets
ini_set('post_max_size', '512M'); //Altera a memória limite para envio de posts
ini_set('upload_max_filesize', '512M'); //Altera o tamanho limite para envio de arquivos
ini_set("max_execution_time", "900000"); //Altera o tempo de execucao de scripts
date_default_timezone_set('America/Belem');

//CONTROLE DE SESSAO
//PARA IMPEDIR MULTIPLOS ACESSOS, DESTROI A SESSAO DO USUARIO LOGADO CASO FECHE O NAVEGADOR OU MUDE DE PAGINA
ini_set("session.use_only_cookies","1");
ini_set("session.use_trans_sid","0");

//CONTROLE DE CACHE
// Data no passado
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// Sempre modificado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");

#CONSTANTES BANCO
//DADOS BASICOS DO SISTEMA
define("PATH",dirname(__FILE__)."/");
define("BANCO","LOCAL");
define("ACESSO_NEGADO","Você não tem permissão para acessar esta área.");
define("IP_USUARIO",$_SERVER['REMOTE_ADDR']);
define("IP_SERVIDOR",((isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] != "") ? $_SERVER['SERVER_ADDR'] : ((isset($_SERVER['LOCAL_ADDR']) && $_SERVER['LOCAL_ADDR'] != "") ? $_SERVER['LOCAL_ADDR'] : "SEM IP")));
define("DATAHORA",date("Y-m-d H:i:s"));

//IDS PADROES DO SISTEMA
define("ID_USUARIO_SISTEMA",0);
define("GRUPO_ADMINISTRADOR",1);

?>