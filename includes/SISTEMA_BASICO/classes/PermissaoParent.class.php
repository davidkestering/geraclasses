<?php
//á
class PermissaoParent {

/**
* nIdTipoTransacao
* @access private
*/
var $nIdTipoTransacao;
/**
* nIdGrupoUsuario
* @access private
*/
var $nIdGrupoUsuario;
/**
* dDtPermissao
* @access private
*/
var $dDtPermissao;
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
	* Construtor de Permissao
	* @param $nIdTipoTransacao IdTipoTransacao
	* @param $nIdGrupoUsuario IdGrupoUsuario
	* @param $dDtPermissao DtPermissao
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	
	/*
	function Permissao($nIdTipoTransacao,$nIdGrupoUsuario,$dDtPermissao,$bPublicado,$bAtivo){
		$this->setIdTipoTransacao($nIdTipoTransacao);
		$this->setIdGrupoUsuario($nIdGrupoUsuario);
		$this->setDtPermissao($dDtPermissao);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	*/
	
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
	* Recupera o valor do atributo $nIdGrupoUsuario. 
	* @return $nIdGrupoUsuario IdGrupoUsuario
	*/
	function getIdGrupoUsuario(){
		return $this->nIdGrupoUsuario;
	}
	/**
	* Atribui valor ao atributo $nIdGrupoUsuario. 
	* @param $nIdGrupoUsuario IdGrupoUsuario
	* @access public
	*/
	function setIdGrupoUsuario($nIdGrupoUsuario){
		$this->nIdGrupoUsuario = $nIdGrupoUsuario;
	}

	/**
	* Recupera o valor do atributo $dDtPermissao. 
	* @return $dDtPermissao DtPermissao
	*/
	function getDtPermissao(){
		return $this->dDtPermissao;
	}
	/**
	* Atribui valor ao atributo $dDtPermissao. 
	* @param $dDtPermissao DtPermissao
	* @access public
	*/
	function setDtPermissao($dDtPermissao){
		$this->dDtPermissao = $dDtPermissao;
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