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
		$vWhereUsuario = array("login = '".$sLogin."'","publicado = 1","ativo = 1");
		$voUsuario = $oFachadaSeguranca->recuperaTodosUsuario($sBanco,$vWhereUsuario);
		if(count($voUsuario) == 1){
			$oUsuario = $voUsuario[0];
			if(is_object($oUsuario)){
				if ($oUsuario->getSenha() == $sSenha){
					//TRATAMENTO PARA USUARIO LOGADO
					if($oUsuario->getLogado() == 1){
						$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
						$sObjetoAcesso = "VERIFICAR ERRO 1: Tentativa de Login falhou. USUARIO LOGADO NO SISTEMA. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
						$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuario->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
						$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
						$_SESSION['sMsgPermissao'] = 'Usuário já logado no sistema! <a href="Javascript: void(0)" onclick="liberaUsuario('.$oUsuario->getId().')">Clique aqui para liberar o acesso!</a>';
						header("Location: ../../index.php?bErro=1");
						exit();
					}else{
						//TRATAMENTO PARA USUARIO NAO LOGADO
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
					}//if($oUsuario->getLogado() == 1){
				}else{
					$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
					$sObjetoAcesso = "VERIFICAR  ERRO 4: Tentativa de Login falhou. USUÁRIO EXISTE MAS HOUVE ERRO NA SENHA. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
					$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,$oUsuario->getId(),$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
					$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
				}//if ($oUsuario->getSenha() == $sSenha){
			}else{
				$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
				$sObjetoAcesso = "VERIFICAR ERRO 5: Tentativa de Login falhou. USUARIO NÃO ENCONTRADO. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
				$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,ID_USUARIO_SISTEMA,$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
				$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
			}//if(is_object($oUsuario)){
		}else{
			$nIdTipoTransacaoAcesso = $oFachadaSeguranca->recuperaTipoTransacaoPorDescricaoCategoria("Acesso","Login",$sBanco);
			$sObjetoAcesso = "VERIFICAR ERRO 6: Tentativa de Login falhou. USUARIO NÃO ENCONTRADO. Login do usuário: ".$_POST['fLogin']." Senha: ".$_POST['fSenha'];
			$oTransacaoAcesso = $oFachadaSeguranca->inicializaTransacaoAcesso("",$nIdTipoTransacaoAcesso,ID_USUARIO_SISTEMA,$sObjetoAcesso,IP_USUARIO,DATAHORA,1,1);
			$oFachadaSeguranca->insereTransacaoAcesso($oTransacaoAcesso,$sBanco);
		}//if(count($voUsuario) == 1){

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