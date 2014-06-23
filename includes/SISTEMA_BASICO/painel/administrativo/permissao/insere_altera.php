<?php
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");

// VERIFICA AS PERMISSÕES
$nIdGrupoUsuario = (isset($_GET['nIdGrupoUsuario']) && $_GET['nIdGrupoUsuario'] != "" && $_GET['nIdGrupoUsuario'] != 0) ? $_GET['nIdGrupoUsuario'] : ((isset($_POST['fIdGrupoUsuario'][0]) && $_POST['fIdGrupoUsuario'][0] != "" && $_POST['fIdGrupoUsuario'][0] != 0) ? $_POST['fIdGrupoUsuario'][0] : "");
$oFachadaSeguranca = new FachadaSegurancaBD();
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Permissao","Alterar",BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Permissao","Alterar",BANCO);
if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
	$oGrupoUsuarioAcesso = $oFachadaSeguranca->recuperaGrupoUsuario($nIdGrupoUsuario,BANCO);
	if(is_object($oGrupoUsuarioAcesso)){
		$nIdGrupoUsuarioAcesso = $oGrupoUsuarioAcesso->getId();
		$sNmGrupoUsuarioAcesso = $oGrupoUsuarioAcesso->getNmGrupoUsuario();
	}//if(is_object($oGrupoUsuarioAcesso)){
}//if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
if(!$bPermissao) {
	if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as permissões do grupo de usuários ".$sNmGrupoUsuarioAcesso." de id: ".$nIdGrupoUsuarioAcesso.", porém não possui permissão para isso!";
	}//if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {

	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}else{
	if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
		$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." acessou a gerência de grupos de usuários para alterar as permissões do grupo de usuários ".$sNmGrupoUsuarioAcesso;
		$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
		$oFachadaSeguranca->insereTransacao($oTransacao,BANCO);
	}//if(isset($nIdGrupoUsuario) && $nIdGrupoUsuario != "" && $nIdGrupoUsuario != 0) {
}//if(!$bPermissao) {

$oGrupoUsuario = $oFachadaSeguranca->recuperaGrupoUsuario($nIdGrupoUsuario,BANCO);

$vWhereCategoriaTipoTransacao = array("publicado = 1","ativo = 1");
$sOrderCategoriaTipoTransacao = "descricao asc";
$voCategoriaTipoTransacao = $oFachadaSeguranca->recuperaTodosCategoriaTipoTransacao(BANCO,$vWhereCategoriaTipoTransacao,$sOrderCategoriaTipoTransacao);

$vWherePermissao = array("publicado = 1","ativo = 1","id_grupo_usuario = ".$nIdGrupoUsuario);
$voPermissao = $oFachadaSeguranca->recuperaTodosPermissao(BANCO,$vWherePermissao);

$vIdCategoria = array();
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

    <div id="MIGALHA"><a href="/">Home</a> &gt; <a href="/painel/">Módulo Administrativo</a> &gt; <a href="/painel/administrativo/grupo_usuario/">Grupos de Usuários</a> &gt; <strong>Permissões do grupo de usuário <?php echo $oGrupoUsuario->getNmGrupoUsuario()?></strong></div>
    <h1>Permissões do grupo de usuário <?php echo $oGrupoUsuario->getNmGrupoUsuario()?></h1>
    
    <form name="form1" method="post" id="formGrupoUsuarioPermissao" action="processa_permissao.php">
		<?php
            if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
        ?>
        <div class="<?php echo (isset($_GET['bErro']) && $_GET['bErro'] == "1") ? "msg_erro" : "msg_sucesso"?>" style="display:block;"><?php echo $_SESSION['sMsg']?></div>
        <?php
                $_SESSION['sMsg'] = "";
            }//if(isset($_SESSION['sMsg']) && $_SESSION['sMsg'] != "") {
        ?> 
        
        <table width="100%"  border="0" align="center" cellpadding="2" cellspacing="0">
			<?php
				if(count($voCategoriaTipoTransacao) > 0){
					foreach($voCategoriaTipoTransacao as $oCategoriaTipoTransacao){
						if(!in_array($oCategoriaTipoTransacao->getId(),$vIdCategoria)){
							$vWhereTipoTransacao = array("id_categoria_tipo_transacao = ".$oCategoriaTipoTransacao->getId(),"publicado = 1","ativo = 1");
							$sOrderTipoTransacao = "transacao asc";
							$voTipoTransacao = $oFachadaSeguranca->recuperaTodosTipoTransacao(BANCO,$vWhereTipoTransacao,$sOrderTipoTransacao);
            ?>
            <tr class="TabelaTitulo">
            	<th height="25" colspan="2" align="left"><?php echo $oCategoriaTipoTransacao->getDescricao()?></th>
            </tr>
            <?php
							if(count($voTipoTransacao) > 0){
								foreach($voTipoTransacao as $oTipoTransacao){
									if (count($voPermissao) > 0){
										$sSelected = "";
										foreach($voPermissao as $oPermissao){
											if ($oPermissao->getIdTipoTransacao() == $oTipoTransacao->getId())
												$sSelected = "checked";
										}//foreach($vPermissao as $oPermissao){
									}//if (count($vPermissao) > 0){
            ?>
            <tr>
                <td width="1%"><label for="<?php echo $oTipoTransacao->getId()?>"></label></td>
                <td class="Texto">
                    <input type="checkbox" id="fIdTipoTransacao[<?php echo $oTipoTransacao->getId()?>]" name="fIdTipoTransacao[]" value="<?php echo $oTipoTransacao->getId()?>" <?php echo isset($sSelected) ? $sSelected : ""?>>
                    <label for="fIdTipoTransacao[<?php echo $oTipoTransacao->getId()?>]"><?php echo $oTipoTransacao->getTransacao()?></label>
                </td>
            </tr>
            <?php
            					}//foreach($vTipoTransacao as $oTipoTransacao){
							}else{
			?>
            <tr>
            	<td align="right" colspan="2" class="Texto">Não foram encontrados Tipos de Transação disponíveis!</td>
            </tr>
            <?php
							}//if(count($voTipoTransacao) > 0){
						}//if(!in_array($oCategoriaTipoTransacao->getId(),$vIdCategoria){
            		}//foreach($vCategoriaTipoTransacao as $oCategoriaTipoTransacao){
            ?>
            <tr>
            	<td align="right" colspan="2" class="Texto">&nbsp;</td>
            </tr>
            <tr>
                <td align="right" colspan="2" class="Texto">
                    <div align="left">
                        <input name="sOP" type="submit" class="Botao" value="Alterar">
                        <input type="hidden" name="fIdGrupoUsuario" value="<?php echo $nIdGrupoUsuario?>">
                    </div>
                </td>
            </tr>
            <?php
				}else{
			?>
            <tr>
            	<td align="right" colspan="2" class="Texto">Não foram encontradas Categorias de Tipos de Transações disponíveis!</td>
            </tr>
            <?php
				}//if(count($voCategoriaTipoTransacao) > 0){
			?>
        </table>
    </form>  
<?php
if(isset($_SESSION['oPermissao']))
	unset($_SESSION['oPermissao']);
unset($_POST);
?>
<!-- InstanceEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
<!-- InstanceEnd --></html>
