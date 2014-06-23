<?php 
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

$oFachadaSeguranca = new FachadaSegurancaBD();

// VERIFICA AS PERMISSÕES
$nIdTipoTransacaoPrincipal = $_SESSION['oLoginAdm']->recuperaTipoTransacaoPorDescricaoCategoria("Permissao","Alterar",BANCO);
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Permissao","Alterar",BANCO);
$nIdTipoTransacaoPrincipal = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Permissao","Alterar",BANCO);

$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
if(is_object($oGrupoUsuarioAuxiliar))
	$sGrupoUsuarioAuxiliar = $oGrupoUsuarioAuxiliar->getNmGrupoUsuario();

if(!$bPermissao){
	// TRANSACAO
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as permissões na gerência de grupos de usuários do grupo ".$sGrupoUsuarioAuxiliar.", porém não possui permissão para isso!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}//if(!$bPermissao){

$bResultado = true;
if(isset($_POST['fIdTipoTransacao']) && count($_POST['fIdTipoTransacao']) > 0){
	$vIdTipoTransacao = $_POST['fIdTipoTransacao'];
	
	//DESATIVANDO TODAS AS PERMISSOES
	$oFachadaSeguranca->desativaPermissaoPorGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." iniciou alteração de permissões para o Grupo de Usuários ".$sGrupoUsuarioAuxiliar." o que ocasiona uma remoção inicial de todas as permissões deste grupo!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
	
	foreach($vIdTipoTransacao as $nIdTipoTransacao){
		$sTipoTransacaoAuxiliar = "";
		$sCategoriaTipoTransacaoAuxiliar = "";
		$oTipoTransacaoAuxiliar = $oFachadaSeguranca->recuperaTipoTransacao($nIdTipoTransacao,BANCO);
		if(is_object($oTipoTransacaoAuxiliar)){
			$sTipoTransacaoAuxiliar = $oTipoTransacaoAuxiliar->getTransacao();
			$oCategoriaTipoTransacaoAuxiliar = $oFachadaSeguranca->recuperaCategoriaTipoTransacao($oTipoTransacaoAuxiliar->getIdCategoriaTipoTransacao(),BANCO);
			if(is_object($oCategoriaTipoTransacaoAuxiliar))
				$sCategoriaTipoTransacaoAuxiliar = $oCategoriaTipoTransacaoAuxiliar->getDescricao();

			$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." habilitou a permissão de ".$sTipoTransacaoAuxiliar." na seção ".$sCategoriaTipoTransacaoAuxiliar." para o Grupo de Usuários ".$sGrupoUsuarioAuxiliar;
			$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
		}
		
		$vWherePermissao = array("id_tipo_transacao = ".$nIdTipoTransacao,"id_grupo_usuario = ".$_POST['fIdGrupoUsuario']);
		$voPermissaoAuxiliar = $oFachadaSeguranca->recuperaTodosPermissao(BANCO,$vWherePermissao);
		if(count($voPermissaoAuxiliar) > 0){
			foreach($voPermissaoAuxiliar as $oPermissaoAuxiliar){
				if(is_object($oPermissaoAuxiliar)){
					$oPermissaoAuxiliar->setPublicado(1);
					$oPermissaoAuxiliar->setAtivo(1);
					$bResultado &= $oFachadaSeguranca->alteraPermissao($oPermissaoAuxiliar,$oTransacao,BANCO);
				}//if(is_object($oPermissaoAuxiliar)){
			}//foreach($voPermissaoAuxiliar as $oPermissaoAuxiliar){
		}else{
			$oPermissao = $oFachadaSeguranca->inicializaPermissao($nIdTipoTransacao,$_POST['fIdGrupoUsuario'],date("Y-m-d H:i:s"),1,1);
			$bResultado &= $oFachadaSeguranca->inserePermissao($oPermissao,$oTransacao,BANCO);
		}//if(count($voPermissaoAuxiliar) > 0){
	}
}else{
	$oFachadaSeguranca->desativaPermissaoPorGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." iniciou alteração de permissões para o Grupo de Usuários ".$sGrupoUsuarioAuxiliar.", entretanto não foram encontradas novas permissões para serem adicionadas, por isso, todas as permissões deste grupo estão desativadas!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}

if (!$bResultado){
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as permissões para o Grupo de Usuários ".$sGrupoUsuarioAuxiliar.", porém ocorreu um erro na alteração!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	
	$_SESSION['sMsg'] = "Não foi possível alterar a permissão!";
	$sHeader = "insere_altera.php?bErro=1&nIdGrupoUsuario=".$_POST['fIdGrupoUsuario'];
} else {
	$_SESSION['sMsg'] = "Permissão alterada com sucesso!";
	$sHeader = "insere_altera.php?bErro=0&nIdGrupoUsuario=".$_POST['fIdGrupoUsuario'];
}//if (!$bResultado){

header("Location:$sHeader");
?>