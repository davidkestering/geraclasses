<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");
require_once(PATH."/classes/Paginacao.class.php");

// VERIFICA AS PERMISSÕES
$bPermissaoVisualizar = $_SESSION['oLoginAdm']->verificaPermissao("Transacao","Visualizar",BANCO);
$oFachadaSeguranca = new FachadaSegurancaBD();
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Transacao","Visualizar",BANCO);
if(!$bPermissaoVisualizar) {
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou acessar a gerência de transações do sistema, porém não possui permissão para isso!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a gerência de transações do sistema!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}

$nNumeroElementos = (isset($_REQUEST['fNumeroElementos']) && $_REQUEST['fNumeroElementos'] != "") ? $_REQUEST['fNumeroElementos'] : 25;
$oPaginacao = new Paginacao($nNumeroElementos);

$vWhereCategoriaTipoTransacaoMenu = array();
$sOrderCategoriaTipoTransacaoMenu = "descricao asc";
$voCategoriaTipoTransacaoMenu = $oFachadaSeguranca->recuperaTodosCategoriaTipoTransacao(BANCO,$vWhereCategoriaTipoTransacaoMenu,$sOrderCategoriaTipoTransacaoMenu);

if(isset($_GET['fConsulta']) && $_GET['fConsulta'] == 1){
	$sWhereCategoriaTipoTransacao = "";
	if(isset($_GET['fIdCategoriaTransacao']) && $_GET['fIdCategoriaTransacao'] != "" && $_GET['fIdCategoriaTransacao'] != 0)
		$sWhereCategoriaTipoTransacao = "id = ".$_GET['fIdCategoriaTransacao'];
	
	$vWhereCategoriaTipoTransacaoConsulta = array($sWhereCategoriaTipoTransacao);
	$sOrderCategoriaTipoTransacaoConsulta = "descricao asc";
	$voCategoriaTipoTransacao = $oFachadaSeguranca->recuperaTodosCategoriaTipoTransacao(BANCO,$vWhereCategoriaTipoTransacaoConsulta,$sOrderCategoriaTipoTransacaoConsulta);
}

if(isset($voCategoriaTipoTransacao) && count($voCategoriaTipoTransacao) > 0)
	$voCategoriaTipoTransacao = $oPaginacao->paginaResultado($voCategoriaTipoTransacao);
