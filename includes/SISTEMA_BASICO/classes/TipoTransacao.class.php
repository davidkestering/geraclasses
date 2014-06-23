<?php
//á
require_once("TipoTransacaoParent.class.php");

class TipoTransacao extends TipoTransacaoParent {
	
	/**
	* Construtor de TipoTransacao
	* @param $nId Id
	* @param $nIdCategoriaTipoTransacao IdCategoriaTipoTransacao
	* @param $sTransacao Transacao
	* @param $dDtTipoTransacao DtTipoTransacao
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	function __construct($nId,$nIdCategoriaTipoTransacao,$sTransacao,$dDtTipoTransacao,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setIdCategoriaTipoTransacao($nIdCategoriaTipoTransacao);
		$this->setTransacao($sTransacao);
		$this->setDtTipoTransacao($dDtTipoTransacao);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	
}
?>