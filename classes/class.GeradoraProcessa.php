<?php
//á
class GeradoraProcessa{

	var $oConstrutor;
	var $sBanco;
	var $sAtributosConstrutor;
    var	$sListaAtributosChave;
	var $sAtributosFormChave;
	var $oTabela;
	var $sNomeArquivo;



	function __construct($sBanco,$oConstrutor){
		$this->oConstrutor = $oConstrutor;
		$this->sBanco = $sBanco;
		$this->sNomeArquivo = "processa_".$this->oConstrutor->sArquivo.".php";
	}
	
	function geraAtributos(){
		$vAtributos = $this->oConstrutor->vAtributos;
		$vCampos = $this->oConstrutor->vCampos;
		if (count($vAtributos) > 0){
			$vAtributosChave = $vAtribChave = array();
			foreach($vAtributos as $nIndice => $sAtributo){
				$oCampo = $vCampos[$nIndice];
				if ($oCampo->Key == "PRI"){
					$sTeste = $vAtributos[$nIndice];
					array_push($vAtributosChave,$sTeste);
					array_push($vAtribChave,"\$_POST['f".substr($sAtributo,1)."']");
				}
				$sNomeAtributo = substr($sAtributo,1);
				$this->sAtributosConstrutor .= "\$_POST['f".substr($sAtributo,1) . "'],";
			}
			$this->sAtributosConstrutor = substr($this->sAtributosConstrutor,0,strlen($this->sAtributosConstrutor) - 1);
    		$this->sListaAtributosChave = join(",",$vAtributosChave);	
    		$this->sAtributosFormChave = join(",",$vAtribChave);	
		}
	}

	function gera(){
			$this->geraAtributos();
			$sArquivo = "";
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_processa.txt");
			$sArquivo = join("",$vModelo);
			
			$sBancoPrimeiraMaiuscula = "";
			
			$vBanco = explode("_",$this->sBanco);
			foreach($vBanco as $sNome)
				$sBancoPrimeiraMaiuscula .= ucfirst($sNome);
				
			$sArquivo = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_MINUSCULO#/",$this->oConstrutor->sArquivo,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",$this->sAtributosConstrutor,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CHAVE#/",$this->sListaAtributosChave,$sArquivo);
			$sArquivo = preg_replace("/#ATRIBUTOS_FORM_CHAVE#/",$this->sAtributosFormChave,$sArquivo);
			$sArquivo = preg_replace("/#BANCO#/",strtoupper($this->sBanco),$sArquivo);
			$sArquivo = preg_replace("/#BANCO_PRIMEIRA_MAIUSCULA#/",$sBancoPrimeiraMaiuscula,$sArquivo);
			$sCaminhoDiretorio = dirname(__FILE__) . "/../paginas_geradas/".$this->oConstrutor->sArquivo."/";
			$sCaminhoArquivo = dirname(__FILE__) . "/../paginas_geradas/".$this->oConstrutor->sArquivo."/".$this->sNomeArquivo;

			if(!is_dir($sCaminhoDiretorio))
				mkdir($sCaminhoDiretorio);
				
			if (file_exists($sCaminhoArquivo)){
				unlink($sCaminhoArquivo);
			}
			$pArquivo = fopen($sCaminhoArquivo,"a+");
			fputs($pArquivo,$sArquivo);
	}
}
?>