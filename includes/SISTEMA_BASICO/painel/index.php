<?php
require_once("../constantes.php");
require_once(PATH."/classes/Login.class.php");
session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/ADM.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
	require_once(PATH."/painel/includes/head.php"); //CARREGA O ARQUIVO COM O CABEÇALHO DO SITE
?>
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
	function liberaUsuario(nIdUsuario){
		$(function() {
			$.ajax({
				type: "POST",
				url: "includes/libera_usuario.php",
				data: "fIdUsuario="+nIdUsuario,
				success: function(msg){
					alert(msg);
				}
			});
		});
	}
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
  <div id="MIGALHA">Home &gt; <strong>Identificação</strong></div>
  <h1>Identificação</h1>
<?php
	if(!isset($_SESSION['oLoginAdm']))
		$sTitulo = "Identifique-se para ter acesso a Área Administrativa do Portal ORM";
	else
		$sTitulo = "Bem vindo a Área Administrativa do Portal ORM";
?>  
  <h3><?php echo $sTitulo?></h3>
  <p>&nbsp;</p>
<?php
		if(isset($_SESSION['sMsgPermissao']) && $_SESSION['sMsgPermissao'] != "") {
?>
  <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsgPermissao']?></div>
<?php
			$_SESSION['sMsgPermissao'] = "";
		}//if($_SESSION['sMsgPermissao']){
?> 
  <span id="msg"></span>
<?php
	if(!isset($_SESSION['oLoginAdm'])) {
?>
  <form name="form1" id="form1" method="post" action="administrativo/login/processa.php" onsubmit="return validaForm(this,'#000000','#FF3300')">
	  <table width="400" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td align="right"><label id="fLogin">Login:</label></td>
		<td>&nbsp;</td>
		<td><input name="fLogin" type="text" id="campoLogin" size="10" lang="vazio" /></td>
	  </tr>
	  <tr>
		<td align="right"><label id="fSenha">Senha:</label></td>
		<td>&nbsp;</td>
		<td><input name="fSenha" type="password" size="10" lang="vazio" /></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;
		</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="submit" value="Logar" /><input type="hidden" name="sOP" value="Logar"></td>
	  </tr>
	</table>
  </form>
<script language="javascript">
	document.getElementById('campoLogin').focus();
</script>
<?php
	}
?>
  <!-- InstanceEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
<!-- InstanceEnd --></html>
