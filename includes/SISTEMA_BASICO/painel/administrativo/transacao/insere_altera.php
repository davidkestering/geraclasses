<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

$nIdCategoriaTipoTransacao = (isset($_GET['nIdCategoriaTipoTransacao']) && $_GET['nIdCategoriaTipoTransacao'] != "" && $_GET['nIdCategoriaTipoTransacao'] != 0) ? $_GET['nIdCategoriaTipoTransacao'] : ((isset($_POST['fIdCategoriaTipoTransacao'][0]) && $_POST['fIdCategoriaTipoTransacao'][0] != "" && $_POST['fIdCategoriaTipoTransacao'][0] != 0) ? $_POST['fIdCategoriaTipoTransacao'][0] : "");
$sOP = ($nIdCategoriaTipoTransacao) ? "Alterar" : "Cadastrar"; 

$oFachadaSeguranca = new FachadaSegurancaBD();
if(isset($nIdCategoriaTipoTransacao) && $nIdCategoriaTipoTransacao != "" && $nIdCategoriaTipoTransacao != 0) {
	$oCategoriaTipoTransacaoAcesso = $oFachadaSeguranca->recuperaCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO);
	if(is_object($oCategoriaTipoTransacaoAcesso))
		$sCategoriaTipoTransacaoAcesso = $oCategoriaTipoTransacaoAcesso->getDescricao();
}

// VERIFICA AS PERMISSÕES
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Transacao",$sOP,BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Transacao",$sOP,BANCO);
if(!$bPermissao) {
	if($sOP == "Cadastrar"){
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou cadastrar informações na gerência de transações do sistema, porém não possui permissão para isso!";
	}else{
		if(isset($nIdCategoriaTipoTransacao) && $nIdCategoriaTipoTransacao != "" && $nIdCategoriaTipoTransacao != 0) {
			$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as informações da Categoria de Tipo de Transação ".$sCategoriaTipoTransacaoAcesso." de id: ".$nIdCategoriaTipoTransacao.", porém não possui permissão para isso!";
		}else{
			$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar informações na gerência de transações, entretanto o id da Categoria de Tipo de Transação não foi carregado corretamente, a informação de id carregada no sistema foi o id:".$nIdCategoriaTipoTransacao.". De qualquer forma este usuário não possui permissão para alterar informações na gerência de transações!";
		}//if(isset($nIdCategoriaTipoTransacao) && $nIdCategoriaTipoTransacao != "" && $nIdCategoriaTipoTransacao != 0) {
	}//if($sOP == "Cadastrar"){
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a gerência de transações para ".$sOP." informações";
	if($sOP == "Alterar")
		$sObjeto .= " da Categoria de Tipo de Transações ".$sCategoriaTipoTransacaoAcesso." de Id: ".$nIdCategoriaTipoTransacao;
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}//if(!$bPermissao) {

$vWherePermissao = array("id_grupo_usuario = ".GRUPO_ADMINISTRADOR);
$voPermissao = $oFachadaSeguranca->recuperaTodosPermissao(BANCO,$vWherePermissao);