//print_r($voCategoriaTipoTransacao);

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

    <div id="MIGALHA"><a href="/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <strong>Gerenciar Transações</strong></div>
    <h1>Gerenciar Transações</h1>
    <?php
    	if(isset($_SESSION['sMsgTransacao']) && $_SESSION['sMsgTransacao'] != "") {
    ?>
    <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsgTransacao']?></div>
    <?php
    		$_SESSION['sMsgTransacao'] = "";
    	}//if($_SESSION['sMsg']){
    ?>
    
    <form method="get" action="visualiza_transacao.php">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th width="20%">Categorias de Transações:</th>
                <td>
                    <select name="fIdCategoriaTransacao">
                        <option value="">Selecione</option>
                        <?php
                        	if(count($voCategoriaTipoTransacaoMenu) > 0){
                        		foreach($voCategoriaTipoTransacaoMenu as $oCategoriaTipoTransacaoMenu){
                        			if(is_object($oCategoriaTipoTransacaoMenu)){
                        				$sDescricaoCategoriaTipoTransacaoMenu = $oCategoriaTipoTransacaoMenu->getDescricao();
                        ?>
                        <option value="<?php echo $oCategoriaTipoTransacaoMenu->getId()?>" <?php echo (isset($_GET['fIdCategoriaTransacao']) && $_GET['fIdCategoriaTransacao'] == $oCategoriaTipoTransacaoMenu->getId()) ? "selected" : ""?>><?php echo $sDescricaoCategoriaTipoTransacaoMenu?></option>
                        <?php
                        			}//if(is_object($oCategoriaTipoTransacaoMenu)){
                        		}//foreach($voCategoriaTipoTransacaoMenu as $oCategoriaTipoTransacaoMenu){
                        	}else{
                        ?>
                        <option value="">Não há categorias de transações cadastrados!</option>
                        <?php
                        	}//if(count($voCategoriaTipoTransacaoMenu) > 0){
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="hidden" name="fConsulta" value="1" />&nbsp;</td>
                <td><input type="submit" value="Consultar" /></td>
            </tr>
        </table>
    </form>
    
    <form method="post" action="processa.php">
        <input type="hidden" name="sOP" id="sOP">
        <div id="ICONES">
            <img src="../../imagens/icones/icone_cadastrar_2.gif" onclick="javascript: submeteFormOpcoes(this,'insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" lang="Nenhuma opcao" style="cursor:hand;" alt="Cadastrar" title="Cadastrar" />
            <img src="../../imagens/icones/icone_editar_0.gif" onclick="javascript: submeteFormOpcoes(this,'insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" lang="Uma opcao" style="cursor:hand;" alt="Editar" title="Editar" />
            <img src="../../imagens/icones/icone_excluir_0.gif" onclick="javascript: confirmaExclusaoLista(this,'o(s) registro(s)');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" lang="Varias opcoes" style="cursor:hand;" alt="Excluir" title="Excluir" /><span id="AJUDA"></span>
            <!--<img src="/imagens/icones/icone_ajuda_0.gif" alt="Ajuda" name="ajuda" id="ajuda" title="Ajuda" />-->
            <a href="javascript:void(0);" onclick="javascript:window.print();"><img src="../../imagens/icones/icone_imprimir_0.gif" alt="Imprimir" name="imprimir" title="Imprimir" id="imprimir" border="0" /></a>
        </div>
        
        <?php
        	if(isset($_GET['fConsulta']) && $_GET['fConsulta'] == 1){
        		if(count($voCategoriaTipoTransacao) > 0){
        ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabela_lista">
            <tr align="left">
                <th width="1%"><img src="../../imagens/checkbox.gif" width="21" height="17" onclick="javascript: alteraTodosCheckbox(this); atualizaIconesOpcoes(this);" /></th>
                <th width="30%"><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Categoria de Transação</a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Tipos de Transações</a> </th>
            </tr>
		<?php
					$nCount = 0;
            		foreach($voCategoriaTipoTransacao as $oCategoriaTipoTransacao){
            			if(is_object($oCategoriaTipoTransacao)){
							$nCount++;
							$sZebra = ($nCount % 2 == 0) ? ' class="zebra"' : "";
							
							$nIdCategoriaTipoTransacao = $oCategoriaTipoTransacao->getId();
							$sDescricaoCategoriaTipoTransacao = $oCategoriaTipoTransacao->getDescricao();
							
							if(isset($nIdCategoriaTipoTransacao) && $nIdCategoriaTipoTransacao != "" && $nIdCategoriaTipoTransacao != 0){
								$vWhereTipoTransacao = array("id_categoria_tipo_transacao = ".$nIdCategoriaTipoTransacao);
								$sOrderTipoTransacao = "transacao asc";
								$voTipoTransacao = $oFachadaSeguranca->recuperaTodosTipoTransacao(BANCO,$vWhereTipoTransacao,$sOrderTipoTransacao);
							}
		?>
            <tr <?php echo $sZebra?>>
                <td><input type="checkbox" name="fIdCategoriaTipoTransacao[]" value="<?php echo $nIdCategoriaTipoTransacao?>" onclick="javascript: atualizaIconesOpcoes(this);" /></td>
                <td><a href="insere_altera.php?nIdCategoriaTipoTransacao=<?php echo $nIdCategoriaTipoTransacao?>"><?php echo $sDescricaoCategoriaTipoTransacao?></a> <?php echo ($oCategoriaTipoTransacao->getPublicado() == 1) ? "<font color='#000099'>(Publicado)</font>" : "<font color='#FF0000'>(Não Publicado)</font>"?> <?php echo ($oCategoriaTipoTransacao->getAtivo() == 1) ? "<font color='#000099'>(Ativo)</font>" : "<font color='#FF0000'>(Não Ativo)</font>"?></td>
                <td>
		<?php
                            if(count($voTipoTransacao) > 0){
                                foreach($voTipoTransacao as $oTipoTransacao){
                                    if(is_object($oTipoTransacao)){
                                        $sTransacao = $oTipoTransacao->getTransacao();
        ?>
                • <?php echo $sTransacao?>;<br /> <?php echo ($oTipoTransacao->getPublicado() == 1) ? "<font color='#000099'>(Publicado)</font>" : "<font color='#FF0000'>(Não Publicado)</font>"?> <?php echo ($oTipoTransacao->getAtivo() == 1) ? "<font color='#000099'>(Ativo)</font>" : "<font color='#FF0000'>(Não Ativo)</font>"?><br /><br />

		<?php
									}//if(is_object($oTipoTransacao)){
								}//foreach($voTipoTransacao as $oTipoTransacao){
							}else{
		?>
                Não há tipos de transação cadastrados!
		<?php
							}//if(count($voTipoTransacao) > 0){
		?>
                </td>
            </tr>
		<?php
						}//if(is_object($oCategoriaTipoTransacao)){
						unset($voTipoTransacao);
						unset($oTipoTransacao);
						unset($sTransacao);
					}//foreach($voCategoriaTipoTransacao as $oCategoriaTipoTransacao){
		?>
        </table>
    </form>
    
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
    			}else{
    ?>
    <div class="msg_alerta" style="display:block;">Não há categorias de transações cadastradas!</div>
    <?php
    			}//if(count($voCategoriaTipoTransacao) > 0){
    		}//if($_GET['fConsulta'] == 1){
    ?>
<?php
if(isset($_SESSION['oTransacao']))
	unset($_SESSION['oTransacao']);
unset($_POST);
?>
<!-- InstanceEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
<!-- InstanceEnd --></html>
