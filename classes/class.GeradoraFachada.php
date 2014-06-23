<?php
//á
class GeradoraFachada{

	var $sIncludeClasses;
	var $sBanco;
	var $sNomeArquivo;
	var $sNomeArquivoParent;
	
	
	function GeradoraFachada($sBanco,$sInteriorFachada,$vNomeClasses){
		$this->sBanco = ucfirst(strtolower($sBanco));
		$this->sInteriorFachada = $sInteriorFachada;
		$this->vNomeClasses = $vNomeClasses;
		
		$sBancoPrimeiraMaiuscula = "";
		
		$vBanco = explode("_",$this->sBanco);
		foreach($vBanco as $sNome)
			$sBancoPrimeiraMaiuscula .= ucfirst($sNome);
				
		//$this->sNomeArquivo = "class.Fachada".$sBancoPrimeiraMaiuscula."BD.php";
		//$this->sNomeArquivoParent = "class.Fachada".$sBancoPrimeiraMaiuscula."BDParent.php";
		$this->sNomeArquivo = "Fachada".$sBancoPrimeiraMaiuscula."BD.class.php";
		$this->sNomeArquivoParent = "Fachada".$sBancoPrimeiraMaiuscula."BDParent.class.php";
	}
	
	function geraIncludeClasses(){
		if (count($this->vNomeClasses) > 0){
			foreach($this->vNomeClasses as $sNomeClasse){
				$this->sIncludeClasses .= "require_once(\"".$sNomeClasse."\");\n";
			}
		}
	}
	
	function gera(){
			$this->geraIncludeClasses();
			$sArquivo = "";
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_classe_fachada.txt");
			$sArquivo = join("",$vModelo);
			
			$sBancoPrimeiraMaiuscula = "";
			
			$vBanco = explode("_",$this->sBanco);
			foreach($vBanco as $sNome)
				$sBancoPrimeiraMaiuscula .= ucfirst($sNome);
				
			$sArquivo = preg_replace("/#INCLUDE_CLASSES#/",$this->sIncludeClasses,$sArquivo);
			$sArquivo = preg_replace("/#BANCO#/",$sBancoPrimeiraMaiuscula,$sArquivo);

			$sCaminhoArquivo = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivo;
			if (file_exists($sCaminhoArquivo)){
				unlink($sCaminhoArquivo);
			}
			$pArquivo = fopen($sCaminhoArquivo,"a+");
			fputs($pArquivo,$sArquivo);
			
			
			
			
			
			$sArquivoParent = "";
			$vModeloParent = file(dirname(__FILE__)."/../modelos/modelo_classe_fachada_parent.txt");
			$sArquivoParent = join("",$vModeloParent);
			
			$vBanco = explode("_",$this->sBanco);
			$sBancoPrimeiraMaiuscula = "";
			
			foreach($vBanco as $sNome)
				$sBancoPrimeiraMaiuscula .= ucfirst($sNome);
			
			
			$sArquivoParent = preg_replace("/#INCLUDE_CLASSES#/",$this->sIncludeClasses,$sArquivoParent);
			$sArquivoParent = preg_replace("/#BANCO#/",$sBancoPrimeiraMaiuscula,$sArquivoParent);
			$sArquivoParent = preg_replace("/#INTERIOR_FACHADA#/",$this->sInteriorFachada,$sArquivoParent);
			$sCaminhoArquivoParent = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivoParent;
			if (file_exists($sCaminhoArquivoParent)){
				unlink($sCaminhoArquivoParent);
			}
			$pArquivoParent = fopen($sCaminhoArquivoParent,"a+");
			fputs($pArquivoParent,$sArquivoParent);
	}
}
?>