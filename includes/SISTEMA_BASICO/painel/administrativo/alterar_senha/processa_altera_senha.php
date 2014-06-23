<?php 
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");
require_once(PATH."/classes/Validacao.class.php");

$oFachadaSeguranca = new FachadaSegurancaBD();
$oValidacao = new Validacao();

// NOME DO CAMPO DO FORULARIO QUE DEVE SER GRAVADO NA TRANSACAO
$nIdTipoTransacao = $_SESSION['oLoginAdm']->recuperaTipoTransacaoPorDescricaoCategoria("Usuario","AlterarSenha",BANCO);
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Usuario","AlterarSenha",BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Usuario","AlterarSenha",BANCO);

if(!$bPermissao){
	//TRANSACAO
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar sua própria senha, porém não possui permissão para isso! Segue abaixo as informações:<br />";
	if ($oFachadaSeguranca->presenteUsuario($_POST['fIdUsuario'],BANCO)){
		// TRANSACAO
		$oUsuarioAuxiliarAcesso = $oFachadaSeguranca->recuperaUsuario($_POST['fIdUsuario'],BANCO);
		if(is_object($oUsuarioAuxiliarAcesso)){
			$sObjetoAcesso .= "VERIFICAR: Tentativa de alteração de senha atual: ".$oUsuarioAuxiliarAcesso->getSenha()." para a nova senha: ".$_POST['fNovaSenhaCampo']."<br />";
		}else{
			$sObjetoAcesso .= "VERIFICAR ERRO: Id do usuário não encontrado para alteração de senha. Id: ".$_POST['fIdUsuario']."<br />";
		}//if(is_object($oUsuarioAuxiliarAcesso)){
	}else{
		$sObjetoAcesso .= "VERIFICAR: Não houve envio do id do usuário para alteração de senha!???<br />";
	}//if ($oFachadaSeguranca->presenteUsuario($nIdUsuario,BANCO)){
	
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}//if(!$bPermissao){

$sHeader = "alterar_senha.php?bErro=1";

$oUsuario = $oFachadaSeguranca->recuperaUsuario($_SESSION['oLoginAdm']->getIdUsuario(),BANCO);
if(is_object($oUsuario)){
	// TRANSACAO
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." alterou sua senha atual: ".$oUsuario->getSenha()." para nova senha: ".$_POST['fNovaSenhaCampo'];
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	
	if($oUsuario->getSenha() == $_POST['fSenha']) {
		if($_POST['fNovaSenhaCampo'] == $_POST['fConfirmaNovaSenha']){
			$oUsuario->setSenha($_POST['fNovaSenhaCampo']);
			if (!$oFachadaSeguranca->alteraUsuario($oUsuario,$oTransacao,BANCO)){
				$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de senha falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar sua senha atual: ".$oUsuario->getSenha()." para nova senha: ".$_POST['fNovaSenhaCampo'].", porém houve erro na alteração.";
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
				$_SESSION['sMsg'] = "Não foi possível alterar a senha!";
			} else {
				$_SESSION['sMsg'] = "Senha alterada com sucesso!";
				$sHeader = "alterar_senha.php?bErro=0";
				session_unregister('oUsuario');
				$_SESSION['oUsuario'] = "";
				unset($_SESSION['oUsuario']);
				unset($_POST);
			}//if ($oFachadaSeguranca->insereUsuario($oUsuario))
		}else{
			$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de senha falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar sua senha atual: ".$oUsuario->getSenha()." para nova senha: ".$_POST['fNovaSenhaCampo'].", porém houve erro na alteração. A nova senha informada: ".$_POST['fNovaSenhaCampo']." não confere com a confirmação de senha: ".$_POST['fConfirmaNovaSenha'];
			$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
			$_SESSION['sMsg'] = "Não foi possível alterar a senha. A confirmação de senha não confere com a nova senha informada! Verifique os dados novamente!";
		}//if($_POST['fNovaSenhaCampo'] == $_POST['fConfirmaNovaSenha']){
	} else {
		$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de senha falhou. SENHA ATUAL INVALIDA. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar sua senha atual: ".$oUsuario->getSenha()." para nova senha: ".$_POST['fNovaSenhaCampo'].", porém houve erro na alteração, pois o usuário passou a senha atual inválida. Digitou: ".$_POST['fSenha'];
		$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
		$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
		$_SESSION['sMsg'] = "Senha atual inválida.";
	}//if($oUsuario->getSenha() == $_POST['fSenha']) {
} else {
	$sObjetoAcesso = "VERIFICAR ERRO: Usuário não encontrado na alteração de senha! Id: ".$_SESSION['oLoginAdm']->getIdUsuario()."<br />";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsg'] = "Usuário não encontrado no sistema!";
}
header("Location:$sHeader");
?>