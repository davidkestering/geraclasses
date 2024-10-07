<?php
//á
class Construtor{
	var $sTabela;						  #OK
	var $sClasse;						  #OK
	var $sClasseSimples;
	var $sArquivo;
	var $vCampos = array();
	var $vAtributos = array();
	var $oTabela;

	function __construct($sNomeTabela){
		$this->sTabela = $sNomeTabela;
		$this->geraNomeClasse();
		$this->oTabela = new Tabela($this->sTabela);
		if ($this->oTabela->pegaNumeroDeCampos() > 0){
			$this->vCampos = $this->oTabela->vCampos;
			$this->geraAtributos();			
		}//if ($this->oTabela->pegaNumeroDeCampos() > 0){
	}

	function geraNomeClasse(){
		$vNome = array();
		if (preg_match("/(-)/",$this->sTabela)){
			$sSeparador = "-";
		} elseif(preg_match("/_/",$this->sTabela)){
			$sSeparador = "_";		
		}
		if(isset($sSeparador)) {
			$vNome = explode($sSeparador,$this->sTabela);
			$vAuxiliar = array();
			for($i = 0; $i < count($vNome);$i++){
				if(strlen($vNome[$i]) > 3)
					$vAuxiliar[$i] = ucfirst(strtolower($vNome[$i]));
				//$vAuxiliar[$i] = ucfirst(strtolower($vNome[$i]));
			}

			$this->sClasse = join("",$vAuxiliar);
			//$this->sClasseSimples = join("",$vAuxiliarSimples);
			$this->sArquivo = strtolower(join("_",$vAuxiliar));
		} else {
			$this->sClasse = $this->sClasseSimples = ucfirst(strtolower($this->sTabela));
			$this->sArquivo = strtolower($this->sTabela);
		}
	}

	function geraAtributos(){
		$sPadraoNumerico = "/(int|integer|bigint|tynint|smallint|mediumint|real|double|float|numeric|decimal)/";
		$sPadraoData = "/(time|timestamp|date|datetime)/";
		$vCampos = $this->vCampos;
		foreach($vCampos as $oCampo){
				$sNomeAtributo = "";
				$bPassouEmPadrao = false;

				if (preg_match($sPadraoNumerico,$oCampo->Type)){
					$sNomeAtributo = "n".ucfirst(strtolower($oCampo->Field));
					$bPassouEmPadrao = true;
				}

				if (preg_match($sPadraoData,$oCampo->Type)){
					$sNomeAtributo = "d".ucfirst(strtolower($oCampo->Field));
					$bPassouEmPadrao = true;
				}

				if ($oCampo->Type == "tinyint(1)" || $oCampo->Type == "tinyint(1) unsigned"){
					$sNomeAtributo = "b".ucfirst(strtolower($oCampo->Field));
					$bPassouEmPadrao = true;
				}
				
				if (!$bPassouEmPadrao){
					$sNomeAtributo = "s".ucfirst(strtolower($oCampo->Field));
				}

				$vNomeAtributo = preg_split("/((-)|(_))/",$sNomeAtributo);
				if (count($vNomeAtributo) > 0){
					for($i = 1;$i < count($vNomeAtributo); $i++){
						$vNomeAtributo[$i] = ucfirst($vNomeAtributo[$i]);
					}
					$sNomeAtributo = join("",$vNomeAtributo);
				}
				array_push($this->vAtributos,$sNomeAtributo);
		}
	}
}
?>