<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/Paginacao.class.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

// VERIFICA AS PERMISSÕES
$bPermissaoVisualizar = $_SESSION['oLoginAdm']->verificaPermissao("Grupos","Visualizar",BANCO);
$oFachadaSeguranca = new FachadaSegurancaBD();
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Grupos","Visualizar",BANCO);
if(!$bPermissaoVisualizar) {
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou acessar a lista de grupos de usuários do sistema, porém não possui permissão para isso!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a lista de grupos de usuários do sistema!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}

$nNumeroElementos = (isset($_REQUEST['fNumeroElementos']) && $_REQUEST['fNumeroElementos'] != "") ? $_REQUEST['fNumeroElementos'] : 25;
$oPaginacao = new Paginacao($nNumeroElementos);

$voGrupoUsuario = $oFachadaSeguranca->recuperaTodosGrupoUsuario(BANCO);
if(count($voGrupoUsuario) > 0)
	$voGrupoUsuario = $oPaginacao->paginaResultado($voGrupoUsuario);

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

    <div id="MIGALHA"><a href="/painel/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <strong>Grupos de Usuários </strong></div>
    <h1>Grupos de Usuários </h1>
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
            <img src="../../imagens/icones/icone_editar_0.gif" alt="Gerenciar permissões" style="cursor:hand;" title="Editar" lang="Uma opcao" onclick="javascript: submeteFormOpcoes(this,'../../administrativo/permissao/insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" />
            <img src="../../imagens/icones/icone_excluir_0.gif" alt="Excluir" style="cursor:hand;" title="Excluir" lang="Varias opcoes" onclick="javascript: confirmaExclusaoLista(this,'o(s) Grupo(s) de Usuário');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" /><span id="AJUDA"></span>
            <!--<img src="../../imagens/icones/icone_ajuda_0.gif" alt="Ajuda" name="ajuda" id="ajuda" title="Ajuda" />-->
            <a href="javascript:void(0);" onclick="javascript:window.print();"><img src="../../imagens/icones/icone_imprimir_0.gif" alt="Imprimir" name="imprimir" title="Imprimir" id="imprimir" border="0" /></a>
        </div>
        
        <?php
        	if(count($voGrupoUsuario) > 0) {
        ?>  
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabela_lista">
            <tr>
                <th width="1%"><img src="../../imagens/checkbox.gif" width="21" height="17" onclick="javascript: alteraTodosCheckbox(this); atualizaIconesOpcoes(this);" /></th>
                <th align="left"><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Nomes dos Grupos de Usuários</a></th>
                <th align="left"><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Ativo</a></th>
            </tr>
		<?php
				$nCount = 0;
				foreach($voGrupoUsuario as $oGrupoUsuario) {
					if(is_object($oGrupoUsuario)){
						$nIdGrupoUsuario = $oGrupoUsuario->getId();
						$nCount++;
						$sZebra = ($nCount % 2 == 0) ? ' class="zebra"' : "";
		?>
            <tr<?php echo $sZebra?>>
                <td><input type="checkbox" name="fIdGrupoUsuario[]" value="<?php echo $nIdGrupoUsuario?>" onclick="javascript: atualizaIconesOpcoes(this);" /></td>
                <td><a href="insere_altera.php?nIdGrupoUsuario=<?php echo $nIdGrupoUsuario?>"><?php echo $oGrupoUsuario->getNmGrupoUsuario()?></a></td>
                <td><a href="insere_altera.php?nIdGrupoUsuario=<?php echo $nIdGrupoUsuario?>"><?php echo (is_object($oGrupoUsuario) && $oGrupoUsuario->getAtivo() == 1) ? "Sim" : "Não"?></a></td>
            </tr>
		<?php
					}//if(is_object($oGrupoUsuario)){
            	}//foreach($voGrupoUsuario as $oGrupoUsuario) {
		?>
        </table>
        <?php
        	}//if(count($voGrupoUsuario) > 0) {
        ?>
    </form>
    
    <?php
    	if(count($voGrupoUsuario) > 0) {
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
    <div class="msg_alerta" style="display:block;">Não há Grupos de Usuário cadastrados. </div>
    <?php
    	}//if(count($voGrupoUsuario) > 0) {
    ?>
    
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
