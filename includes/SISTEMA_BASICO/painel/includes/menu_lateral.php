<?php
//á
$sCaminho = "../../../../painel/";

if(isset($_SESSION['oLoginAdm'])) {
	// VERIFICA AS PERMISSÕES
	$bPermissaoVisualizarUsuario = $_SESSION['oLoginAdm']->verificaPermissao("Usuario","Visualizar",BANCO);
	$bPermissaoVisualizarGrupoUsuario = $_SESSION['oLoginAdm']->verificaPermissao("Grupos","Visualizar",BANCO);
	$bPermissaoVisualizarTransacao = $_SESSION['oLoginAdm']->verificaPermissao("Transacao","Visualizar",BANCO);
	$bPermissaoVerLogTransacao = $_SESSION['oLoginAdm']->verificaPermissao("Transacao","VerLog",BANCO);
	$bPermissaoVerErroTransacao = $_SESSION['oLoginAdm']->verificaPermissao("Transacao","VerErro",BANCO);
}
?>

<div id="MENU_LATERAL"> 
<script type="text/javascript">
	function mostraMenu(sId){
		var oDivMenu = $("#"+sId);
		oDivMenu.fadeToggle('slow');
	}
</script>
<?php
if(isset($_SESSION['oLoginAdm'])) {
?>
	<table border="0" cellspacing="1" cellpadding="0" bgcolor="#FFFFFF">
        <tr> 
        	<td class="menu_lateral_modulo"><a href="javascript: void(0);">Página Inicial</a></td> 
        </tr> 
        <tr> 
        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>index.php">Home</a></td> 
        </tr>
        <tr>
        	<td class="menu_lateral_modulo"><a href="javascript: void(0);" onclick="mostraMenu('modulo_adm')">Administrativo</a></td>
        </tr> 
        <tr> 
            <td> 
                <div style="display:block;" id="modulo_adm">
                    <table width="100%" border="0" align="right" cellpadding="0" cellspacing="0"> 
						<?php
                        	if(isset($bPermissaoVisualizarUsuario)) {
                        ?>
                        <tr>
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/usuario/">Usuários</a></td> 
                        </tr> 
                        <?php
                        	}	
                        ?>		  
                        <tr> 
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/alterar_senha/alterar_senha.php?nIdUsuario=<?php echo $_SESSION['oLoginAdm']->oUsuario->getId()?>">Alterar senha</a></td> 
                        </tr> 
                        <?php
                        	if(isset($bPermissaoVisualizarGrupoUsuario)) {
                        ?>		  
                        <tr> 
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/grupo_usuario/">Grupos de usuários</a></td> 
                        </tr> 
                        <?php
							} 
							if(isset($bPermissaoVerLogTransacao)) {
                        ?>		  
                        <tr> 
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/transacao/">Transações</a></td> 
                        </tr>
                        <?php
							}
							if(isset($bPermissaoVerErroTransacao)) {
                        ?>		  
                        <tr> 
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/transacao_acesso/">Alertas</a></td> 
                        </tr>
                        <?php
							}
							if(isset($bPermissaoVisualizarTransacao)){
                        ?>
                        <tr> 
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/transacao/visualiza_transacao.php">Gerenciar Transações</a></td> 
                        </tr>
                        <?php
                        	}
                        ?>
                        <tr> 
                        	<td class="menu_lateral_opcao"><a href="<?php echo $sCaminho?>administrativo/login/processa.php?sOP=Logoff">Sair</a></td> 
                        </tr> 
                    </table>
                </div>
            </td> 
        </tr>
	</table> 
<?php
}//if(isset($_SESSION['oLoginAdm'])) {
?>  
</div>