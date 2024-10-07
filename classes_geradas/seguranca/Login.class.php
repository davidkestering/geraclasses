<?php
//á
require_once("FachadaSegurancaBD.class.php");

class Login {
	var $oUsuario;
	var $vPermissao;
	
	function setUsuario($oUsuario){
		$this->oUsuario = $oUsuario;
	}
	
	function getUsuario(){
		return $this->oUsuario;
	}
	
	function setvPermissao($vPermissao){
		$this->vPermissao = $vPermissao;
	}
	
	function getvPermissao(){
		return $this->vPermissao;
	}
	
	function getIdUsuario(){
		return $this->oUsuario->getId();
	}
	
	function logarUsuarioPainel($sLogin,$sSenha,$sBanco){
		//ATENCAO O LOGIN PRECISA SER PASSADO NO UTF8_DECODE PARA O BANCO POIS É UM TEXTO LITERAL, A SENHA NÃO PRECISA POIS SERÁ COMPARADA DE IGUAL PARA IGUAL
		$oFachadaSeguranca = new FachadaSegurancaBD();
		$vWhereUsuario = array("login = '".$sLogin."'","publicado = 1","ativo = 1","logado = 0");
		$voUsuario = $oFachadaSeguranca->recuperaTodosUsuario($sBanco,$vWhereUsuario);

		$vWhereUsuarioLogado = array("login = '".$sLogin."'","publicado = 1","ativo = 1","logado = 1");
		$voUsuarioLogado = $oFachadaSeguranca->recuperaTodosUsuario($sBanco,$vWhereUsuarioLogado);
		if(count($voUsuarioLogado) == 1){
			$oUsuarioLogado = $voUsuarioLogado[0];
			if(is_object($oUsuarioLogado)){
				if ($oUsuarioLogado->getSenha() == $sSenha){
					//TRATAMENTO PARA USUARIO LOGADO HÁ MAIS DE 1H
					$vWhereTransacaoUsuarioLogado = array("publicado = 1","ativo = 1","id_usuario = ".$oUsuarioLogado->getId(),"id_tipo_transacao = 3");
					$sOrderTransacaoUsuarioLogado = "id desc limit 1";
					$voTransacaoUsuarioLogado = $oFachadaSeguranca->recuperaTodosTransacao($sBanco,$vWhereTransacaoUsuarioLogado,$sOrderTransacaoUsuarioLogado);
					if(count($voTransacaoUsuarioLogado) == 1){
						$oTransacaoUsuarioLogado = $voTransacaoUsuarioLogado[0];
						if(is_object($oTransacaoUsuarioLogado)){
							$dDataHoraAtual = date("Y-m-d H:i:s");
							$dDataHoraUltimoLogin = $oTransacaoUsuarioLogado->getDtTransacao();
							$nDiferencaDatasHoras = strtotime($dDataHoraAtual) - strtotime($dDataHoraUltimoLogin);
							$nTempo1Hora = 60 * 60;
							if($nDiferencaDatasHoras >= $nTempo1Hora){
								$oUsuarioLogado->setLogado(0);
								$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
								$sObjetoAcesso = "Usuário Logado há mais de 1 hora. Desbloqueio Automático. Login efetuado com sucesso. Login do usuário: ".$_POST['fLogin'];
								$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoAcesso,$oUsuarioLogado->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
								$oFachadaSeguranca->alteraUsuario($oUsuarioLogado,$oTransacaoAcesso,$sBanco);
								$this->setUsuario($oUsuarioLogado);
								$vWherePermissaoGrupoUsuario = array("id_grupo_usuario = ".$oUsuarioLogado->getIdGrupoUsuario(),"publicado = 1","ativo = 1");
								$voPermissao = $oFachadaSeguranca->recuperaTodosPermissao($sBanco,$vWherePermissaoGrupoUsuario);
								$this->setvPermissao($voPermissao);
								$oUsuarioLogado->setLogado(1);
								$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
								$sObjetoAcesso = "Login efetuado com sucesso. Login do usuário: ".$_POST['fLogin'];
								$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoAcesso,$oUsuarioLogado->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
								$oFachadaSeguranca->alteraUsuario($oUsuarioLogado,$oTransacaoAcesso,$sBanco);
								return true;
							}else{
								$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
								$sObjetoAcesso = "VERIFICAR ERRO 1: Tentativa de Login falhou. USUARIO JÁ SE ENCONTRA LOGADO NO SISTEMA. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
								$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuarioLogado->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
								$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
							}//if($nDiferencaDatasHoras >= $nTempo1Hora){
						}else{
							$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
							$sObjetoAcesso = "VERIFICAR ERRO 2: Tentativa de Login falhou. USUARIO LOGADO NÃO ENCONTRADO. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
							$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuarioLogado->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
							$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
						}//if(is_object($oTransacaoUsuarioLogado)){
					}else{
						$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
						$sObjetoAcesso = "VERIFICAR  ERRO 3: Tentativa de Login falhou. USUARIO LOGADO NÃO ENCONTRADO. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
						$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuarioLogado->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
						$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
					}//if(count($voTransacaoUsuarioLogado) == 1){
				}else{
					$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
					$sObjetoAcesso = "VERIFICAR  ERRO 4: Tentativa de Login falhou. USUÁRIO LOGADO, ERRO NA SENHA. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
					$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuarioLogado->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
					$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
				}//if ($oUsuarioLogado->getSenha() == $sSenha){
			}else{
				$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
				$sObjetoAcesso = "VERIFICAR  ERRO 5: Tentativa de Login falhou. USUARIO LOGADO NÃO ENCONTRADO. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,ID_USUARIO_SISTEMA,$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
			}//if(is_object($oUsuarioLogado)){
		}else{
			if(count($voUsuario) == 1){
				$oUsuario = $voUsuario[0];
				if(is_object($oUsuario)){
					if ($oUsuario->getSenha() == $sSenha){
						$oUsuario->setLogado(1);
						$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
						$sObjetoAcesso = "Login efetuado com sucesso. Login do usuário: ".$_POST['fLogin'];
						$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacao("",$nIdTipoTransacaoAcesso,$oUsuario->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
						$oFachadaSeguranca->alteraUsuario($oUsuario,$oTransacaoAcesso,$sBanco);
						$this->setUsuario($oUsuario);
						$vWherePermissaoGrupoUsuario = array("id_grupo_usuario = ".$oUsuario->getIdGrupoUsuario(),"publicado = 1","ativo = 1");
						$voPermissao = $oFachadaSeguranca->recuperaTodosPermissao($sBanco,$vWherePermissaoGrupoUsuario);
						$this->setvPermissao($voPermissao);
						return true;
					}else{
						$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
						$sObjetoAcesso = "VERIFICAR ERRO 6: Tentativa de Login falhou. ERRO NA SENHA. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
						$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuario->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
						$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
					}//if ($oUsuario->getSenha() == $sSenha){
				}else{
					$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
					$sObjetoAcesso = "VERIFICAR ERRO 7: Tentativa de Login falhou. USUARIO NÃO ENCONTRADO. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
					$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,ID_USUARIO_SISTEMA,$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
					$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
				}//if(is_object($oUsuario)){
			}else{
				$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
				$sObjetoAcesso = "VERIFICAR ERRO 8: Tentativa de Login falhou. USUARIO NÃO ENCONTRADO OU MULTIPLOS LOGINS. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,ID_USUARIO_SISTEMA,$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
			}//if(count($voUsuario) == 1){
		}//if(count($voUsuarioLogado) == 1){
		
		return false;
	}
	
	function alteraSenhaUsuario($sLogin,$sSenhaAtual,$sSenhaNova,$sBanco){
		$oFachadaSeguranca = new FachadaSegurancaBD();
		$vWhereUsuario = array("login = '".$sLogin."'","publicado = 1","ativo = 1");
		$voUsuario = $oFachadaSeguranca->recuperaTodosUsuario($sBanco,$vWhereUsuario);
		if(count($voUsuario) == 1){
			$oUsuario = $voUsuario[0];
			if(is_object($oUsuario)){
				if ($oUsuario->getSenha() == $sSenhaAtual){
					$oUsuario->setSenha($sSenhaNova);
					return ($oFachadaSeguranca->alteraUsuario($oUsuario,$sBanco));
				}//if ($oUsuario->getSenha() == $sSenhaAtual){
			}//if(is_object($oUsuario)){
		}//if(count($voUsuario) == 1){
		return false;
	}
	
	function verificaPermissao($sTipo,$sOp,$sBanco){
		$oFachadaSeguranca = new FachadaSegurancaBD();
		$nIdTipoTransacao = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria($sTipo,$sOp,$sBanco);
		$voPermissao = $this->getvPermissao();
		if (count($voPermissao) > 0){
			foreach($voPermissao as $oPermissao){
				if ($oPermissao->getIdTipoTransacao() == $nIdTipoTransacao){
					return true;
				}
			}
		}
		return false;
	}
	
	function recuperaTipoTransacaoPorDescricaoCategoria($sTipo,$sOp,$sBanco){
		$oFachadaSeguranca = new FachadaSegurancaBD();
		return ($oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria($sTipo,$sOp,$sBanco));
	}
	
}
?>