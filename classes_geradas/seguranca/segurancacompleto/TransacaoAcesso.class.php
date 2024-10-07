<?php
//á
require_once("TransacaoAcessoParent.class.php");

class TransacaoAcesso extends TransacaoAcessoParent {
	
	/**
	* Construtor de TransacaoAcesso
	* @param $nId Id
	* @param $nIdTipoTransacao IdTipoTransacao
	* @param $nIdUsuario IdUsuario
	* @param $sObjeto Objeto
	* @param $sIp Ip
	* @param $dDtCadastro DtCadastro
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	function __construct($nId,$nIdTipoTransacao,$nIdUsuario,$sObjeto,$sIp,$dDtCadastro,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setIdTipoTransacao($nIdTipoTransacao);
		$this->setIdUsuario($nIdUsuario);
		$this->setObjeto($sObjeto);
		$this->setIp($sIp);
		$this->setDtCadastro($dDtCadastro);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	
}
?>