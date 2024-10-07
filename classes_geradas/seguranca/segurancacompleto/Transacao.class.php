<?php
//á
require_once("TransacaoParent.class.php");

class Transacao extends TransacaoParent {
	
	/**
	* Construtor de Transacao
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