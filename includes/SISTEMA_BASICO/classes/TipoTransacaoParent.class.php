<?php
//á
class TipoTransacaoParent {

/**
* nId
* @access private
*/
var $nId;
/**
* nIdCategoriaTipoTransacao
* @access private
*/
var $nIdCategoriaTipoTransacao;
/**
* sTransacao
* @access private
*/
var $sTransacao;
/**
* dDtTipoTransacao
* @access private
*/
var $dDtTipoTransacao;
/**
* bPublicado
* @access private
*/
var $bPublicado;
/**
* bAtivo
* @access private
*/
var $bAtivo;

	
	/**
	* Construtor de TipoTransacao
	* @param $nId Id
	* @param $nIdCategoriaTipoTransacao IdCategoriaTipoTransacao
	* @param $sTransacao Transacao
	* @param $dDtTipoTransacao DtTipoTransacao
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	
	/*
	function TipoTransacao($nId,$nIdCategoriaTipoTransacao,$sTransacao,$dDtTipoTransacao,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setIdCategoriaTipoTransacao($nIdCategoriaTipoTransacao);
		$this->setTransacao($sTransacao);
		$this->setDtTipoTransacao($dDtTipoTransacao);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	*/
	
	/**
	* Recupera o valor do atributo $nId. 
	* @return $nId Id
	*/
	function getId(){
		return $this->nId;
	}
	/**
	* Atribui valor ao atributo $nId. 
	* @param $nId Id
	* @access public
	*/
	function setId($nId){
		$this->nId = $nId;
	}

	/**
	* Recupera o valor do atributo $nIdCategoriaTipoTransacao. 
	* @return $nIdCategoriaTipoTransacao IdCategoriaTipoTransacao
	*/
	function getIdCategoriaTipoTransacao(){
		return $this->nIdCategoriaTipoTransacao;
	}
	/**
	* Atribui valor ao atributo $nIdCategoriaTipoTransacao. 
	* @param $nIdCategoriaTipoTransacao IdCategoriaTipoTransacao
	* @access public
	*/
	function setIdCategoriaTipoTransacao($nIdCategoriaTipoTransacao){
		$this->nIdCategoriaTipoTransacao = $nIdCategoriaTipoTransacao;
	}

	/**
	* Recupera o valor do atributo $sTransacao. 
	* @return $sTransacao Transacao
	*/
	function getTransacao(){
		return $this->sTransacao;
	}
	/**
	* Atribui valor ao atributo $sTransacao. 
	* @param $sTransacao Transacao
	* @access public
	*/
	function setTransacao($sTransacao){
		$this->sTransacao = $sTransacao;
	}

	/**
	* Recupera o valor do atributo $dDtTipoTransacao. 
	* @return $dDtTipoTransacao DtTipoTransacao
	*/
	function getDtTipoTransacao(){
		return $this->dDtTipoTransacao;
	}
	/**
	* Atribui valor ao atributo $dDtTipoTransacao. 
	* @param $dDtTipoTransacao DtTipoTransacao
	* @access public
	*/
	function setDtTipoTransacao($dDtTipoTransacao){
		$this->dDtTipoTransacao = $dDtTipoTransacao;
	}

	/**
	* Recupera o valor do atributo $bPublicado. 
	* @return $bPublicado Publicado
	*/
	function getPublicado(){
		return $this->bPublicado;
	}
	/**
	* Atribui valor ao atributo $bPublicado. 
	* @param $bPublicado Publicado
	* @access public
	*/
	function setPublicado($bPublicado){
		$this->bPublicado = $bPublicado;
	}

	/**
	* Recupera o valor do atributo $bAtivo. 
	* @return $bAtivo Ativo
	*/
	function getAtivo(){
		return $this->bAtivo;
	}
	/**
	* Atribui valor ao atributo $bAtivo. 
	* @param $bAtivo Ativo
	* @access public
	*/
	function setAtivo($bAtivo){
		$this->bAtivo = $bAtivo;
	}

	
}
?>