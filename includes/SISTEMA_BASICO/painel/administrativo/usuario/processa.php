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
$nIdTipoTransacao = $_SESSION['oLoginAdm']->recuperaTipoTransacaoPorDescricaoCategoria("Usuario",$sOP,BANCO);
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Usuario",$sOP,BANCO);
$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Usuario",$sOP,BANCO);

if(!$bPermissao){
	//TRANSACAO
	$bPublicadoAcesso = (isset($_POST['fPublicado']) && $_POST['fPublicado'] == 1) ? "1" : "0";
	$bAtivoAcesso = (isset($_POST['fAtivo']) && $_POST['fAtivo'] == 1) ? "1" : "0";
	
	if($sOP != "Excluir"){
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou ".$sOP." informações na gerência de usuários, porém não possui permissão para isso! Segue abaixo as informações:<br />";
		if($sOP == "Cadastrar"){
			if(isset($_POST['fNome']))
				$sObjetoAcesso .= "Usuário: ".$_POST['fNome']."<br />";
			if(isset($_POST['fLogin']))
				$sObjetoAcesso .= "Login: ".$_POST['fLogin']."<br />";
			if(isset($_POST['fIdGrupoUsuario'])){
				$oGrupoUsuarioAcesso = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
				if(is_object($oGrupoUsuarioAcesso))
					$sGrupoUsuarioAcesso = $oGrupoUsuarioAcesso->getNmGrupoUsuario();
				$sObjetoAcesso .= "Grupo de Usuário: ".$sGrupoUsuarioAcesso."<br />";
			}//if(isset($_POST['fIdGrupoUsuario'])){
			if(isset($_POST['fSenha']))
				$sObjetoAcesso .= "Senha: ".$_POST['fSenha']."<br />";
			if(isset($_POST['fEmail']))
				$sObjetoAcesso .= "Email: ".$_POST['fEmail']."<br />";
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
				$oUsuarioAuxiliar = $oFachadaSeguranca->recuperaUsuario($_POST['fIdUsuario'],BANCO);
				if(is_object($oUsuarioAuxiliar)){
					$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($oUsuarioAuxiliar->getIdGrupoUsuario(),BANCO);
					if(is_object($oGrupoUsuarioAuxiliar))
						$sGrupoUsuarioAuxiliar = $oGrupoUsuarioAuxiliar->getNmGrupoUsuario();
					
					$oGrupoUsuarioAuxiliarNovo = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
					if(is_object($oGrupoUsuarioAuxiliarNovo))
						$sGrupoUsuarioAuxiliarNovo = $oGrupoUsuarioAuxiliarNovo->getNmGrupoUsuario();
						
					if($oUsuarioAuxiliar->getNmUsuario() != $_POST['fNome'])
						$sObjetoAcesso .= "Usuário: ".$oUsuarioAuxiliar->getNmUsuario()." --> ".$_POST['fNome']."<br />";
					if($oUsuarioAuxiliar->getLogin() != $_POST['fLogin'])
						$sObjetoAcesso .= "Login: ".$oUsuarioAuxiliar->getLogin()." --> ".$_POST['fLogin']."<br />";
					if($oUsuarioAuxiliar->getIdGrupoUsuario() != $_POST['fIdGrupoUsuario'])
						$sObjetoAcesso .= "Grupo de Usuário: ".$sGrupoUsuarioAuxiliar." --> ".$sGrupoUsuarioAuxiliarNovo."<br />";
					if($oUsuarioAuxiliar->getSenha() != $_POST['fSenha'] && $_POST['fSenha'] != "")
						$sObjetoAcesso .= "Senha: ".$oUsuarioAuxiliar->getSenha()." --> ".$_POST['fSenha']."<br />";
					if($oUsuarioAuxiliar->getEmail() != $_POST['fEmail'])
						$sObjetoAcesso .= "Email(s): ".$oUsuarioAuxiliar->getEmail()." --> ".$_POST['fEmail']."<br />";
					if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
						if($oUsuarioAuxiliar->getPublicado() == 1)
							$sPublicadoAtual = "Sim";
						else
							$sPublicadoAtual = "Não";
							
						if($bPublicado == 1)
							$sPublicadoNovo = "Sim";
						else
							$sPublicadoNovo = "Não";
						$sObjetoAcesso .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
					}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
					if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
						if($oUsuarioAuxiliar->getAtivo() == 1)
							$sAtivoAtual = "Sim";
						else
							$sAtivoAtual = "Não";
							
						if($bAtivo == 1)
							$sAtivoNovo = "Sim";
						else
							$sAtivoNovo = "Não";
						$sObjetoAcesso .= "Ativo: ".$sAtivoAtual." --> ".$sAtivoNovo."<br />";
					}//if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				}//if(is_object($oUsuarioAuxiliar)){
			}//if($sOP == "Alterar"){
		}//if($sOP == "Cadastrar"){
	}else{
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou excluir informações da gerência de usuários, porém não possui permissão para isso! Segue abaixo as informações:<br />";
		if(count($_POST['fIdUsuario']) > 0){
			foreach($_POST['fIdUsuario'] as $nIdUsuario) {
				if ($oFachadaSeguranca->presenteUsuario($nIdUsuario,BANCO)){
					// TRANSACAO
					$oUsuarioAuxiliarAcesso = $oFachadaSeguranca->recuperaUsuario($nIdUsuario,BANCO);
					if(is_object($oUsuarioAuxiliarAcesso)){
						$sObjetoAcesso .= "Tentou excluir o usuário ".$oUsuarioAuxiliarAcesso->getNmUsuario()." de id=".$oUsuarioAuxiliarAcesso->getId()."<br />";
					}//if(is_object($oUsuarioAuxiliarAcesso)){
				}//if ($oFachadaSeguranca->presenteUsuario($nIdUsuario,BANCO)){
			}//foreach($_POST['fIdUsuario'] as $nIdUsuario) {
		}else{
			$sObjetoAcesso .= "Não houve envio de ids de usuários para exclusão!???<br />";
		}//if(count($_POST['fIdUsuario']) > 0){
	}//if($sOP != "Excluir"){
	
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}//if(!$bPermissao){

