<?php
//á
require_once("GrupoUsuarioParent.class.php");

class GrupoUsuario extends GrupoUsuarioParent {
	
	/**
	* Construtor de GrupoUsuario
	* @param $nId Id
	* @param $sNmGrupoUsuario NmGrupoUsuario
	* @param $dDtGrupoUsuario DtGrupoUsuario
	* @param $bPublicado Publicado
	* @param $bAtivo Ativo
	*/
	function __construct($nId,$sNmGrupoUsuario,$dDtGrupoUsuario,$bPublicado,$bAtivo){
		$this->setId($nId);
		$this->setNmGrupoUsuario($sNmGrupoUsuario);
		$this->setDtGrupoUsuario($dDtGrupoUsuario);
		$this->setPublicado($bPublicado);
		$this->setAtivo($bAtivo);
		
	}
	
}
?>