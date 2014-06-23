<?php
include_once(dirname(__FILE__)."/classes/class.Conexao.php");
include_once(dirname(__FILE__)."/classes/class.Banco.php");

session_start();

if (!isset($_SESSION['vParametroConexao'])){
	header("Location:index.php");
	exit;
} else {
	$vParametroConexao = array();
	$vParametroConexao = $_SESSION['vParametroConexao'];
	$oConexao = new Conexao($vParametroConexao['HOST'],$vParametroConexao['USUARIO'],$vParametroConexao['SENHA'],$vParametroConexao['TIPO'],$vParametroConexao['BANCO']);
	$oBanco = new Banco($oConexao->sBanco); 
}
?>
<html>
<head>
<title>Listagem de Tabelas</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body bgcolor="#FFFFFF" text="#000000">
<center>
	<form name="geracao" method="post" action="processa.php">
    <table width="27%" border="1" cellspacing="0" cellpadding="0">
     <tr align="center"> 
       <td>Tabelas do Banco : 
         <?php echo $oBanco->pegaNome()?>
       </td>
      </tr>
      <?php if ($oBanco->pegaNumeroDeTabelas() > 0) { ?>
      <tr> 
        <td align="center"> <select name="fTabela[]" size="10" multiple>
            <?php  foreach ($oBanco->vTabela as $vTabela) { ?>
            <option value="<?php echo $vTabela[0]?>"> 
            <?php echo $vTabela[0]?>
            </option>
            <?php } ?>
          </select> </td>
      </tr>
      <tr> 
        <td align="center"> <input type="submit" name="Gerar" value="Gerar Classes"> 
          <input type="hidden" name="sOP" value="Gerar"> </td>
      </tr>
      <?php } else { ?>
      <tr> 
        <td align="center"> O banco ainda não tem tabelas!</td>
      </tr>
      <?php } ?>
    </table>
  </form>
</center>
</body>
</html>