//CASO ALTERE A PRÓPRIA IDENTIFICAÇÃO NÃO CHECA A PERMISSÃO E ALTERA A OPERAÇÃO
if(isset($_SESSION['oLoginAdm']) && $_SESSION['oLoginAdm']->getIdUsuario() == $_POST['fIdUsuario']){
	$sOP = "AlterarUsuario";
}//if($_SESSION['oLoginAdm']->getIdUsuario() != $nIdUsuario){

// REGISTRANDO NA SESSÃO
if (isset($sOP) && $sOP != "Excluir"){
	$bLogado = (isset($_POST['fLogado']) && $_POST['fLogado'] == 1) ? "1" : "0";
	$bPublicado = (isset($_POST['fPublicado']) && $_POST['fPublicado'] == 1) ? "1" : "0";
	$bAtivo = (isset($_POST['fAtivo']) && $_POST['fAtivo'] == 1) ? "1" : "0";
	
	$oUsuario = $oFachadaSeguranca->inicializaUsuario($_POST['fIdUsuario'],$_POST['fIdGrupoUsuario'],$_POST['fNome'],$_POST['fLogin'],$_POST['fSenha'],$_POST['fEmail'],$bLogado,$_POST['fDataCadastro'],$bPublicado,$bAtivo);
	//print_r($oUsuario);
	//die();
	$_SESSION['oUsuario'] = $oUsuario;
	
	$sAtributosChave = "nId,bLogado,sEmail,sSenha,bPublicado,bAtivo";
	$_SESSION['sMsg'] = $oValidacao->verificaObjetoVazio($oUsuario,$sAtributosChave);
	if ($_SESSION['sMsg']){
		header("Location:insere_altera.php?sOP=$sOP&bErro=1&nIdUsuario=".$_POST['fIdUsuario']);
		exit();
	}//if ($_SESSION['sMsg']){
	
	if (isset($_POST['fSenha']) && $_POST['fSenha'] != "") {
		if ($_POST['fSenha'] != $_POST['fSenhaConfirmacao']) {
			$_SESSION['sMsg'] = "A senha precisa ser igual a confirmação. Tente novamente!";
			header("Location:insere_altera.php?sOP=$sOP&bErro=1&nIdUsuario=".$_POST['fIdUsuario']);
			exit();
		}//if ($_POST['fSenha'] != $_POST['fSenhaConfirmacao']) {
	}//if (isset($_POST['fSenha']) && $_POST['fSenha'] != "") {
}//if (isset($sOP) && $sOP != "Excluir"){

