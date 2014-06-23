<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

$nIdGrupoUsuario = (isset($_GET['nIdGrupoUsuario']) && $_GET['nIdGrupoUsuario'] != "" && $_GET['nIdGrupoUsuario'] != 0) ? $_GET['nIdGrupoUsuario'] : ((isset($_POST['fIdGrupoUsuario'][0]) && $_POST['fIdGrupoUsuario'][0] != "" && $_POST['fIdGrupoUsuario'][0] != 0) ? $_POST['fIdGrupoUsuario'][0] : "");
$sOP = ($nIdGrupoUsuario) ? "Alterar" : "Cadastrar"; 

$oFachadaSeguranca = new FachadaSegurancaBD();

if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
	$oGrupoUsuarioAcesso = $oFachadaSeguranca->recuperaGrupoUsuario($nIdGrupoUsuario,BANCO);
	if(is_object($oGrupoUsuarioAcesso)){
		$sNmGrupoUsuarioAcesso = $oGrupoUsuarioAcesso->getNmGrupoUsuario();
	}//if(is_object($oUsuarioAcesso)){
}//if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {

$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Grupos",$sOP,BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Grupos",$sOP,BANCO);
if(!$bPermissao) {
	if($sOP == "Cadastrar"){
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou cadastrar informações na gerência de grupos de usuários, porém não possui permissão para isso!";
	}else{
		if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
			$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar dados do grupo de usuários ".$sNmGrupoUsuarioAcesso." de id: ".$nIdGrupoUsuario.", porém não possui permissão para isso!";
		}else{
			$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar informações na gerência de grupos de usuários, entretanto o id do grupo de usuários não foi carregado corretamente, a informação de id carregada no sistema foi o id:".$nIdGrupoUsuario.". De qualquer forma este usuário não possui permissão para alterar informações na gerência de grupos de usuários!";
		}//if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {
	}//if($sOP == "Cadastrar"){
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a gerência de grupos de usuários para ".$sOP." informações";
	if($sOP == "Alterar")
		$sObjeto .= " do Grupo de Usuários ".$sNmGrupoUsuarioAcesso." de Id: ".$nIdGrupoUsuario;
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}//if(!$bPermissao) {


if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0)
	$oGrupoUsuario = $oFachadaSeguranca->recuperaGrupoUsuario($nIdGrupoUsuario,BANCO);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/ADM.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
	require_once(PATH."/painel/includes/head.php"); //CARREGA O ARQUIVO COM O CABEÇALHO DO SITE
?>
<!-- InstanceBeginEditable name="head" -->
<link href="<?php echo $sCaminho?>css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?php echo $sCaminho?>js/jquery.validationEngine.js"></script>
<script language="javascript" src="<?php echo $sCaminho?>js/jquery.validationEngine-pt-BR.js"></script>
<script>
	// This method is called right before the ajax form validation request
	// it is typically used to setup some visuals ("Please wait...");
	// you may return a false to stop the request 
	function beforeCall(form, options){
		if (window.console) 
			console.log("Right before the AJAX form validation call");
		return true;
	}
	
	// Called once the server replies to the ajax form validation request
	function ajaxValidationCallback(status, form, json, options){
		if (window.console) 
			console.log(status);
		
		if (status === true && json[1] == true) {
			//alert("the form is valid!");
			//alert(json);
			// uncomment these lines to submit the form to form.action
			form.validationEngine('detach');
			form.submit();
			// or you may use AJAX again to submit the data
		}else{
			//jQuery("#fNome").validationEngine('showPrompt',json[2],'error',true);
			//jQuery("#formGrupoUsuario").validationEngine('validate');
			jQuery('#formGrupoUsuario').validationEngine('validateField', '#fNome');
			//jQuery('#fNome').validationEngine('showPrompt');
		}
	}
				
	jQuery(document).ready(function(){
		jQuery("#formGrupoUsuario").validationEngine({
			ajaxFormValidation: true,
			ajaxFormValidationURL: "includes/validaGRUPO.php",
			onAjaxFormComplete: ajaxValidationCallback,
		});
	});
</script>
<!-- InstanceEndEditable -->

</head>

<body <?php echo (isset($sBody) && $sBody != "") ? $sBody : "" ?>>

<?php
	require_once(PATH."/painel/includes/topo.php"); //CARREGA O ARQUIVO COM O TOPO DO SITE
	require_once(PATH."/painel/includes/barra.php"); //CARREGA O ARQUIVO COM A BARRA DO SITE
	require_once(PATH."/painel/includes/menu_lateral.php"); //CARREGA O ARQUIVO COM O MENU LATERAL DO SITE
?>

<div id="CONTEUDO">
<!-- InstanceBeginEditable name="EDITAVEL" -->

    <div id="MIGALHA"><a href="/painel/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <a href="/painel/administrativo/grupo_usuario/">Grupos de Usuários</a> &gt; <strong><?php echo $sOP?> Grupo de Usuários </strong></div>
    <h1><?php echo $sOP?> Grupo de Usuário</h1>
    
    <span id="msg"></span>
    <?php
    	if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
    ?>
    <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsg']?></div>
    <?php
    		$_SESSION['sMsg'] = "";
    	}//if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
    ?> 
    
    <form action="processa.php" method="post" id="formGrupoUsuario">
        <table width="500" border="0" cellspacing="1" cellpadding="0">
            <tr>
                <th align="right" class="FormCampoNome">Nome:</th>
                <td><input name="fNome" id="fNome" type="text" size="30" value="<?php echo (isset($oGrupoUsuario) && is_object($oGrupoUsuario)) ? $oGrupoUsuario->getNmGrupoUsuario() : ""?>" lang="<?php echo (isset($oGrupoUsuario) && is_object($oGrupoUsuario)) ? $oGrupoUsuario->getId() : ""?>" class="validate[required,ajax[ajaxGrupo]]" /><input type="hidden" name="fieldId" value="fNome" /></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Publicado:</th>
                <td><input name="fPublicado" type="checkbox" class="Campo" value="1" <?php echo (isset($oGrupoUsuario) && is_object($oGrupoUsuario) && $oGrupoUsuario->getPublicado() == 1) ? "checked" : ""?>></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Ativo:</th>
                <td><input name="fAtivo" type="checkbox" class="Campo" value="1" <?php echo (isset($oGrupoUsuario) && is_object($oGrupoUsuario) && $oGrupoUsuario->getAtivo() == 1) ? "checked" : ""?>></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="sOP" value="<?php echo $sOP?>">
                    <input type="hidden" name="fDataCadastro" value="<?php echo (isset($oGrupoUsuario) && is_object($oGrupoUsuario)) ? $oGrupoUsuario->getDtGrupoUsuario() : date("Y-m-d H:i:s")?>" />
                    <input type="hidden" name="fIdGrupoUsuario" value="<?php echo (isset($oGrupoUsuario) && is_object($oGrupoUsuario)) ? $oGrupoUsuario->getId() : ""?>">
                </td>
                <td><input type="submit" name="Submit" value="<?php echo $sOP?>" /></td>
            </tr>
        </table>
    </form>
<?php
if(isset($_SESSION['oGrupoUsuario']))
	unset($_SESSION['oGrupoUsuario']);
unset($_POST);
?>
<!-- InstanceEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
<!-- InstanceEnd --></html>
