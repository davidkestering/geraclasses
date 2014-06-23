<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");

// VERIFICA AS PERMISSÕES
$bPermissaoVisualizar = $_SESSION['oLoginAdm']->verificaPermissao("Usuario","AlterarSenha",BANCO);
$oFachadaSeguranca = new FachadaSegurancaBD();
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Usuario","AlterarSenha",BANCO);
if(!$bPermissaoVisualizar) {
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar a própria senha, porém não possui permissão para isso!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a área de alteração de senha!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/ADM.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
	require_once(PATH."/painel/includes/head.php"); //CARREGA O ARQUIVO COM O CABEÇALHO DO SITE
?>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->

</head>

<body <?php echo (isset($sBody) && $sBody != "") ? $sBody : "" ?>>

<?php
	require_once(PATH."/painel/includes/topo.php"); //CARREGA O ARQUIVO COM O TOPO DO SITE
	require_once(PATH."/painel/includes/barra.php"); //CARREGA O ARQUIVO COM A BARRA DO SITE
	require_once(PATH."/painel/includes/menu_lateral.php"); //CARREGA O ARQUIVO COM O MENU LATERAL DO SITE
?>

<div id="CONTEUDO">
<!-- InstanceBeginEditable name="EDITAVEL" -->
    <div id="MIGALHA"><a href="/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <strong>Alterar senha </strong></div>
    <h1>Alterar senha </h1>
    <?php
    	if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
    ?>
    <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsg']?></div>
    <?php
    		$_SESSION['sMsg'] = "";
    	}//if($_SESSION['sMsg']){
    ?> 
	<span id="msg"></span>

    <form action="processa_altera_senha.php" method="post" name="formAlteraSenha" onSubmit="JavaScript:return validaForm(this,'#000000','#FF3300')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
            	<th align="left" colspan="2">Dados do Usuário:</th>
            </tr>
            <tr>
                <th width="13%" >Nome:</th>
                <td width="87%" ><?php echo $_SESSION['oLoginAdm']->oUsuario->getNmUsuario()?></td>
            </tr>
            <tr>
                <th >Login:</th>
                <td ><?php echo $_SESSION['oLoginAdm']->oUsuario->getLogin()?></td>
            </tr>
            <tr>
                <th ><label id="fSenha">Senha atual:</label></th>
                <td><input name="fSenha" type="password"  size="15" maxlength="20" lang="vazio"></td>
            </tr>
            <tr>
                <th ><label id="fNovaSenhaCampo">Senha nova:</label></th>
                <td><input type="password" name="fNovaSenhaCampo" id="fNovaSenha" lang="vazio" value=""></td>
            </tr>
            <tr>
                <th class="FormCampoNome"><label id="fConfirmaNovaSenha">Confirme a senha:</label></th>
                <td><input name="fConfirmaNovaSenha" type="password"  size="15" maxlength="20" lang="fNovaSenha" id="igual"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="envia" value="Alterar"><input type="hidden" name="fIdUsuario" value="<?php echo ($_SESSION['oLoginAdm']->oUsuario) ? ($_SESSION['oLoginAdm']->oUsuario->getId()) : ""?>"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
        </table>
    </form>
<?php
if(isset($_SESSION['oUsuario']))
	unset($_SESSION['oUsuario']);
unset($_POST);
?>
  <!-- InstanceEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
<!-- InstanceEnd --></html>
