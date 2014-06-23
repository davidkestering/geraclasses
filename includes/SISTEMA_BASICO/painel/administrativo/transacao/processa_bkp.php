<?php 
require_once("../../../constantes.php");
require_once(PATH."/painel/includes/valida_usuario.php");
require_once(PATH."/classes/FachadaSegurancaBD.class.php");
require_once(PATH."/classes/Validacao.class.php");

$oFachadaSeguranca = new FachadaSegurancaBD();
$oValidacao = new Validacao();

$oValidacao->printvardie($_POST);
die();

// VERIFICA AS PERMISSÕES
$sOP = (isset($_POST['sOP'])) ? $_POST['sOP'] : "";
$nIdTipoTransacaoPrincipal = $_SESSION['oLoginAdm']->recuperaTipoTransacaoPorDescricaoCategoria("Transacao",$sOP,BANCO);
$bPermissao = $_SESSION['oLoginAdm']->verificaPermissao("Transacao",$sOP,BANCO);
$nIdTipoTransacaoPrincipal = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Transacao",$sOP,BANCO);

if(!$bPermissao){
	if($sOP != "Excluir"){
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou ".$sOP." informações na gerência de transações do sistema, porém não possui permissão para isso! Segue abaixo as informações:<br />";
		if($sOP == "Cadastrar"){
			if(isset($_POST['fNomeCategoria']))
				$sObjetoAcesso .= "Categoria de Tipo de Transação: ".$_POST['fNomeCategoria']."<br />";
			if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
				foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
					$sObjetoAcesso .= "Tipo de Transação: ".$sTipoTransacao."<br />";
				}//foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
			}//if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
		}else{
			if($sOP == "Alterar"){
				$oCategoriaTipoTransacaoAuxiliar = $oFachadaSeguranca->recuperaGrupoUsuario($_POST['fIdCategoriaTipoTransacao'],BANCO);
				if(is_object($oCategoriaTipoTransacaoAuxiliar)){
					// TRANSACAO
					if($oCategoriaTipoTransacaoAuxiliar->getDescricao() != $_POST['fNomeCategoria'])
						$sObjetoAcesso .= "Categoria de Tipo de Transação: ".$oCategoriaTipoTransacaoAuxiliar->getDescricao()." --> ".$_POST['fNomeCategoria']."<br />";
					if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
						foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
							$sObjetoAcesso .= "Tipo de Transação: ".$sTipoTransacao."<br />";
						}//foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
					}//if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
				}//if(is_object($oGrupoUsuarioAuxiliar)){
			}//if($sOP == "Alterar"){
		}//if($sOP == "Cadastrar"){
	}else{
		$sObjetoAcesso = "VERIFICAR: Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou excluir informações da gerência de transações do sistema, porém não possui permissão para isso! Segue abaixo as informações:<br />";
		if(count($_POST['fIdCategoriaTipoTransacao']) > 0){
			foreach($_POST['fIdCategoriaTipoTransacao'] as $nIdCategoriaTipoTransacao) {
				if ($oFachadaSeguranca->presenteCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO)){
					// TRANSACAO
					$oCategoriaTipoTransacaoAuxiliarAcesso = $oFachadaSeguranca->recuperaCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO);
					if(is_object($oCategoriaTipoTransacaoAuxiliarAcesso)){
						$sObjetoAcesso .= "Tentou excluir as Categorias de Tipos de Transações ".$oCategoriaTipoTransacaoAuxiliarAcesso->getDescricao()." de id=".$oCategoriaTipoTransacaoAuxiliarAcesso->getId()."<br />";
					}//if(is_object($oCategoriaTipoTransacaoAuxiliarAcesso)){
				}//if ($oFachadaSeguranca->presenteCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO)){
			}//foreach($_POST['fIdCategoriaTipoTransacao'] as $nIdCategoriaTipoTransacao) {
		}else{
			$sObjetoAcesso .= "VERIFICAR: Não houve envio de ids de Categorias de Tipos de Transações para exclusão!???<br />";
		}//if(count($_POST['fIdCategoriaTipoTransacao']) > 0){
	}//if($sOP != "Excluir"){
	
	$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
	$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
	$_SESSION['sMsgPermissao'] = ACESSO_NEGADO;
	header("location: ../../index.php?bErro=1");
	exit();
}//if(!$bPermissao){

