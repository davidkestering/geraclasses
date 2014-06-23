<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/Data.class.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

// VERIFICA AS PERMISSÕES
$bPermissaoVisualizar = $_SESSION['oLoginAdm']->verificaPermissao("Transacao","VerLog",BANCO);
$oFachadaSeguranca = new FachadaSegurancaBD();
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Transacao","VerLog",BANCO);
if(!$bPermissaoVisualizar) {
	$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou acessar o log do sistema, porém não possui permissão para isso!";
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou o log do sistema!";
	$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
}

$oFachadaSeguranca = new FachadaSegurancaBD();

$vWhereCategoriaTipoTransacao = array();
$sOrderCategoriaTipoTransacao = "descricao asc";
$voCategoriaTipoTransacao = $oFachadaSeguranca->recuperaTodosCategoriaTipoTransacao(BANCO,$vWhereCategoriaTipoTransacao,$sOrderCategoriaTipoTransacao);

$voUsuario = $oFachadaSeguranca->recuperaTodosUsuario(BANCO);

$vMes = array(1 => "Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");

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

    <div id="MIGALHA"><a href="/painel/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <strong>Transação</strong></div> 
    <h1>Transação</h1> 
    
    <table width="100%"  border="0" cellpadding="0" cellspacing="0">
        <form name="form1" method="post" action="index.php" onSubmit="return validaForm(this,'#000000','#FF3300')">
            <tr align="left">
            	<th colspan="3" class="campo">Dados para consulta </th>
            </tr>
            <tr>
                <th width="13%" align="right" class="FormCampoNome"><label id="fIdCategoriaTipoTransacao">Pesquisar por:</label></th>
                <td colspan="2" class="Texto">
                    <select name="fIdCategoriaTipoTransacao" class="Campo" id="vazio">
                    	<?php
							if(count($voCategoriaTipoTransacao) > 0){
						?>
                        <option value="">Todos</option>
                        <?php
								//TRATAMENTO QUE RETIRA OS LOGS DA AREA DE TRANSACOES
								$vOculto = array(5);
								if(isset($_SESSION['oLoginAdm']) && $_SESSION['oLoginAdm']->oUsuario->getIdGrupoUsuario() == GRUPO_ADMINISTRADOR)
									$vOculto = array(0);
								
								foreach($voCategoriaTipoTransacao as $oCategoriaTipoTransacao){
									$sSelected = "";
									if(!in_array($oCategoriaTipoTransacao->getId(),$vOculto)){
										if(isset($_POST['fIdCategoriaTipoTransacao']) && $_POST['fIdCategoriaTipoTransacao'] == $oCategoriaTipoTransacao->getId()){
											$sSelected = "selected";
											$sNomePesquisa = $oCategoriaTipoTransacao->getDescricao();
										}
                        ?>
                        <option value="<?php echo $oCategoriaTipoTransacao->getId()?>" <?php echo $sSelected?>><?php echo $oCategoriaTipoTransacao->getDescricao()?></option>
                        <?php
									}//if(!in_array($oCategoriaTipoTransacao->getId(),$vOculto)){
								}//foreach($vCategoriaTipoTransacao as $oCategoriaTipoTransacao){
							}else{
						?>
                        <option value="">Não foram encontradas Categorias de Tipos de Transações disponíveis!</option>
                        <?php
							}//if(count($voCategoriaTipoTransacao) > 0){
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Data Início:</th>
                <td class="Texto"><input name="fDataInicio" lang="vazio" type="text" id="fDataInicioValida" value="<?php echo (isset($_POST['fDataInicio']) && $_POST['fDataInicio'] != "") ? $_POST['fDataInicio'] : date("d/m/Y")?>" size="18" /></td>
                <td width="90%" class="Texto"><img src="../../imagens/calendario.gif" alt="Selecione" id="botaoDataInicio" />
                <script type="text/javascript">Calendar.setup({ inputField:"fDataInicioValida", button:"botaoDataInicio" });</script></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Data Fim:</th>
                <td class="Texto"><input name="fDataFim" lang="vazio" type="text" id="fDataFimValida" value="<?php echo (isset($_POST['fDataFim']) && $_POST['fDataFim'] != "") ? $_POST['fDataFim'] : date("d/m/Y")?>" size="18" /></td>
                <td width="90%" class="Texto"><img src="../../imagens/calendario.gif" alt="Selecione" id="botaoDataFim" />
                <script type="text/javascript">Calendar.setup({ inputField:"fDataFimValida", button:"botaoDataFim" });</script></td>
            </tr>
            <tr>
                <th align="right" class="FormCampoNome">Usuário:</th>
                <td colspan="2" class="Texto">
                    <select name="fIdUsuario" class="Campo">
                    	<?php
							if(count($voUsuario) > 0){
						?>
                        <option value="">Selecione</option>
                        <?php
								foreach($voUsuario as $oUsuario){
									$sSelected = "";
									if(isset($_POST['fIdUsuario']) && $_POST['fIdUsuario'] == $oUsuario->getId())
										$sSelected = "selected";
                        ?>
                        <option value="<?php echo $oUsuario->getId()?>" <?php echo $sSelected?>><?php echo $oUsuario->getNmUsuario()?></option>
                        <?php
                        		}//foreach($vUsuario as $oUsuario){
							}else{
						?>
                        <option value="">Não foram encontrados usuários disponíveis!</option>
                        <?php
							}//if(count($voUsuario) > 0){
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2">
                    <input name="submit" type="submit" value="Consultar" class="botao" width="74" height="35" border="0">
                    <input type="hidden" name="bPesquisar" value="1">
                </td>
            </tr>
        </form>
    </table>
	<br /><br />
        
    <?php
		if(isset($_POST['bPesquisar']) && $_POST['bPesquisar'] == 1){
			//FORMATANDO A DATA
			if(isset($_POST['fDataInicio'])){
				$oDataInicio = new Data($_POST['fDataInicio'],"d/m/Y");
				$oDataInicio->setFormato("Y-m-d");
				$dDataInicio = $oDataInicio->getData();
				if($dDataInicio == "0000-00-00" || $dDataInicio == "--" || strlen($dDataInicio) < 10){
					$dDataInicio = "";
				}else{
					$dDataInicio = $dDataInicio." 00:00:00";
				}
			}
			
			if(isset($_POST['fDataFim'])){
				$oDataFim = new Data($_POST['fDataFim'],"d/m/Y");
				$oDataFim->setFormato("Y-m-d");
				$dDataFim = $oDataFim->getData();
				if($dDataFim == "0000-00-00" || $dDataFim == "--" || strlen($dDataFim) < 10){
					$dDataFim = "";
				}else{
					$dDataFim = $dDataFim." 23:59:59";
				}
			}
			
			$sWherePeriodo = "";
			if($dDataInicio != "" || $dDataFim != ""){
				if($dDataInicio != "" && $dDataFim != ""){
					$sWherePeriodo = "dt_transacao BETWEEN '".$dDataInicio."' AND '".$dDataFim."'";
				}else{
					if($dDataInicio != ""){
						$sWherePeriodo = "dt_transacao >= '".$dDataInicio."'";
					}
					
					if($dDataFim != ""){
						$sWherePeriodo = "dt_transacao <= '".$dDataFim."'";
					}
				}
			}
			
			$sWhereUsuario = "";
			if(isset($_POST['fIdUsuario']) && $_POST['fIdUsuario'] != "" && $_POST['fIdUsuario'] != 0)
				$sWhereUsuario = "id_usuario = ".$_POST['fIdUsuario'];
			
			$sWhereTipoTransacao = "";
			if(isset($_POST['fIdCategoriaTipoTransacao']) && $_POST['fIdCategoriaTipoTransacao'] != "" && $_POST['fIdCategoriaTipoTransacao'] != 0){
				$vWhereTipoTransacao = array("id_categoria_tipo_transacao = ".$_POST['fIdCategoriaTipoTransacao']);
				$sOrderTipoTransacao = "id asc";
				$voTipoTransacao = $oFachadaSeguranca->recuperaTodosTipoTransacao(BANCO,$vWhereTipoTransacao,$sOrderTipoTransacao);
				if(count($voTipoTransacao) > 0){
					$sWhereTipoTransacao = "id_tipo_transacao in (";
					foreach($voTipoTransacao as $oTipoTransacao){
						if(is_object($oTipoTransacao)){
							$sWhereTipoTransacao .= $oTipoTransacao->getId().",";
						}//if(is_object($oTipoTransacao)){
					}//foreach($voTipoTransacao as $oTipoTransacao){
					$sWhereTipoTransacao = substr($sWhereTipoTransacao,0,-1).")";
				}//if(count($voTipoTransacao) > 0){
			}//if(isset($_POST['fIdCategoriaTipoTransacao']) && $_POST['fIdCategoriaTipoTransacao'] != "" && $_POST['fIdCategoriaTipoTransacao'] != 0){
				
			//PESQUISA
			$vWhereTransacao = array("publicado = 1","ativo = 1",$sWherePeriodo,$sWhereUsuario,$sWhereTipoTransacao);
			$sOrderTransacao = "dt_transacao desc, id asc";
			$voTransacao = $oFachadaSeguranca->recuperaTodosTransacao(BANCO,$vWhereTransacao,$sOrderTransacao);
			if(count($voTransacao) > 0){
    ?>
    <div id="ICONES">
        <a href="javascript:void(0);" onclick="javascript:window.print();"><img src="../../imagens/icones/icone_imprimir_0.gif" style="cursor:hand;" alt="Imprimir" title="Imprimir" border="0" /></a>
        <span id="AJUDA"></span>
    </div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabela_lista">
        <tr align="left">
            <th>Usuário Responsável</th>
            <th><?php echo (isset($sNomePesquisa) && $sNomePesquisa != "") ? $sNomePesquisa : 'Objeto'?></th>
            <th>Transação</th>
            <th>Data / Hora </th>
        </tr>
	<?php
				$nCount = 0;
				foreach($voTransacao as $oTransacao){
					if(is_object($oTransacao)){
						$oTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacao($oTransacao->getIdTipoTransacao(),BANCO);
						if(is_object($oTipoTransacao)){
							$oCategoriaTipoTransacao = $oFachadaSeguranca->recuperaCategoriaTipoTransacao($oTipoTransacao->getIdCategoriaTipoTransacao(),BANCO);
							$oUsuario = $oFachadaSeguranca->recuperaUsuario($oTransacao->getIdUsuario(),BANCO);
						}
						$sNome = $oTransacao->getObjeto();

						$oDataTransacao = new Data(substr($oTransacao->getDtTransacao(),0,10),"Y-m-d");
						$oDataTransacao->setFormato("d/m/Y");
						$dDataTransacao = $oDataTransacao->getData()." ".substr($oTransacao->getDtTransacao(),11,5);
						
						$nCount++;
						$sZebra = ($nCount % 2 == 0) ? ' class="zebra"' : "";
	?>
        <tr<?php echo $sZebra?>>
            <td class="Texto"><?php echo (isset($oUsuario) && is_object($oUsuario)) ? $oUsuario->getNmUsuario() : "-"?></td>
            <td class="Texto"><?php echo isset($sNome) ? $sNome : "-"?></td>
            <td class="Texto"><?php echo (isset($_POST) && $_POST['fIdCategoriaTipoTransacao'] == "") ? $oCategoriaTipoTransacao->getDescricao()." - ".$oTipoTransacao->getTransacao() : 	$oTipoTransacao->getTransacao()?></td>
            <td align="left" class="Texto"><?php echo isset($dDataTransacao) ? $dDataTransacao : "-"?></td>
        </tr>
	<?php
					}//if(is_object($oTransacao)){
				}//foreach($vTransacao as $oTransacao){
	?>
    </table>
	<br /><br />
    <?php 		
    		} else {
    ?>
    <div class="msg_erro" style="display:block;">Não foram encontrados registros para a sua pesquisa</div>
    <?php
			}//if(count($voTransacao) > 0){
		}else{
	?>
    <div class="msg_alerta" style="display:block;">Ajuste as opções do filtro como desejar e clique em consultar para obter as informações!</div>
    <?php
		}//if(isset($_POST['bPesquisar']) && $_POST['bPesquisar'] == 1){
    ?>
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
