<html>
<head>
<title>Definindo a Conexão ao Banco de Dados</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body bgcolor="#FFFFFF" text="#000000">
<center>
	<form name="conexao" method="post" action="processa.php">
	<table width="27%" border="1" cellspacing="0" cellpadding="0">
      <tr align="center"> 
      <td colspan="2">Dados de Autenticação</td>
    </tr>
    <tr> 
      <td width="21%" align="right">Host:</td>
      <td width="79%">
          <input type="text" name="fHost" value="localhost">
        </td>
    </tr>
    <tr> 
      <td width="21%" align="right">Usuário:</td>
      <td width="79%">
          <input type="text" name="fUsuario" value="root">
        </td>
    </tr>
    <tr> 
      <td width="21%" align="right">Senha:</td>
      <td width="79%">
          <input type="password" name="fSenha" value="">
        </td>
    </tr>
    <tr align="center"> 
      <td colspan="2">Características do Banco</td>
    </tr>
    <tr> 
      <td width="21%" align="right">Nome:</td>
      <td width="79%">
          <input type="text" name="fNome">
        </td>
    </tr>
    <tr> 
      <td width="21%" align="right">Tipo:</td>
      <td width="79%">
          <select name="fTipo">
            <option value="MySQL" selected>MySQL</option>
          </select>
        </td>
    </tr>
    <tr> 
       <td colspan="2" align="center">
          <input type="submit" name="Conectar" value="Conectar">
          <input type="hidden" name="sOP" value="Conectar"> 	
		</td>
    </tr>
  </table>
  </form>
 </center>
</body>
</html>