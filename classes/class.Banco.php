<?php
//á
class Banco {
	var $sNomeBanco;
	var $nNumeroDeTabelas;
	var $vTabela;
	
	function Banco($sNomeBanco){
		global $oConexao;
		$this->sNomeBanco = $sNomeBanco;
		$this->vTabela = array();
		$this->nNumeroDeTabelas = 0;
		$this->pegaTabelas();
	
	}
	
	function pegaTabelas(){
		global $oConexao;
		$sSQL = "SHOW TABLES";
		$oConexao->consulta($sSQL);
		if ($oConexao->pegaConsulta()){
			if ($oConexao->contaRegistros() > 0){
				while ($vResultado = $oConexao->pegaVetorResultado()){
					$this->vTabela[] = $vResultado;
				}
			}
		}
		$this->nNumeroDeTabelas = count($this->vTabela);
	}
	
	function pegaNome(){
		return $this->sNomeBanco;
	}
		
	function pegaNumeroDeTabelas(){
		return $this->nNumeroDeTabelas;
	}
}
?>