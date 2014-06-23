<?php
//á
class GrupoUsuarioParent {

/**
* nId
* @access private
*/
var $nId;
/**
* sNmGrupoUsuario
* @access private
*/
var $sNmGrupoUsuario;
/**
* dDtGrupoUsuario
* @access private
*/
var $dDtGrupoUsuario;
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
	* Construtor de GrupoUsuario
	* @param $nId Id
	* @param $sNmGrupoUsuario NmGrupoUsuario
	* @param $dDtGrupoUsuario DtGrupoUsuario
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	
	/*
	function GrupoUsuario($nId,$sNmGrupoUsuario,$dDtGrupoUsuario,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setNmGrupoUsuario($sNmGrupoUsuario);
		$this->setDtGrupoUsuario($dDtGrupoUsuario);
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
	* Recupera o valor do atributo $sNmGrupoUsuario. 
	* @return $sNmGrupoUsuario NmGrupoUsuario
	*/
	function getNmGrupoUsuario(){
		return $this->sNmGrupoUsuario;
	}
	/**
	* Atribui valor ao atributo $sNmGrupoUsuario. 
	* @param $sNmGrupoUsuario NmGrupoUsuario
	* @access public
	*/
	function setNmGrupoUsuario($sNmGrupoUsuario){
		$this->sNmGrupoUsuario = $sNmGrupoUsuario;
	}

	/**
	* Recupera o valor do atributo $dDtGrupoUsuario. 
	* @return $dDtGrupoUsuario DtGrupoUsuario
	*/
	function getDtGrupoUsuario(){
		return $this->dDtGrupoUsuario;
	}
	/**
	* Atribui valor ao atributo $dDtGrupoUsuario. 
	* @param $dDtGrupoUsuario DtGrupoUsuario
	* @access public
	*/
	function setDtGrupoUsuario($dDtGrupoUsuario){
		$this->dDtGrupoUsuario = $dDtGrupoUsuario;
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