<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

$nIdUsuario = (isset($_GET['nIdUsuario']) && $_GET['nIdUsuario'] != "" && $_GET['nIdUsuario'] != 0) ? $_GET['nIdUsuario'] : ((isset($_POST['fIdUsuario'][0]) && $_POST['fIdUsuario'][0] != "" && $_POST['fIdUsuario'][0] != 0) ? $_POST['fIdUsuario'][0] : "");
$sOP = ($nIdUsuario != "") ? "Alterar" : "Cadastrar"; 
$oFachadaSeguranca = new FachadaSegurancaBD();

if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {
	$oUsuarioAcesso = $oFachadaSeguranca->recuperaUsuario($nIdUsuario,BANCO);
	if(is_object($oUsuarioAcesso)){
		$sNmUsuarioAcesso = $oUsuarioAcesso->getNmUsuario();
	}//if(is_object($oUsuarioAcesso)){
}//if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {

$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Usuario",$sOP,BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Usuario",$sOP,BANCO);
if(!$bPermissao) {
	if($sOP == "Cadastrar"){
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou cadastrar informações na gerência de usuários, porém não possui permissão para isso!";
	}else{
		if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {
			$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar dados do usuário ".$sNmUsuarioAcesso." de id: ".$nIdUsuario.", porém não possui permissão para isso!";
		}else{
			$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar informações na gerência de usuários, entretanto o id do usuário não foi carregado corretamente, a informação de id carregada no sistema foi o id:".$nIdUsuario.". De qualquer forma este usuário não possui permissão para alterar informações na gerência de usuários!";
		}//if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {
	}//if($sOP == "Cadastrar"){
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a gerência de usuários para ".$sOP." informações";
	if($sOP == "Alterar")
		$sObjeto .= " do Usuário ".$sNmUsuarioAcesso." de Id: ".$nIdUsuario;
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}//if(!$bPermissao) {

$voGrupoUsuario = $oFachadaSeguranca->recuperaTodosGrupoUsuario(BANCO);
if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {
	$oUsuarioDetalhe = $oFachadaSeguranca->recuperaUsuario($nIdUsuario,BANCO);
	if(is_object($oUsuarioDetalhe))
		$nIdGrupoUsuario = $oUsuarioDetalhe->getIdGrupoUsuario();
}//if(isset($nIdUsuario) && $nIdUsuario != "" && $nIdUsuario != 0) {

if(isset($_SESSION['oUsuario']) && (!isset($nIdGrupoUsuario) || $nIdGrupoUsuario == "" || $nIdGrupoUsuario == 0))
	$nIdGrupoUsuario = $_SESSION['oUsuario']->getIdGrupoUsuario();

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
			//jQuery("#fLogin").validationEngine('showPrompt',json[2],'error',true);
			//jQuery("#formUsuario").validationEngine('validate');
			//jQuery("#fLogin").focus();
			jQuery('#formUsuario').validationEngine('validateField', '#fLogin');
			//jQuery('#fLogin').validationEngine('showPrompt');
		}
	}
				
	jQuery(document).ready(function(){
		jQuery("#formUsuario").validationEngine({
			ajaxFormValidation: true,
			ajaxFormValidationURL: "includes/validaUSUARIO.php",
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

    <div id="MIGALHA"><a href="/painel/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <a href="/painel/administrativo/usuario/">Usuários</a> &gt; <strong><?php echo $sOP?> Usuário </strong></div>
    <h1><?php echo $sOP?> Usuário </h1>
    <span id="msg"></span>
    <?php
    	if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
    ?>
    <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsg']?></div>
    <?php
    		$_SESSION['sMsg'] = "";
    	}//if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
    ?> 
    
    <form action="processa.php" id="formUsuario" method="post">
        <table width="500" border="0" cellspacing="1" cellpadding="0">
            <tr>
                <th align="right">Nome:</th>
                <td><input name="fNome" id="fNome" type="text" size="30" value="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getNmUsuario() : ""?>" class="validate[required]" /></td>
            </tr>
            <tr>
                <th align="right">Email:</th>
                <td><input name="fEmail" id="fEmail" type="text" size="30" value="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getEmail() : ""?>" class="validate[required,custom[email]]" /></td>
            </tr>
            <tr>
                <th align="right">Grupo de Usuário:</th>
                <td>
                    <select name="fIdGrupoUsuario" id="fIdGrupoUsuario" class="validate[required]">
                        <option value="">Selecione</option>
                        <?php
                        	if(count($voGrupoUsuario)) {
                        		foreach($voGrupoUsuario as $oGrupoUsuario) {
									if(is_object($oGrupoUsuario)){
                        ?>
                        <option value="<?php echo $oGrupoUsuario->getId()?>"<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe) && $oGrupoUsuario->getId() == $oUsuarioDetalhe->getIdGrupoUsuario()) ? " selected" : ""?>><?php echo $oGrupoUsuario->getNmGrupoUsuario()?></option>
                        <?php
									}//if(is_object($oGrupoUsuario)){
                        		}//foreach($voGrupoUsuario as $oGrupoUsuario) {
                        	}//if(count($voGrupoUsuario)) {
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th align="right">Login:</th>
                <td><input name="fLogin" id="fLogin" type="text" size="10" value="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getLogin() : ""?>" class="validate[required,custom[noSpecialCaracters],ajax[ajaxUser]]" lang="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getId() : ""?>" maxlength='50' /><input type="hidden" name="fieldId" value="fLogin" /></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Senha:</th>
                <td><input name="fSenha" id="fSenha" type="password" size="15"  maxlength="20" value="" class="<?php echo (isset($sOP) && $sOP == "Cadastrar") ? "validate[required]" : ""?>"></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Confirme a senha:</th>
                <td><input name="fSenhaConfirmacao" id="fSenhaConfirmacao" type="password" size="15" maxlength="20" value="" class="validate[equals[fSenha]]"></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Publicado:</th>
                <td><input name="fPublicado" type="checkbox" class="Campo" value="1" <?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe) && $oUsuarioDetalhe->getPublicado() == 1) ? "checked" : ""?>></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Ativo:</th>
                <td><input name="fAtivo" type="checkbox" class="Campo" value="1" <?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe) && $oUsuarioDetalhe->getAtivo() == 1) ? "checked" : ""?>></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" value="<?php echo $sOP?>" />  
                    <input type="hidden" name="sOP" value="<?php echo $sOP?>">
                    <input type="hidden" name="fLogado" value="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getLogado() : "0"?>" />
                    <input type="hidden" name="fDataCadastro" value="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getDtUsuario() : date("Y-m-d H:i:s")?>" />
                    <input type="hidden" name="fIdUsuario" value="<?php echo (isset($oUsuarioDetalhe) && is_object($oUsuarioDetalhe)) ? $oUsuarioDetalhe->getId() : ""?>">
                </td>
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
