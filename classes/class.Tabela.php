<?php
//á
class Tabela {
	var $sNomeTabela;
	var $nNumeroDeCampos;
	var $vCampos;
	
	function __construct($sNomeTabela){
		$this->sNomeTabela = $sNomeTabela;
		$this->nNumeroDeCampos = 0;
		$this->vCampos = array();
		$this->pegaCampos();
	}
	
	function pegaCampos(){
		global $oConexao;
		$sSQL = "DESCRIBE $this->sNomeTabela";
		$oConexao->consulta($sSQL);	
		if ($oConexao->pegaConsulta()){
			while ($oResultado = $oConexao->pegaObjetoResultado()){
				$this->vCampos[] = $oResultado;
			}
		}
		$this->nNumeroDeCampos = count($this->vCampos);
	}
	
	function pegaNome(){
		return $this->sNomeTabela;
	}
	
	function pegaNumeroDeCampos(){
		return $this->nNumeroDeCampos;
	}
}
?>