switch($sOP){
	case "Cadastrar":
		// TRANSACAO
		$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou o cadastro de novo usuário de nome ".$_POST['fNome']." com o login ".$_POST['fLogin'];
		$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
		$nId = $oFachadaSeguranca->insereUsuario($oUsuario,$oTransacao,BANCO);
		if (!$nId){
			$oGrupoUsuarioAuxiliarNovo = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
			if(is_object($oGrupoUsuarioAuxiliarNovo))
				$sGrupoUsuarioAuxiliarNovo = $oGrupoUsuarioAuxiliarNovo->getNmGrupoUsuario();
				
			$sObjetoAcesso = "VERIFICAR: Tentativa de cadastro de novo usuário falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou cadastrar novo usuário, porém houve erro no cadastro. Informações que seriam cadastradas: <br />";
			if($_POST['fNome'] != "")
				$sObjetoAcesso .= "Usuário: ".$_POST['fNome']."<br />";
			if($_POST['fLogin'] != "")
				$sObjetoAcesso .= "Login: ".$_POST['fLogin']."<br />";
			if($_POST['fIdGrupoUsuario'] != "")
				$sObjetoAcesso .= "Grupo de Usuário: ".$sGrupoUsuarioAuxiliarNovo."<br />";
			if($_POST['fSenha'] != "")
				$sObjetoAcesso .= "Senha: ".$_POST['fSenha']."<br />";
			if($_POST['fEmail'] != "")
				$sObjetoAcesso .= "Email(s): ".$_POST['fEmail']."<br />";

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
			$_SESSION['sMsg'] = "Não foi possível inserir o usuário!";
			$sHeader = "insere_altera.php?sOP=$sOP&bErro=1";
		} else {
			$_SESSION['sMsg'] = "Usuário inserido com sucesso!";
			$sHeader = "index.php?bErro=0";
			session_unregister('oUsuario');
			$_SESSION['oUsuario'] = "";
			unset($_SESSION['oUsuario']);
			unset($_POST);
		}//if (!$nId){
	break;
	case "Alterar":
		$sHeader = "insere_altera.php?sOP=$sOP&bErro=1&nIdUsuario=".$_POST['fIdUsuario'];
		$oUsuarioAuxiliar = $oFachadaSeguranca->recuperaUsuario($_POST['fIdUsuario'],BANCO);
		if(is_object($oUsuarioAuxiliar)){
			$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($oUsuarioAuxiliar->getIdGrupoUsuario(),BANCO);
			if(is_object($oGrupoUsuarioAuxiliar))
				$sGrupoUsuarioAuxiliar = $oGrupoUsuarioAuxiliar->getNmGrupoUsuario();
			
			$oGrupoUsuarioAuxiliarNovo = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
			if(is_object($oGrupoUsuarioAuxiliarNovo))
				$sGrupoUsuarioAuxiliarNovo = $oGrupoUsuarioAuxiliarNovo->getNmGrupoUsuario();
			
			// TRANSACAO
			$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou alteração de Informações do Usuário ".$oUsuarioAuxiliar->getNmUsuario()."<br /> Modificações realizadas: <br />";
			if($oUsuarioAuxiliar->getNmUsuario() != $_POST['fNome'])
				$sObjeto .= "Usuário: ".$oUsuarioAuxiliar->getNmUsuario()." --> ".$_POST['fNome']."<br />";
			if($oUsuarioAuxiliar->getLogin() != $_POST['fLogin'])
				$sObjeto .= "Login: ".$oUsuarioAuxiliar->getLogin()." --> ".$_POST['fLogin']."<br />";
			if($oUsuarioAuxiliar->getIdGrupoUsuario() != $_POST['fIdGrupoUsuario'])
				$sObjeto .= "Grupo de Usuário: ".$sGrupoUsuarioAuxiliar." --> ".$sGrupoUsuarioAuxiliarNovo."<br />";
			if($oUsuarioAuxiliar->getSenha() != $_POST['fSenha'] && $_POST['fSenha'] != "")
				$sObjeto .= "Senha: ".$oUsuarioAuxiliar->getSenha()." --> ".$_POST['fSenha']."<br />";
			if($oUsuarioAuxiliar->getEmail() != $_POST['fEmail'])
				$sObjeto .= "Email(s): ".$oUsuarioAuxiliar->getEmail()." --> ".$_POST['fEmail']."<br />";
			if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
				if($oUsuarioAuxiliar->getPublicado() == 1)
					$sPublicadoAtual = "Sim";
				else
					$sPublicadoAtual = "Não";
					
				if($bPublicado == 1)
					$sPublicadoNovo = "Sim";
				else
					$sPublicadoNovo = "Não";
				$sObjeto .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
			}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
			if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				if($oUsuarioAuxiliar->getAtivo() == 1)
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

			// CASO NÃO SEJA INFORMADO UMA NOVA SENHA DEVE SETAR A QUE ESTÁ NO BANCO
			if(trim($_POST['fSenha']) == "")
				$oUsuario->setSenha($oUsuarioAuxiliar->getSenha());

			if (!$oFachadaSeguranca->alteraUsuario($oUsuario,$oTransacao,BANCO)){
				//TRANSACAO
				$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de informações do usuário ".$oUsuarioAuxiliar->getNmUsuario()." falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as informações do usuário ".$oUsuarioAuxiliar->getNmUsuario().", porém houve erro na alteração. Modificações que seriam realizadas e não foram concluídas: <br />";
				if($oUsuarioAuxiliar->getNmUsuario() != $_POST['fNome'])
					$sObjetoAcesso .= "Usuário: ".$oUsuarioAuxiliar->getNmUsuario()." --> ".$_POST['fNome']."<br />";
				if($oUsuarioAuxiliar->getLogin() != $_POST['fLogin'])
					$sObjetoAcesso .= "Login: ".$oUsuarioAuxiliar->getLogin()." --> ".$_POST['fLogin']."<br />";
				if($oUsuarioAuxiliar->getIdGrupoUsuario() != $_POST['fIdGrupoUsuario'])
					$sObjetoAcesso .= "Grupo de Usuário: ".$sGrupoUsuarioAuxiliar." --> ".$sGrupoUsuarioAuxiliarNovo."<br />";
				if($oUsuarioAuxiliar->getSenha() != $_POST['fSenha'] && $_POST['fSenha'] != "")
					$sObjetoAcesso .= "Senha: ".$oUsuarioAuxiliar->getSenha()." --> ".$_POST['fSenha']."<br />";
				if($oUsuarioAuxiliar->getEmail() != $_POST['fEmail'])
					$sObjetoAcesso .= "Email(s): ".$oUsuarioAuxiliar->getEmail()." --> ".$_POST['fEmail']."<br />";
				if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
					if($oUsuarioAuxiliar->getPublicado() == 1)
						$sPublicadoAtual = "Sim";
					else
						$sPublicadoAtual = "Não";
						
					if($bPublicado == 1)
						$sPublicadoNovo = "Sim";
					else
						$sPublicadoNovo = "Não";
					$sObjetoAcesso .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
				}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
				if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
					if($oUsuarioAuxiliar->getAtivo() == 1)
						$sAtivoAtual = "Sim";
					else
						$sAtivoAtual = "Não";
						
					if($bAtivo == 1)
						$sAtivoNovo = "Sim";
					else
						$sAtivoNovo = "Não";
					$sObjetoAcesso .= "Ativo: ".$sAtivoAtual." --> ".$sAtivoNovo."<br />";
				}//if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
				$_SESSION['sMsg'] = "Não foi possível alterar o usuário!";
				$sHeader = "insere_altera.php?sOP=$sOP&bErro=1&nIdUsuario=".$_POST['fIdUsuario'];
			} else {
				$_SESSION['sMsg'] = "Usuário alterado com sucesso!";
				$sHeader = "index.php?bErro=0";
				session_unregister('oUsuario');
				$_SESSION['oUsuario'] = "";
				unset($_SESSION['oUsuario']);
				unset($_POST);		
			}//if ($oFachadaSeguranca->insereUsuario($oUsuario))
		} else {
			$_SESSION['sMsg'] = "Usuário não encontrado no sistema!";
		}//if(is_object($oUsuarioAuxiliar)){
	break;
	case "AlterarUsuario":
		$sHeader = "insere_altera.php?sOP=Alterar&bErro=1&nIdUsuario=".$_SESSION['oLoginAdm']->getIdUsuario();
		$oUsuarioAuxiliar = $oFachadaSeguranca->recuperaUsuario($_SESSION['oLoginAdm']->getIdUsuario(),BANCO);
		if(is_object($oUsuarioAuxiliar)){
			$oGrupoUsuarioAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($oUsuarioAuxiliar->getIdGrupoUsuario(),BANCO);
			if(is_object($oGrupoUsuarioAuxiliar))
				$sGrupoUsuarioAuxiliar = $oGrupoUsuarioAuxiliar->getNmGrupoUsuario();
			
			$oGrupoUsuarioAuxiliarNovo = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdGrupoUsuario'],BANCO);
			if(is_object($oGrupoUsuarioAuxiliarNovo))
				$sGrupoUsuarioAuxiliarNovo = $oGrupoUsuarioAuxiliarNovo->getNmGrupoUsuario();
			
			// TRANSACAO
			$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Usuario","Alterar",BANCO);
			
			$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou alteração de Informações do Usuário ".$oUsuarioAuxiliar->getNmUsuario()."<br /> Modificações realizadas: <br />";
			if($oUsuarioAuxiliar->getNmUsuario() != $_POST['fNome'])
				$sObjeto .= "Usuário: ".$oUsuarioAuxiliar->getNmUsuario()." --> ".$_POST['fNome']."<br />";
			if($oUsuarioAuxiliar->getLogin() != $_POST['fLogin'])
				$sObjeto .= "Login: ".$oUsuarioAuxiliar->getLogin()." --> ".$_POST['fLogin']."<br />";
			if($oUsuarioAuxiliar->getIdGrupoUsuario() != $_POST['fIdGrupoUsuario'])
				$sObjeto .= "Grupo de Usuário: ".$sGrupoUsuarioAuxiliar." --> ".$sGrupoUsuarioAuxiliarNovo."<br />";
			if($oUsuarioAuxiliar->getSenha() != $_POST['fSenha'] && $_POST['fSenha'] != "")
				$sObjeto .= "Senha: ".$oUsuarioAuxiliar->getSenha()." --> ".$_POST['fSenha']."<br />";
			if($oUsuarioAuxiliar->getEmail() != $_POST['fEmail'])
				$sObjeto .= "Email(s): ".$oUsuarioAuxiliar->getEmail()." --> ".$_POST['fEmail']."<br />";
			if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
				if($oUsuarioAuxiliar->getPublicado() == 1)
					$sPublicadoAtual = "Sim";
				else
					$sPublicadoAtual = "Não";
					
				if($bPublicado == 1)
					$sPublicadoNovo = "Sim";
				else
					$sPublicadoNovo = "Não";
				$sObjeto .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
			}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
			if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				if($oUsuarioAuxiliar->getAtivo() == 1)
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

			// CASO NÃO SEJA INFORMADO UMA NOVA SENHA DEVE SETAR A QUE ESTÁ NO BANCO
			if(trim($_POST['fSenha']) == "")
				$oUsuario->setSenha($oUsuarioAuxiliar->getSenha());
			
			if (!$oFachadaSeguranca->alteraUsuario($oUsuario,$oTransacao,BANCO)){
				//TRANSACAO
				$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de informações do usuário ".$oUsuarioAuxiliar->getNmUsuario()." falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as informações do usuário ".$oUsuarioAuxiliar->getNmUsuario().", porém houve erro na alteração. Modificações que seriam realizadas e não foram concluídas: <br />";
				if($oUsuarioAuxiliar->getNmUsuario() != $_POST['fNome'])
					$sObjetoAcesso .= "Usuário: ".$oUsuarioAuxiliar->getNmUsuario()." --> ".$_POST['fNome']."<br />";
				if($oUsuarioAuxiliar->getLogin() != $_POST['fLogin'])
					$sObjetoAcesso .= "Login: ".$oUsuarioAuxiliar->getLogin()." --> ".$_POST['fLogin']."<br />";
				if($oUsuarioAuxiliar->getIdGrupoUsuario() != $_POST['fIdGrupoUsuario'])
					$sObjetoAcesso .= "Grupo de Usuário: ".$sGrupoUsuarioAuxiliar." --> ".$sGrupoUsuarioAuxiliarNovo."<br />";
				if($oUsuarioAuxiliar->getSenha() != $_POST['fSenha'] && $_POST['fSenha'] != "")
					$sObjetoAcesso .= "Senha: ".$oUsuarioAuxiliar->getSenha()." --> ".$_POST['fSenha']."<br />";
				if($oUsuarioAuxiliar->getEmail() != $_POST['fEmail'])
					$sObjetoAcesso .= "Email(s): ".$oUsuarioAuxiliar->getEmail()." --> ".$_POST['fEmail']."<br />";
				if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
					if($oUsuarioAuxiliar->getPublicado() == 1)
						$sPublicadoAtual = "Sim";
					else
						$sPublicadoAtual = "Não";
						
					if($bPublicado == 1)
						$sPublicadoNovo = "Sim";
					else
						$sPublicadoNovo = "Não";
					$sObjetoAcesso .= "Publicado: ".$sPublicadoAtual." --> ".$sPublicadoNovo."<br />";
				}//if($oUsuarioAuxiliar->getPublicado() != $bPublicado){
				if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
					if($oUsuarioAuxiliar->getAtivo() == 1)
						$sAtivoAtual = "Sim";
					else
						$sAtivoAtual = "Não";
						
					if($bAtivo == 1)
						$sAtivoNovo = "Sim";
					else
						$sAtivoNovo = "Não";
					$sObjetoAcesso .= "Ativo: ".$sAtivoAtual." --> ".$sAtivoNovo."<br />";
				}//if($oUsuarioAuxiliar->getAtivo() != $bAtivo){
				
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
				$_SESSION['sMsg'] = "Não foi possível alterar o usuário!";
				$sHeader = "insere_altera.php?sOP=Alterar&bErro=1&nIdUsuario=".$_SESSION['oLoginAdm']->getIdUsuario();
			} else {
				$_SESSION['sMsg'] = "Usuário alterado com sucesso!";
				$sHeader = "index.php?bErro=0";
				session_unregister('oUsuario');
				$_SESSION['oUsuario'] = "";
				unset($_SESSION['oUsuario']);
				unset($_POST);
			}//if ($oFachadaSeguranca->insereUsuario($oUsuario))
		} else {
			$_SESSION['sMsg'] = "Usuário não encontrado no sistema!";
		}//if(is_object($oUsuarioAuxiliar)){
	break;
	case "Excluir":
		$bResultado = true;
		foreach($_POST['fIdUsuario'] as $nIdUsuario) {
			if ($oFachadaSeguranca->presenteUsuario($nIdUsuario,BANCO)){
				// TRANSACAO
				$oUsuarioAuxiliar = $oFachadaSeguranca->recuperaUsuario($nIdUsuario,BANCO);				
				$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou a desativação do Usuário ".$oUsuarioAuxiliar->getNmUsuario()." com o login ".$oUsuarioAuxiliar->getLogin();
				$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacao,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);

				$bResultado &= $oFachadaSeguranca->desativaUsuario($nIdUsuario,$oTransacao,BANCO);
			} else {
				$bResultado &= false;
			}//if ($oFachadaSeguranca->presenteUsuario($nIdUsuario,BANCO)){
		} //foreach($_POST['fIdUsuario'] as $nIdUsuario)
		
		if($bResultado){
			$_SESSION['sMsg'] = "Usuário excluído com sucesso!";			
			$sHeader = "index.php?bErro=0";
		} else {
			$_SESSION['sMsg'] = "Não foi possível excluir o Usuário";
			$sHeader = "index.php?bErro=1";
		}//if($bResultado){
	break;
}	
header("Location:$sHeader");
?>