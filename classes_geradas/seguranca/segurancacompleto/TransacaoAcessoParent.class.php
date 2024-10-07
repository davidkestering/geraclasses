<?php
//á
class TransacaoAcessoParent {

/**
* nId
* @access private
*/
var $nId;
/**
* nIdTipoTransacao
* @access private
*/
var $nIdTipoTransacao;
/**
* nIdUsuario
* @access private
*/
var $nIdUsuario;
/**
* sObjeto
* @access private
*/
var $sObjeto;
/**
* sIp
* @access private
*/
var $sIp;
/**
* dDtCadastro
* @access private
*/
var $dDtCadastro;
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
	
	/*
	function TransacaoAcesso($nId,$nIdTipoTransacao,$nIdUsuario,$sObjeto,$sIp,$dDtCadastro,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setIdTipoTransacao($nIdTipoTransacao);
		$this->setIdUsuario($nIdUsuario);
		$this->setObjeto($sObjeto);
		$this->setIp($sIp);
		$this->setDtCadastro($dDtCadastro);
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
	* Recupera o valor do atributo $nIdTipoTransacao. 
	* @return $nIdTipoTransacao IdTipoTransacao
	*/
	function getIdTipoTransacao(){
		return $this->nIdTipoTransacao;
	}
	/**
	* Atribui valor ao atributo $nIdTipoTransacao. 
	* @param $nIdTipoTransacao IdTipoTransacao
	* @access public
	*/
	function setIdTipoTransacao($nIdTipoTransacao){
		$this->nIdTipoTransacao = $nIdTipoTransacao;
	}

	/**
	* Recupera o valor do atributo $nIdUsuario. 
	* @return $nIdUsuario IdUsuario
	*/
	function getIdUsuario(){
		return $this->nIdUsuario;
	}
	/**
	* Atribui valor ao atributo $nIdUsuario. 
	* @param $nIdUsuario IdUsuario
	* @access public
	*/
	function setIdUsuario($nIdUsuario){
		$this->nIdUsuario = $nIdUsuario;
	}

	/**
	* Recupera o valor do atributo $sObjeto. 
	* @return $sObjeto Objeto
	*/
	function getObjeto(){
		return $this->sObjeto;
	}
	/**
	* Atribui valor ao atributo $sObjeto. 
	* @param $sObjeto Objeto
	* @access public
	*/
	function setObjeto($sObjeto){
		$this->sObjeto = $sObjeto;
	}

	/**
	* Recupera o valor do atributo $sIp. 
	* @return $sIp Ip
	*/
	function getIp(){
		return $this->sIp;
	}
	/**
	* Atribui valor ao atributo $sIp. 
	* @param $sIp Ip
	* @access public
	*/
	function setIp($sIp){
		$this->sIp = $sIp;
	}

	/**
	* Recupera o valor do atributo $dDtCadastro.
	* @return $dDtCadastro DtCadastro
	*/
	function getDtCadastro(){
		return $this->dDtCadastro;
	}
	/**
	* Atribui valor ao atributo $dDtCadastro.
	* @param $dDtCadastro DtCadastro
	* @access public
	*/
	function setDtCadastro($dDtCadastro){
		$this->dDtCadastro = $dDtCadastro;
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