if(isset($nIdCategoriaTipoTransacao) && $nIdCategoriaTipoTransacao != "" && $nIdCategoriaTipoTransacao != 0) {
	$oCategoriaTipoTransacao = $oFachadaSeguranca->recuperaCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO);
	//print_r($oCategoriaTipoTransacao);
	if(is_object($oCategoriaTipoTransacao)){
		$sDescricaoCategoriaTipoTransacao = $oCategoriaTipoTransacao->getDescricao();
		$vWhereTipoTransacao = array("id_categoria_tipo_transacao = ".$nIdCategoriaTipoTransacao);
		$sOrderTipoTransacao = "transacao asc";
		$voTipoTransacao = $oFachadaSeguranca->recuperaTodosTipoTransacao(BANCO,$vWhereTipoTransacao,$sOrderTipoTransacao);
	}
}

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
<script language="javascript">
function montaLinha(nValor,nValorAtual) {
	if(nValor) {
		sHtml = "";
		var nValorAtual = parseInt(nValorAtual);
		for(var i=1;i<=nValor;i++){
			sHtml += "Transação: <input type='text' name='fTipoTransacaoNova"+(nValorAtual+i)+"' id='fTipoTransacaoNova"+(nValorAtual+i)+"' class='novoTipoTransacao validate[required,ajax[ajaxTransacao]]' onblur='javascript: validaTransacao(\"fTipoTransacaoNova"+(nValorAtual+i)+"\");' lang='<?php echo (isset($nIdCategoriaTipoTransacao) && $nIdCategoriaTipoTransacao != "" && $nIdCategoriaTipoTransacao != 0) ? $nIdCategoriaTipoTransacao : "0"?>_"+(nValorAtual+i)+"' size='20'><br />";
		}
		window.document.getElementById('divTransacao').innerHTML = sHtml;
	}
}
</script>
<script>
	function validaTransacao(sIdElemento){
		//alert(sIdElemento);
		//jQuery('#formTransacao').validationEngine('detach');
		jQuery('#formTransacao').validationEngine('validateField', '#'+sIdElemento);
		//jQuery('#formTransacao').validationEngine('attach');
	}

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
		//alert("AQUI1");
		if (window.console) 
			console.log(status);
		
		if (status === true && json[1] == true) {
			//alert("the form is valid!");
			//alert(json);
			// uncomment these lines to submit the form to form.action
			var nContadorErro = 0;
			$(".novoTipoTransacao").each(function(index, element) {
				//alert(index);
				//alert(element.id);
				jQuery('#formTransacao').validationEngine('validateField', '#'+element.id);
				$.ajax({
					type: 'GET',
					dataType: "json",
					data: 'fieldIdP='+element.lang+'&fieldValue='+element.value+'&fieldId='+element.id,
					url:'includes/validaTRANSACAO.php',
					success: function(retorno){
						var errorFieldId = retorno[0];
						var transacaoStatus = retorno[1];
						var msg = retorno[2];
						if(transacaoStatus == false){
							nContadorErro += 1;
							//alert("TESTE"+nContadorErro);
							jQuery('#formTransacao').validationEngine('validateField', '#'+errorFieldId);
							//alert(errorFieldId);
						}
					}
				});
			});
			
			//alert(nContadorErro);
			if(nContadorErro == 0){
				//alert(nContadorErro);
				form.validationEngine('detach');
				form.submit();
			}
			// or you may use AJAX again to submit the data
		}else{
			//jQuery("#fNome").validationEngine('showPrompt',json[2],'error',true);
			//jQuery("#formGrupoUsuario").validationEngine('validate');
			jQuery('#formTransacao').validationEngine('validateField', '#fNomeCategoria');
			//jQuery('#fNome').validationEngine('showPrompt');
		}
	}
	
	jQuery(document).ready(function(){
		jQuery("#formTransacao").validationEngine({
			ajaxFormValidation: true,
			ajaxFormValidationURL: "includes/validaCATEGORIA.php",
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

    <div id="MIGALHA"><a href="/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <strong><?php echo $sOP?> Transação</strong></div>
    <h1><?php echo $sOP?> Transação</h1>
    <span id="msg"></span>
    <?php
    	if(isset($_SESSION['sMsgTransacao']) && $_SESSION['sMsgTransacao'] != "") {
    ?>
    <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsgTransacao']?></div>
    <?php
    		$_SESSION['sMsgTransacao'] = "";
    	}//if($_SESSION['sMsg']){
    ?>
    
    <form method="post" action="processa.php" id="formTransacao" name="formTransacao">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th width="20%">Nome da Categoria:</th>
                <td><input type="text" name="fNomeCategoria" class="validate[required,ajax[ajaxCategoria]]" value="<?php echo (isset($oCategoriaTipoTransacao) && is_object($oCategoriaTipoTransacao) ? $sDescricaoCategoriaTipoTransacao : "")?>" size="30" id='fNomeCategoria' lang="<?php echo isset($nIdCategoriaTipoTransacao) ? $nIdCategoriaTipoTransacao : ""?>" /><input name="fieldId" type="hidden" value="fNomeCategoria" /></td>
            </tr>
            <tr>
                <th><label id="fTipoTransacao[]">Tipos de Transação:</label></th>
                <td>
					<?php
                    	if($sOP == "Cadastrar"){
							$nContador = 0;
                    ?>
                    Básicos:<br />
                    <input type="checkbox" name="fTipoTransacao[]" class="validate[minCheckbox[1]] checkbox" id="fTipoTransacao1" value="Visualizar" />Visualizar<br />
                    <input type="checkbox" name="fTipoTransacao[]" class="validate[minCheckbox[1]] checkbox" id="fTipoTransacao2" value="Cadastrar" />Cadastrar<br />
                    <input type="checkbox" name="fTipoTransacao[]" class="validate[minCheckbox[1]] checkbox" id="fTipoTransacao3" value="Alterar" />Alterar<br />
                    <input type="checkbox" name="fTipoTransacao[]" class="validate[minCheckbox[1]] checkbox" id="fTipoTransacao4" value="Excluir" />Excluir<br />
                    <input type="checkbox" name="fTipoTransacao[]" class="validate[minCheckbox[1]] checkbox" id="fTipoTransacao5" value="Desativar" />Desativar<br />
                    <?php
                    	}else{
                    		if(count($voTipoTransacao) > 0){
								$nContador = 0;
                    			foreach($voTipoTransacao as $oTipoTransacao){
                    				if(is_object($oTipoTransacao)){
										$nContador++;
                    					$nIdTipoTransacao = $oTipoTransacao->getId();
                    					$sTransacao = $oTipoTransacao->getTransacao();
                    					if (count($voPermissao) > 0){
                    						$sSelected = "";
                    						foreach($voPermissao as $oPermissao){
												if(is_object($oPermissao)){
													if ($oPermissao->getIdTipoTransacao() == $oTipoTransacao->getId())
														$sSelected = "checked";
												}//if(is_object($oPermissao)){
											}//foreach($voPermissao as $oPermissao){
										}//if (count($voPermissao) > 0){
                    ?>
                    <input type="checkbox" name="fTipoTransacao[]" class="validate[minCheckbox[1]] checkbox" id="fTipoTransacao<?php echo $nContador?>" value="<?php echo $sTransacao?>" <?php echo $sSelected?> /><?php echo $sTransacao?><br />
                    <?php
									}//if(is_object($oTipoTransacao)){
								}//foreach($voTipoTransacao as $oTipoTransacao){
							}else{
                    ?>
                    Não há tipos de transação cadastrados!
                    <?php
                    		}//if(count($voTipoTransacao) > 0){
                   		}//if($sOP == "Cadastrar"){
                    ?>
                    <br /><br />
                    Selecione a quantidade de novas transações:<br />
                    <select name="fQtd" id="fQtd" onChange="JavaScript: montaLinha(this.value,'<?php echo $nContador?>');">
                        <option value="">Selecione</option>
                        <?php 
                            for($i=1;$i<=4;$i++) {
                        ?>
                        <option value="<?php echo $i?>"><?php echo $i?></option>
                        <?php
                            }//for($i=1;$i<=20;$i++) {
                        ?>
                    </select><br /><br />
                    <div id="divTransacao"></div>
                </td>
            </tr>
            <tr>
            	<td colspan="2"><input type="hidden" name="sOP" value="<?php echo $sOP?>" />&nbsp;</td>
            </tr>
            <tr>
                <td><input type="hidden" name="fIdCategoriaTipoTransacao" value="<?php echo $nIdCategoriaTipoTransacao?>" />&nbsp;</td>
                <td><input type="submit" value="<?php echo $sOP?>" /></td>
            </tr>
        </table>
    </form>
<?php
if(isset($_SESSION['oCategoriaTipoTransacao']))
	unset($_SESSION['oCategoriaTipoTransacao']);
unset($_POST);
?>
<!-- InstanceEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
<!-- InstanceEnd --></html>
