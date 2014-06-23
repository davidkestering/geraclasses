<?php
//á
class CategoriaTipoTransacaoParent {

/**
* nId
* @access private
*/
var $nId;
/**
* sDescricao
* @access private
*/
var $sDescricao;
/**
* dDtCategoriaTipoTransacao
* @access private
*/
var $dDtCategoriaTipoTransacao;
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
	* Construtor de CategoriaTipoTransacao
	* @param $nId Id
	* @param $sDescricao Descricao
	* @param $dDtCategoriaTipoTransacao DtCategoriaTipoTransacao
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	
	/*
	function CategoriaTipoTransacao($nId,$sDescricao,$dDtCategoriaTipoTransacao,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setDescricao($sDescricao);
		$this->setDtCategoriaTipoTransacao($dDtCategoriaTipoTransacao);
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
	* Recupera o valor do atributo $sDescricao. 
	* @return $sDescricao Descricao
	*/
	function getDescricao(){
		return $this->sDescricao;
	}
	/**
	* Atribui valor ao atributo $sDescricao. 
	* @param $sDescricao Descricao
	* @access public
	*/
	function setDescricao($sDescricao){
		$this->sDescricao = $sDescricao;
	}

	/**
	* Recupera o valor do atributo $dDtCategoriaTipoTransacao. 
	* @return $dDtCategoriaTipoTransacao DtCategoriaTipoTransacao
	*/
	function getDtCategoriaTipoTransacao(){
		return $this->dDtCategoriaTipoTransacao;
	}
	/**
	* Atribui valor ao atributo $dDtCategoriaTipoTransacao. 
	* @param $dDtCategoriaTipoTransacao DtCategoriaTipoTransacao
	* @access public
	*/
	function setDtCategoriaTipoTransacao($dDtCategoriaTipoTransacao){
		$this->dDtCategoriaTipoTransacao = $dDtCategoriaTipoTransacao;
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