if (isset($sOP) && $sOP != "Excluir"){
	$bPublicado = (isset($_POST['fPublicado']) && $_POST['fPublicado'] == 1) ? "1" : "0";
	$bAtivo = (isset($_POST['fAtivo']) && $_POST['fAtivo'] == 1) ? "1" : "0";
	
	$oCategoriaTipoTransacao = $oFachadaSeguranca->inicializaCategoriaTipoTransacao($_POST['fIdCategoriaTipoTransacao'],$_POST['fNomeCategoria'],date("Y-m-d H:i:s"),$bPublicado,$bAtivo);
	$_SESSION['oCategoriaTipoTransacao'] = $oCategoriaTipoTransacao;
	
	$sAtributosChave = "nId,sDescricao,bPublicado,bAtivo";
	$_SESSION['sMsgTransacao'] = $oValidacao->verificaObjetoVazio($oCategoriaTipoTransacao,$sAtributosChave);
	if ($_SESSION['sMsgTransacao']){
		header("Location:insere_altera.php?sOP=$sOP&bErro=1&nIdCategoriaTransacao=".$_POST['fIdCategoriaTipoTransacao']);
		exit();
	}
}

switch($sOP){
	case "Cadastrar":
		// TRANSACAO
		$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou o cadastro de uma nova categoria de tipo de transação com o Nome: ".$_POST['fNomeCategoria']."<br />Segue abaixo os tipos de transações vinculadas:<br />";
		if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
			foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
				$sObjeto .= "Tipo de Transação: ".$sTipoTransacao."<br />";
			}//foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
		}//if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
		$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
		$nIdCategoriaTipoTransacao = $oFachadaSeguranca->insereCategoriaTipoTransacao($oCategoriaTipoTransacao,$oTransacao,BANCO);
		if (!$nIdCategoriaTipoTransacao){
			$sObjetoAcesso = "VERIFICAR: Tentativa de cadastro de nova categoria de tipo de transação falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou cadastrar uma nova categoria de tipo de transação com o Nome: ".$_POST['fNomeCategoria'].", porém houve erro no cadastro. Segue abaixo os tipos de transações que seriam vinculadas: <br />";
			if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
				foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
					$sObjetoAcesso .= "Tipo de Transação: ".$sTipoTransacao."<br />";
				}//foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
			}//if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
				
			$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
			
			$_SESSION['sMsgTransacao'] = "Não foi possível cadastrar a transação. Verifique se já existe o nome da categoria!";
			$sHeader = "insere_altera.php?sOP=$sOP&bErro=1";
		} else {
			if(count($_POST['fTipoTransacao']) > 0){
				foreach($_POST['fTipoTransacao'] as $sTransacao){
					if($sTransacao != ""){
						$oTipoTransacao = $oFachadaSeguranca->inicializaTipoTransacao("",$nIdCategoriaTipoTransacao,$sTransacao,date("Y-m-d H:i:s"),$bPublicado,$bAtivo);
						//TRANSACAO
						$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." cadastrou um novo tipo de transação ".$sTransacao." para a Categoria de Tipo de Transação ".$_POST['fNomeCategoria'];
						$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
						$nIdTipoTransacao = $oFachadaSeguranca->insereTipoTransacao($oTipoTransacao,$oTransacao,BANCO);
						
						if($nIdTipoTransacao){
							//HABILITANDO PERMISSAO PARA O GRUPO ADMINISTRADOR
							$oPermissao = $oFachadaSeguranca->inicializaPermissao($nIdTipoTransacao,GRUPO_ADMINISTRADOR,date("Y-m-d H:i:s"),1,1);
							//TRANSACAO
							$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." habilitou a permissão de ".$sTransacao." na seção ".$_POST['fNomeCategoria']." para o Grupo de Usuários Administrador";
							$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
							$oFachadaSeguranca->inserePermissao($oPermissao,$oTransacao,BANCO);
						}//if($nIdTipoTransacao){
					}//if($sTransacao != ""){
				}//foreach($_POST['fTipoTransacao'] as $sTransacao){
			}//if(count($_POST['fTipoTransacao']) > 0){
			
			$_SESSION['sMsgTransacao'] = "Transação cadastrada com sucesso!";
			$sHeader = "visualiza_transacao.php?bErro=0&fIdCategoriaTransacao=".$nIdCategoriaTipoTransacao."&fConsulta=1";
			session_unregister('oCategoriaTipoTransacao');
			$_SESSION['oCategoriaTipoTransacao'] = "";
		}//if ($oFachadaSeguranca->insereUsuario($oUsuario))
	break;
	case "Alterar":
		$sHeader = "insere_altera.php?sOP=$sOP&bErro=1&nIdCategoriaTransacao=".$_POST['fIdCategoriaTipoTransacao'];
		$oCategoriaTipoTransacaoAuxiliar = $oFachadaSeguranca->recuperaCategoriaTipoTransacao($_POST['fIdCategoriaTipoTransacao'],BANCO);
		if(is_object($oCategoriaTipoTransacaoAuxiliar)){
			// TRANSACAO
			$sObjeto = "Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." efetuou alteração de Informações da Categoria de Tipo de Transações ".$oCategoriaTipoTransacaoAuxiliar->getDescricao()."<br /> Modificações realizadas: <br />";
			if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
				foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
					$sObjeto .= "Tipo de Transação: ".$sTipoTransacao."<br />";
				}//foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
			}//if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
			
			$oTransacao = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjeto,IP_USUARIO,DATAHORA,1,1);
			
			if (!$oFachadaSeguranca->alteraCategoriaTipoTransacao($oCategoriaTipoTransacao,$oTransacao,BANCO)){
				//TRANSACAO
				$sObjetoAcesso = "VERIFICAR: Tentativa de alteração de informações da Categoria de Tipo de Transações ".$oCategoriaTipoTransacaoAuxiliar->getDescricao()." falhou. Usuário ".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()." tentou alterar as informações da Categoria de Tipo de Transações ".$oCategoriaTipoTransacaoAuxiliar->getDescricao().", porém houve erro na alteração. Modificações que seriam realizadas e não foram concluídas: <br />";
				if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
					foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
						$sObjetoAcesso .= "Tipo de Transação: ".$sTipoTransacao."<br />";
					}//foreach($_POST['fTipoTransacao'] as $sTipoTransacao){
				}//if(isset($_POST['fTipoTransacao']) && count($_POST['fTipoTransacao']) > 0){
				
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoPrincipal,$_SESSION['oLoginAdm']->getIdUsuario(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,BANCO);
				
				$_SESSION['sMsgTransacao'] = "Não foi possível alterar a transação. Verifique se já existe o nome da categoria!";
				$sHeader = "insere_altera.php?sOP=$sOP&bErro=1&nIdCategoriaTransacao=".$_POST['fIdCategoriaTipoTransacao'];
			} else {
				
				$vsTransacaoAuxiliar = array();
				if(count($_POST['fTipoTransacao']) > 0){
					$voTipoTransacaoAuxiliar = $oFachadaSeguranca->recuperaTipoTransacaoPorCategoria($_POST['fIdCategoriaTipoTransacao'],BANCO);

					if(count($voTipoTransacaoAuxiliar) > 0){
						foreach($voTipoTransacaoAuxiliar as $oTipoTransacaoAuxiliar){
							if(is_object($oTipoTransacaoAuxiliar))
								array_push($vsTransacaoAuxiliar,$oTipoTransacaoAuxiliar->getTransacao());
						}
					}
					
					/*if(count($voTipoTransacaoAuxiliar) > 0){
						foreach($voTipoTransacaoAuxiliar as $oTipoTransacaoAuxiliar){
							if(is_object($oTipoTransacaoAuxiliar)){
								$nIdTipoTransacao = $oTipoTransacaoAuxiliar->getId();
								if($nIdTipoTransacao)
									$oFachadaSeguranca->excluiPermissaoPorTipoTransacao($nIdTipoTransacao,BANCO);
							}
						}
						$oFachadaSeguranca->excluiTipoTransacaoPorCategoria($_POST['fIdCategoriaTipoTransacao'],BANCO);
					}*/
					
					foreach($_POST['fTipoTransacao'] as $sTransacao){
						if($sTransacao != ""){
							if(!in_array($sTransacao,$vsTransacaoAuxiliar)){
								$oTipoTransacao = $oFachadaSeguranca->inicializaTipoTransacao($nId,$_POST['fIdCategoriaTipoTransacao'],$sTransacao);
								$nIdTipoTransacao = $oFachadaSeguranca->insereTipoTransacao($oTipoTransacao,BANCO);
							
								if($nIdTipoTransacao){
									$oPermissao = $oFachadaSeguranca->inicializaPermissao($nIdTipoTransacao,1);
									$oFachadaSeguranca->inserePermissao($oPermissao,BANCO);
								}
							}
						}
					}
					
				}
				
				$_SESSION['sMsgTransacao'] = "Transação alterada com sucesso!";
				$sHeader = "visualiza_transacao.php?bErro=0&fIdCategoriaTransacao=".$_POST['fIdCategoriaTipoTransacao']."&fConsulta=1";
				$_SESSION['oCategoriaTipoTransacao'] = "";
				unset($_SESSION['oCategoriaTipoTransacao']);		
			}//if ($oFachadaSeguranca->insereUsuario($oUsuario))
		} else {
			$_SESSION['sMsgTransacao'] = "Transação não encontrada no sistema!";
		}
	break;
	case "Excluir":
		$bResultado = true;
		foreach($_POST['fIdCategoriaTipoTransacao'] as $nIdCategoriaTipoTransacao) {
			if ($oFachadaSeguranca->presenteCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO)){
				$voTipoTransacaoAuxiliar = $oFachadaSeguranca->recuperaTipoTransacaoPorCategoria($nIdCategoriaTipoTransacao,BANCO);
				if(count($voTipoTransacaoAuxiliar) > 0){
					foreach($voTipoTransacaoAuxiliar as $oTipoTransacaoAuxiliar){
						if(is_object($oTipoTransacaoAuxiliar)){
							$nIdTipoTransacao = $oTipoTransacaoAuxiliar->getId();
							if($nIdTipoTransacao)
								$oFachadaSeguranca->excluiPermissaoPorTipoTransacao($nIdTipoTransacao,BANCO);
						}
					}
					$oFachadaSeguranca->excluiTipoTransacaoPorCategoria($nIdCategoriaTipoTransacao,BANCO);
				}
				$bResultado &= $oFachadaSeguranca->excluiCategoriaTipoTransacao($nIdCategoriaTipoTransacao,BANCO);
			} else 
				$bResultado &= false;
		}//foreach($_POST['fIdCategoriaTipoTransacao'] as $nIdCategoriaTipoTransacao) {
		
		if($bResultado){
			$_SESSION['sMsgTransacao'] = "Transação excluída com sucesso!";			
			$sHeader = "visualiza_transacao.php?bErro=0";
		} else {
			$_SESSION['sMsgTransacao'] = "Não foi possível excluir a Transação";
			$sHeader = "visualiza_transacao.php?bErro=1";
		}//if($bResultado){
	break;
}	
header("Location:$sHeader");
?>