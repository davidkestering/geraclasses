<?php
require_once("../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/Paginacao.class.php");

$nNumeroElementos = ($_REQUEST['fNumeroElementos']) ? $_REQUEST['fNumeroElementos'] : 25;
$oPaginacao = new Paginacao($nNumeroElementos);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
	require_once(PATH."/painel/includes/head.php"); //CARREGA O ARQUIVO COM O CABEÇALHO DO SITE
?>
<!-- TemplateBeginEditable name="head" --><!-- TemplateEndEditable -->

</head>

<body <?php echo (isset($sBody) && $sBody != "") ? $sBody : "" ?>>

<?php
	require_once(PATH."/painel/includes/topo.php"); //CARREGA O ARQUIVO COM O TOPO DO SITE
	require_once(PATH."/painel/includes/barra.php"); //CARREGA O ARQUIVO COM A BARRA DO SITE
	require_once(PATH."/painel/includes/menu_lateral.php"); //CARREGA O ARQUIVO COM O MENU LATERAL DO SITE
?>

<div id="CONTEUDO">
<!-- TemplateBeginEditable name="EDITAVEL" -->

	<div id="MIGALHA"><a href="/">Home</a> &gt; <strong>T&iacute;tulo da p&aacute;gina</strong></div>
	<h1>T&iacute;tulo</h1>
	<form method="post" action="processa.php">
		<input type="hidden" name="sOP" id="sOP" />
        <div id="ICONES">
            <img src="../../painel/imagens/icones/icone_cadastrar_2.gif" onclick="javascript: submeteFormOpcoes(this,'insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" lang="Nenhuma opcao" style="cursor:pointer;" alt="Cadastrar" title="Cadastrar" />
            <img src="../../painel/imagens/icones/icone_editar_0.gif" onclick="javascript: submeteFormOpcoes(this,'insere_altera.php');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" lang="Uma opcao" style="cursor:pointer;" alt="Editar" title="Editar" />
            <img src="../../painel/imagens/icones/icone_excluir_0.gif" onclick="javascript: confirmaExclusaoLista(this,'o(s) registro(s)');" onmouseover="javascript: atualizaIconeOpcao(this,'ativo');" onmouseout="javascript: atualizaIconeOpcao(this,'inativo');" lang="Varias opcoes" style="cursor:pointer;" alt="Excluir" title="Excluir" /><span id="AJUDA"></span>
            <!--<img src="../../imagens/icones/icone_ajuda_0.gif" alt="Ajuda" name="ajuda" id="ajuda" title="Ajuda" />-->
            <a href="javascript:void(0);" onclick="javascript:window.print();"><img src="../../painel/imagens/icones/icone_imprimir_0.gif" alt="Imprimir" name="imprimir" title="Imprimir" id="imprimir" border="0" /></a>
        </div>
        
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabela_lista">
            <tr>
                <th width="1%"><img src="../../painel/imagens/checkbox.gif" width="21" height="17" onclick="javascript: alteraTodosCheckbox(this); atualizaIconesOpcoes(this);" /></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Coluna 1</a> <a href="#"><img src="/painel/imagens/duvida.gif" name="ajuda" class="ajuda" /></a></th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Coluna 2</a> </th>
                <th class="col_ordem_dec"><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Coluna 3</a> </th>
                <th><a href="javascript: void(0);" onclick="javascript: ordenaTabelaLista(this);">Coluna 4</a> </th>
            </tr>
            <tr<?php echo (isset($sZebra) && $sZebra != "") ? $sZebra : ""?>>
                <td><input type="checkbox" name="fId[]" value="1" onclick="javascript: atualizaIconesOpcoes(this);" /></td>
                <td><a href="insere_altera.php?nId=1">Lorem ipsum</a></td>
                <td><a href="insere_altera.php?nId=1">Lorem ipsum</a></td>
                <td class="col_destaca"><a href="insere_altera.php?nId=1">Lorem ipsum</a></td>
                <td><a href="insere_altera.php?nId=1">Lorem ipsum</a></td>
            </tr>
		</table>
	</form>
    
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" id="PAGINACAO">
            <tr>
                <td width="98%">Linhas por p&aacute;gina:</td>
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
    
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cras hendrerit odio et erat. Aliquam molestie odio quis velit. Nulla lorem nulla, auctor nec, venenatis eu, consequat a, justo. Pellentesque ultrices purus vel pede. In dignissim eros non velit. Vestibulum mauris enim, elementum ac, pharetra sed, tempor sed, mauris. Duis dui. Maecenas semper mauris vel quam. Duis volutpat. In sit amet erat. Aliquam velit sapien, hendrerit in, dapibus sed, luctus et, lacus. Maecenas imperdiet euismod purus. Suspendisse potenti. Fusce nonummy erat non velit. Ut pharetra. Sed lacus risus, iaculis non, interdum eu, scelerisque sit amet, ligula.</p>
    
    <p> Quisque justo leo, mattis eget, tempor in, volutpat sed, quam. Maecenas auctor mi ac magna. Nulla facilisi. Maecenas eget lorem. Vivamus magna. Nam nonummy auctor est. Duis tincidunt vestibulum dolor. Duis elit purus, facilisis ut, dignissim vitae, lobortis in, augue. Suspendisse venenatis mattis dui. Fusce lorem libero, molestie ut, condimentum ut, adipiscing non, lacus. Pellentesque orci. Mauris lectus ante, scelerisque quis, feugiat ac, luctus sed, eros. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse ultrices varius eros. In turpis nibh, fermentum non, rhoncus quis, ullamcorper eu, erat. Etiam tellus dui, aliquam at, lobortis vel, porttitor non, dolor. Aliquam cursus tellus sit amet quam. Sed mi. Mauris eget enim. In est. Nulla adipiscing, lacus ac venenatis lobortis, libero eros tristique enim, non rhoncus metus mi quis dolor. Integer commodo sem nec est. Sed nonummy, lacus quis rutrum pellentesque, pede quam aliquam nulla, sit amet mollis ipsum nisi a lacus. Integer dapibus, diam vitae hendrerit ullamcorper, ligula justo fringilla nulla, eget accumsan quam risus vel quam. Nulla elementum. In massa. Integer at elit. Sed egestas pede ut nisl. </p>
    
    <h2>Sub-t&iacute;tulo</h2>
    
    <p>Praesent fringilla pellentesque dolor. Maecenas imperdiet, mauris sit amet facilisis rhoncus, augue velit semper arcu, id fermentum enim diam a arcu. Mauris imperdiet nisi. Nam nec tellus rhoncus metus pharetra vehicula. Morbi egestas pretium massa. Phasellus dui augue, lacinia sit amet, tempor ut, vehicula eget, lorem. Suspendisse non neque. Aenean rutrum metus eget elit. Proin ut mauris id ipsum euismod eleifend. Vivamus enim est, dignissim ac, varius sed, tempus non, est. Proin ultrices pretium tortor. Donec lobortis blandit turpis. Morbi pharetra diam vel augue. Cras elementum suscipit nisl. Curabitur consequat. Sed ut magna. Pellentesque hendrerit magna sed orci. Suspendisse congue nunc vel diam. Quisque sodales. Curabitur enim. Proin massa enim, pharetra a, vulputate id, luctus aliquet, elit. Sed cursus pellentesque ligula. Pellentesque fringilla. Fusce lorem eros, vestibulum nec, hendrerit non, feugiat ac, lorem. </p>
    
    <p>Curabitur leo eros, rhoncus condimentum, nonummy euismod, fermentum in, libero. Sed sed turpis semper orci tristique interdum. Maecenas diam. Etiam id odio ac mi dignissim nonummy. Curabitur fermentum ligula et est. Nullam eget nisl. Duis vel enim id pede porttitor hendrerit. Donec scelerisque ornare quam. Maecenas ligula nibh, varius et, tempus vel, facilisis id, odio. Suspendisse magna neque, dapibus et, varius vitae, lacinia in, est. Maecenas id diam. </p>

<!-- TemplateEndEditable -->

</div><!-- FIM <div id="CONTEUDO"> -->

<?php
	require_once(PATH."/painel/includes/rodape.php"); //CARREGA O ARQUIVO COM O RODAPE DO SITE
?>

</body>
</html>