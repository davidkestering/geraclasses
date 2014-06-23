<?php 
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");
require_once(PATH."/classes/Validacao.class.php");

$oFachadaSeguranca = new FachadaSegurancaBD();
$oValidacao = new Validacao();

//print_r($_POST);
//die();

// VERIFICA AS PERMISSÕES
$sOP = (isset($_POST['sOP'])) ? $_POST['sOP'] : "";
$nIdTipoTransacao = $_SESSION['oLoginAdm']->recuperaTipoTransacaoPorDescricaoCategoria("Grupos",$sOP,BANCO);
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Grupos",$sOP,BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Grupos",$sOP,BANCO);

if(!$bPermissao){
	//TRANSACAO
	$bPublicadoAcesso = (isset($_POST['fPublicado']) && $_POST['fPublicado'] == 1) ? "1" : "0";
	$bAtivoAcesso = (isset($_POST['fAtivo']) && $_POST['fAtivo'] == 1) ? "1" : "0";
	
	if($sOP != "Excluir"){
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou ".$sOP." informações na gerência de grupos de usuários, porém não possui permissão para isso! Segue abaixo as informações:<br />";
		if($sOP == "Cadastrar"){
			if(isset($_POST['fNome']))
				$sObjetoAcesso .= "Grupo de Usuários: ".$_POST['fNome']."<br />";
			if(isset($bPublicadoAcesso)){
				if($bPublicadoAcesso == 1)
					$sPublicadoAcesso = "Sim";
				else
					$sPublicadoAcesso = "Não";
				$sObjetoAcesso .= "Publicado: ".$sPublicadoAcesso."<br />";
			}//if(isset($bPublicadoAcesso)){
			if(isset($bAtivoAcesso)){
				if($bAtivoAcesso == 1)
					$sAtivoAcesso = "Sim";
				else
					$sAtivoAcesso = "Não";
				$sObjetoAcesso .= "Ativo: ".$sAtivoAcesso."<br />";
			}//if(isset($bAtivoAcesso)){
		}else{
			if($sOP == "Alterar"){
				$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
				if(is_object($oGrupoUsuarioAuxiliar)){
					// TRANSACAO
					if($oGrupoUsuarioAuxiliar->getNmGrupoUsuario() != $_POST['fNome'])
						$sObjetoAcesso .= "Grupo de Usuários: ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario()." --> ".$_POST['fNome']."<br />";
					if($oGrupoUsuarioAuxiliar->getPublicado() != $bPublicado){
						if($oGrupoUsuarioAuxiliar->getPublicado() == 1)
							$sPublicadoAtual = "Sim";
						else
							$sPublicadoAtual = "Não";
							
						if($bPublicado == 1)
							$sPublicadoNovo = "Sim";
						else
							$sPublicadoNovo = "Não";
						$sObjetoAcesso .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
					}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
					if($oGrupoUsuarioAuxiliar->getAtivo() != $bAtivo){
						if($oGrupoUsuarioAuxiliar->getAtivo() == 1)
							$sAtivoAtual = "Sim";
						else
							$sAtivoAtual = "Não";
							
						if($bAtivo == 1)
							$sAtivoNovo = "Sim";
						else
							$sAtivoNovo = "Não";
						$sObjetoAcesso .= "Ativo: ".$sAtivoAtual." --> ".$sAtivoNovo."<br />";
					}//if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				}//if(is_object($oGrupoUsuarioAuxiliar)){
			}//if($sOP == "Alterar"){
		}//if($sOP == "Cadastrar"){
	}else{
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou excluir informações da gerência de usuários, porém não possui permissão para isso! Segue abaixo as informações:<br />";
		if(count($_POST['fIdGrupoUsuario']) > 0){
			foreach($_POST['fIdGrupoUsuario'] as $nIdGrupoUsuario) {
				if ($oFachadaSeguranca->presenteGrupoUsuario($nIdGrupoUsuario,BANCO)){
					// TRANSACAO
					$oGrupoUsuarioAuxiliarAcesso = $oFachadaSeguranca->recuperaGrupoUsuario($nIdGrupoUsuario,BANCO);
					if(is_object($oGrupoUsuarioAuxiliarAcesso)){
						$sObjetoAcesso .= "Tentou excluir o grupo de usuários ".$oGrupoUsuarioAuxiliarAcesso->getNmGrupoUsuario()." de id=".$oGrupoUsuarioAuxiliarAcesso->getId()."<br />";
					}//if(is_object($oGrupoUsuarioAuxiliarAcesso)){
				}//if ($oFachadaSeguranca->presenteGrupoUsuario($nIdGrupoUsuario,BANCO)){
			}//foreach($_POST['fIdGrupoUsuario'] as $nIdGrupoUsuario) {
		}else{
			$sObjetoAcesso .= "VERIFICAR: Não houve envio de ids de grupos de usuários para exclusão!???<br />";
		}//if(count($_POST['fIdUsuario']) > 0){
	}//if($sOP != "Excluir"){
	
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}//if(!$bPermissao){

if (isset($sOP) && $sOP != "Excluir"){
	$bPublicado = (isset($_POST['fPublicado']) && $_POST['fPublicado'] == 1) ? "1" : "0";
	$bAtivo = (isset($_POST['fAtivo']) && $_POST['fAtivo'] == 1) ? "1" : "0";
	
	$oGrupoUsuario = $oFachadaSeguranca->inicializaGrupoUsuario($_POST['fIdGrupoUsuario'],$_POST['fNome'],$_POST['fDataCadastro'],$bPublicado,$bAtivo);
	$_SESSION['oGrupoUsuario'] = $oGrupoUsuario;
	
	$sAtributosChave = "nId,bPublicado,bAtivo";
	$_SESSION['sMsg'] = $oValidacao->verificaObjetoVazio($oGrupoUsuario,$sAtributosChave);
	if ($_SESSION['sMsg']){
		header("Location:insere_altera.php?sOP=$sOP&bErro=1&nIdGrupoUsuario=".$_POST['fIdGrupoUsuario']);
		exit();
	}
}

switch($sOP){
	case "Cadastrar":
		// TRANSACAO
		$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou o cadastro de novo grupo de usuários de nome ".$_POST['fNome'];
		$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
		$nIdGrupoUsuario = $oFachadaSeguranca->insereGrupoUsuario($oGrupoUsuario,$oTransacao,BANCO);
		if (!$nIdGrupoUsuario){
			$sObjetoAcesso = "VERIFICAR: Tentativa de cadastro de novo grupo de usuários falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou cadastrar novo grupo de usuários, porém houve erro no cadastro. Informações que seriam cadastradas: <br />";
			if($_POST['fNome'] != "")
				$sObjetoAcesso .= "Grupo de Usuário: ".$_POST['fNome']."<br />";
			
			if($bPublicado == 1)
				$sPublicadoNovo = "Sim";
			else
				$sPublicadoNovo = "Não";
			$sObjetoAcesso .= "Publicado: ".$sPublicadoNovo."<br />";

			if($bAtivo == 1)
				$sAtivoNovo = "Sim";
			else
				$sAtivoNovo = "Não";
			$sObjetoAcesso .= "Ativo: ".$sAtivoNovo."<br />";
				
			$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
			
			$_SESSION['sMsg'] = "Não foi possível inserir o Grupo de Usuários!";
			$sHeader = "insere_altera.php?sOP=$sOP&bErro=1";
		} else {
			//INSERINDO AUTOMATICAMENTE A OPCAO DE LOGIN PARA O NOVO GRUPO
			$oPermissaoLogin = $oFachadaSeguranca->inicializaPermissao(ACESSO_LOGIN,$nIdGrupoUsuario,date("Y-m-d H:i:s"),1,1);
			//TRANSACAO
			$sObjeto = "Habilitando Permissão de Login para o Grupo de Usuários ".$_POST['fNome']." de id: ".$nIdGrupoUsuario;
			$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->inserePermissao($oPermissaoLogin,$oTransacao,BANCO);
			
			//INSERINDO AUTOMATICAMENTE A OPCAO DE LOGOUT PARA O NOVO GRUPO
			$oPermissaoLogout = $oFachadaSeguranca->inicializaPermissao(ACESSO_LOGOUT,$nIdGrupoUsuario,date("Y-m-d H:i:s"),1,1);
			//TRANSACAO
			$sObjeto = "Habilitando Permissão de Logout para o Grupo de Usuários ".$_POST['fNome']." de id: ".$nIdGrupoUsuario;
			$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->inserePermissao($oPermissaoLogout,$oTransacao,BANCO);
			
			//INSERINDO AUTOMATICAMENTE A OPCAO DE ALTERAR SENHA PARA O NOVO GRUPO
			$oPermissaoAlteraSenha = $oFachadaSeguranca->inicializaPermissao(ALTERAR_SENHA,$nIdGrupoUsuario,date("Y-m-d H:i:s"),1,1);
			//TRANSACAO
			$sObjeto = "Habilitando Permissão de Alteração de Senha para o Grupo de Usuários ".$_POST['fNome']." de id: ".$nIdGrupoUsuario;
			$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->inserePermissao($oPermissaoAlteraSenha,$oTransacao,BANCO);
			
			$_SESSION['sMsg'] = "Grupo de Usuários inserido com sucesso!";
			$sHeader = "index.php?bErro=0";			
			session_unregister('oGrupoUsuario');
			$_SESSION['oGrupoUsuario'] = "";
			unset($_SESSION['oGrupoUsuario']);
			unset($_POST);
		}//if ($oFachadaSeguranca->insereGrupoUsuario($oGrupoUsuario))
	break;
	case "Alterar":
		$sHeader = "insere_altera.php?sOP=$sOP&bErro=1&nIdGrupoUsuario=".$_POST['fIdGrupoUsuario'];
		$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
		if(is_object($oGrupoUsuarioAuxiliar)){
			// TRANSACAO
			$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou alteração de Informações do Grupo de Usuários ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario()."<br /> Modificações realizadas: <br />";
			if($oGrupoUsuarioAuxiliar->getNmGrupoUsuario() != $_POST['fNome'])
				$sObjeto .= "Grupo de Usuários: ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario()." --> ".$_POST['fNome']."<br />";
			if($oGrupoUsuarioAuxiliar->getPublicado() != $bPublicado){
				if($oGrupoUsuarioAuxiliar->getPublicado() == 1)
					$sPublicadoAtual = "Sim";
				else
					$sPublicadoAtual = "Não";
					
				if($bPublicado == 1)
					$sPublicadoNovo = "Sim";
				else
					$sPublicadoNovo = "Não";
				$sObjeto .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
			}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
			if($oGrupoUsuarioAuxiliar->getAtivo() != $bAtivo){
				if($oGrupoUsuarioAuxiliar->getAtivo() == 1)
					$sAtivoAtual = "Sim";
				else
					$sAtivoAtual = "Não";
					
				if($bAtivo == 1)
					$sAtivoNovo = "Sim";
				else
					$sAtivoNovo = "Não";
				$sObjeto .= "Ativo: ".$sAtivoAtual." --> ".$sAtivoNovo."<br />";
			}//if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
			
			$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);

			if (!$oFachadaSeguranca->alteraGrupoUsuario($oGrupoUsuario,$oTransacao,BANCO)){
				//TRANSACAO
				$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de informações do grupo de usuários ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario()." falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as informações do grupo de usuários ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario().", porém houve erro na alteração. Modificações que seriam realizadas e não foram concluídas: <br />";
				if($oGrupoUsuarioAuxiliar->getNmGrupoUsuario() != $_POST['fNome'])
					$sObjetoAcesso .= "Grupo de Usuários: ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario()." --> ".$_POST['fNome']."<br />";
				if($oGrupoUsuarioAuxiliar->getPublicado() != $bPublicado){
					if($oGrupoUsuarioAuxiliar->getPublicado() == 1)
						$sPublicadoAtual = "Sim";
					else
						$sPublicadoAtual = "Não";
						
					if($bPublicado == 1)
						$sPublicadoNovo = "Sim";
					else
						$sPublicadoNovo = "Não";
					$sObjetoAcesso .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
				}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
				if($oGrupoUsuarioAuxiliar->getAtivo() != $bAtivo){
					if($oGrupoUsuarioAuxiliar->getAtivo() == 1)
						$sAtivoAtual = "Sim";
					else
						$sAtivoAtual = "Não";
						
					if($bAtivo == 1)
						$sAtivoNovo = "Sim";
					else
						$sAtivoNovo = "Não";
					$sObjetoAcesso .= "Ativo: ".$sAtivoAtual." --> ".$sAtivoNovo."<br />";
				}//if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
				$_SESSION['sMsg'] = "Não foi possível alterar o Grupo de Usuários!";
				$sHeader = "insere_altera.php?sOP=$sOP&bErro=1&nIdGrupoUsuario=".$_POST['fIdGrupoUsuario'];
			} else {
				$_SESSION['sMsg'] = "Grupo de Usuários alterado com sucesso!";
				$sHeader = "index.php?bErro=0";
				session_unregister('oGrupoUsuario');
				$_SESSION['oGrupoUsuario'] = "";
				unset($_SESSION['oGrupoUsuario']);
				unset($_POST);
			}//if ($oFachadaSeguranca->insereGrupoUsuario($oGrupoUsuario))
		} else {
			$_SESSION['sMsg'] = "Grupo de Usuários não encontrado no sistema!";
		}
	break;
	case "Excluir":		
		$bResultado = true;
		foreach($_POST['fIdGrupoUsuario'] as $nIdGrupoUsuario) {
			$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($nIdGrupoUsuario,BANCO);
			if(is_object($oGrupoUsuarioAuxiliar)){
				// TRANSACAO
				$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou a desativação do Grupo de Usuários ".$oGrupoUsuarioAuxiliar->getNmGrupoUsuario().", o que ocasiona a desativação de suas respectivas permissões!";
				$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);

				$bResultado &= $oFachadaSeguranca->desativaGrupoUsuario($nIdGrupoUsuario,$oTransacao,BANCO);
				if($bResultado == true)
					$oFachadaSeguranca->desativaPermissaoPorGrupoUsuario($nIdGrupoUsuario,BANCO);
			} else 
				$bResultado &= false;
		} //foreach($_POST['fIdGrupoUsuario'] as $nIdGrupoUsuario)
		
		if($bResultado){
			$_SESSION['sMsg'] = "Grupo de Usuários excluído com sucesso!";			
			$sHeader = "index.php?bErro=0";
		} else {
			$_SESSION['sMsg'] = "Não foi possível excluir o Grupo de Usuários";
			$sHeader = "index.php?bErro=1";
		}//if($bResultado){
	break;
	
}	

header("Location:$sHeader");

?>