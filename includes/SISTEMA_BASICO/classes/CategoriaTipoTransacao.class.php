<?php
//á
require_once("CategoriaTipoTransacaoParent.class.php");

class CategoriaTipoTransacao extends CategoriaTipoTransacaoParent {
	
	/**
	* Construtor de CategoriaTipoTransacao
	* @param $nId Id
	* @param $sDescricao Descricao
	* @param $dDtCategoriaTipoTransacao DtCategoriaTipoTransacao
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	function __construct($nId,$sDescricao,$dDtCategoriaTipoTransacao,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setDescricao($sDescricao);
		$this->setDtCategoriaTipoTransacao($dDtCategoriaTipoTransacao);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	
}
?>