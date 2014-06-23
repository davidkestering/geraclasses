<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/Paginacao.class.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

// VERIFICA AS PERMISSÕES
$bPermissaoVisualizar = $_SESSION['oLoginAdm']->verificaPermissao("Usuario","Visualizar",BANCO);
$oFachadaSeguranca = new FachadaSegurancaBD();
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Usuario","Visualizar",BANCO);
if(!$bPermissaoVisualizar) {
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou acessar a lista de usuários do sistema, porém não possui permissão para isso!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a lista de usuários do sistema!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}

$nNumeroElementos = (isset($_REQUEST['fNumeroElementos']) && $_REQUEST['fNumeroElementos'] != "") ? $_REQUEST['fNumeroElementos'] : 25;
$oPaginacao = new Paginacao($nNumeroElementos);

$voUsuario = $oFachadaSeguranca->recuperaTodosUsuario(BANCO);
if(count($voUsuario) > 0)
	$voUsuario = $oPaginacao->paginaResultado($voUsuario);
	
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
<script type="text/javascript">
	function liberaUsuario(nIdUsuario){
		$(function() {
			var oLabel = $("#label_"+nIdUsuario);
			$.ajax({
				type: "POST",
				url: "libera_usuario.php",
				data: "fIdUsuario="+nIdUsuario,
				beforeSend: function(e){
					oLabel.text("Liberando...");
				},
				success: function(msg){
					oLabel.text(msg);
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

    <div id="MIGALHA"><a href="/painel/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <strong>Usuários</strong></div>
    <h1>Usuários</h1>
    <?php
    	if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
    ?>
    <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsg']?></div>
    <?php
    		$_SESSION['sMsg'] = "";
    	}//if($_SESSION['sMsg']){
    ?> 
    <form method="post" action="processa.php">
        <input type="hidden" name="sOP" id="sOP">
        <div id="ICONES">
            <img src="../../imagens/icones/icone_cadastrar_1.gif" alt="Cadastrar" style="cursor:hand;" title="Cadastrar" lang="Nenhuma opcao" onclick="javascript: submeteFormOpcoes(this,'insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" />
            <img src="../../imagens/icones/icone_editar_0.gif" alt="Editar" style="cursor:hand;" title="Editar" lang="Uma opcao" onclick="javascript: submeteFormOpcoes(this,'insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" />
            <img src="../../imagens/icones/icone_excluir_0.gif" alt="Excluir" style="cursor:hand;" title="Excluir" lang="Varias opcoes" onclick="javascript: confirmaExclusaoLista(this,'o(s) usuário(s)');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" /><span id="AJUDA"></span>
            <!--<img src="../../imagens/icones/icone_ajuda_0.gif" alt="Ajuda" name="ajuda" id="ajuda" title="Ajuda" />-->
            <a href="javascript:void(0);" onclick="javascript:window.print();"><img src="../../imagens/icones/icone_imprimir_0.gif" alt="Imprimir" name="imprimir" title="Imprimir" id="imprimir" border="0" /></a>
        </div>
        <?php
        	if(count($voUsuario)) {
        ?>  
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabela_lista">
            <tr align="left">
                <th width="1%"><img src="../../imagens/checkbox.gif" width="21" height="17" onclick="javascript: alteraTodosCheckbox(this); atualizaIconesOpcoes(this);" /></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Nome</a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Login</a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Email</a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Grupo de Usuários</a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Logado</a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Ativo</a></th>
            </tr>
		<?php
				$nCount = 0;
				foreach($voUsuario as $oUsuario) {
					if(is_object($oUsuario)){
						$nIdUsuario = $oUsuario->getId();
						$oGrupoUsuario = $oFachadaSeguranca->recuperaGrupoUsuario($oUsuario->getIdGrupoUsuario(),BANCO);
						$nCount++;
						$sZebra = ($nCount % 2 == 0) ? ' class="zebra"' : "";
        ?>
            <tr<?php echo $sZebra?>>
                <td><input type="checkbox" name="fIdUsuario[]" value="<?php echo $nIdUsuario?>" onclick="javascript: atualizaIconesOpcoes(this);" /></td>
                <td><a href="insere_altera.php?nIdUsuario=<?php echo $nIdUsuario?>"><?php echo $oUsuario->getNmUsuario()?></a></td>
                <td><a href="insere_altera.php?nIdUsuario=<?php echo $nIdUsuario?>"><?php echo $oUsuario->getLogin()?></a></td>
                <td><a href="insere_altera.php?nIdUsuario=<?php echo $nIdUsuario?>"><?php echo $oUsuario->getEmail()?></a></td>
                <td><a href="insere_altera.php?nIdUsuario=<?php echo $nIdUsuario?>"><?php echo (is_object($oGrupoUsuario)) ? $oGrupoUsuario->getNmGrupoUsuario() : "-"?></a></td>
                <td><label id="label_<?php echo $oUsuario->getId()?>"><?php echo (is_object($oUsuario) && $oUsuario->getLogado() == 1) ? "<a href='Javascript: void(0);' onclick='liberaUsuario(".$oUsuario->getId().");'>Sim</a>" : "Não"?></label></td>
                <td><a href="insere_altera.php?nIdUsuario=<?php echo $nIdUsuario?>"><?php echo (is_object($oUsuario) && $oUsuario->getAtivo() == 1) ? "Sim" : "Não"?></a></td>
            </tr>
		<?php
        			}//if(is_object($oUsuario)){
        		}//foreach($voUsuario as $oUsuario) {
        ?>
        </table>
        <?php
        	}//if(count($voUsuario)) {
        ?>
    </form>
    
    <?php
    	if(count($voUsuario)) {
    ?>  
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="PAGINACAO">
            <tr>
                <td width="98%">Linhas por página:</td>
                <td width="1%">
                    <select name="fNumeroElementos" onchange="javascript: this.form.submit();">
                        <option<?php echo ($nNumeroElementos == 10) ? " selected" : ""?>>10</option>
                        <option<?php echo ($nNumeroElementos == 25) ? " selected" : ""?>>25</option>
                        <option<?php echo ($nNumeroElementos == 50) ? " selected" : ""?>>50</option>
                        <option<?php echo ($nNumeroElementos == 100) ? " selected" : ""?>>100</option>
                    </select>
                </td>
                <td width="1%" nowrap="nowrap"><?php echo $oPaginacao->retornaLinhaDeLinks()?></td>
            </tr>
        </table>
    </form>
    <?php
    	} else {
    ?>
    <div class="msg_alerta" style="display:block;">Não há Usuários cadastradas. </div>
    <?php
    	}//if(count($voUsuario)) {
    ?>
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
<!-- InstanceEnd -->
</html>