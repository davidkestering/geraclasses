<?php
//á
require_once("PermissaoParent.class.php");

class Permissao extends PermissaoParent {
	
	/**
	* Construtor de Permissao
	* @param $nIdTipoTransacao IdTipoTransacao
	* @param $nIdGrupoUsuario IdGrupoUsuario
	* @param $dDtPermissao DtPermissao
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	function __construct($nIdTipoTransacao,$nIdGrupoUsuario,$dDtPermissao,$bPublicado,$bAtivo){
		$this->setIdTipoTransacao($nIdTipoTransacao);
		$this->setIdGrupoUsuario($nIdGrupoUsuario);
		$this->setDtPermissao($dDtPermissao);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	
}
?>