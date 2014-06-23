<?php

class GeradoraVisualiza{

	var $oConstrutor;
	var $sBanco;
	var $sBancoPrimeiraMaiuscula;
	var $sColunaAtributos;
	var $sColunaGetAtributos;
	var $sRecuperaAtributosAuxiliares;
	var $sObjetosDestruir;
	var $oTabela;
	var $sNomeArquivo;



	function GeradoraVisualiza($sBanco,$oConstrutor){
		$this->oConstrutor = $oConstrutor;
		$this->sBanco = $sBanco;
		$this->sNomeArquivo = "visualiza_".$this->oConstrutor->sArquivo.".php";
	}
	
	function geraAtributos(){
		$vAtributos = $this->oConstrutor->vAtributos;
		$vCampos = $this->oConstrutor->vCampos;

		if (count($vAtributos) > 0){
			foreach($vAtributos as $nIndice => $sAtributo){
				$sNomeAtributo = substr($sAtributo,1);
				$oCampo = $vCampos[$nIndice];
				if ($oCampo->Key != "PRI"){ 
					$this->sColunaAtributos .= "<th>".substr($sAtributo,1) . "</th>\n\t";
					/*
						Se o campo for chave estrangeira, deve ser dado um tratamento para
						que seja mostrado o valor do campo do BD. na tela de visualizaÃ§Ã£o, ao
						invÃ©s de um valor de chave.
					*/
					if (substr($oCampo->Field,0,3) == "ID_"){
						$sAux = substr($sAtributo,3);
						$this->sRecuperaAtributosAuxiliares .= "\t\t\t\$o$sAux = \$oFachada".$this->sBancoPrimeiraMaiuscula."->recupera$sAux(\$o".$this->oConstrutor->sClasse."->get".substr($sAtributo,1)."(),BANCO_".strtoupper($this->sBanco).");\n";

						$this->sColunaGetAtributos .= "<td><?php echo \$o".substr($sAtributo,3)."->getNome()?></td>\n\t";
						$this->sObjetosDestruir .= "\t\tunset(\$o$sAux);\n";
					} else {
						$this->sColunaGetAtributos .= "<td><?php echo \$o".$this->oConstrutor->sClasse."->get".substr($sAtributo,1) . "()?></td>\n\t";
					}
				}
			}
		}
		return count($vAtributos);
	}

	function gera(){
			$vBanco = explode("_",$this->sBanco);
			foreach($vBanco as $sNome)
				$this->sBancoPrimeiraMaiuscula .= ucfirst($sNome);
			
			$nAtributos = $this->geraAtributos();
			$sArquivo = "";
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_visualiza.txt");
			$sArquivo = join("",$vModelo);

			$sArquivo = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_MINUSCULO#/",$this->oConstrutor->sArquivo,$sArquivo);
			$sArquivo = preg_replace("/#BANCO#/",strtoupper($this->sBanco),$sArquivo);
			$sArquivo = preg_replace("/#BANCO_PRIMEIRA_MAIUSCULA#/",$this->sBancoPrimeiraMaiuscula,$sArquivo);
			$sArquivo = preg_replace("/#COLUNA_ATRIBUTOS#/",$this->sColunaAtributos,$sArquivo);
			$sArquivo = preg_replace("/#COLUNA_GET_ATRIBUTOS#/",$this->sColunaGetAtributos,$sArquivo);
			$sArquivo = preg_replace("/#RECUPERA_ATRIBUTOS_AUXILIARES#/",$this->sRecuperaAtributosAuxiliares,$sArquivo);
			$sArquivo = preg_replace("/#OBJETOS_DESTRUIR#/",$this->sObjetosDestruir,$sArquivo);
			$sArquivo = preg_replace("/#JAVA_SCRIPT#/",$this->sBanco,$sArquivo);
			$sArquivo = preg_replace("/#NUMERO_ATRIBUTOS#/",$nAtributos,$sArquivo);
			
			$sCaminhoDiretorio = dirname(__FILE__) . "/../paginas_geradas/".$this->oConstrutor->sArquivo."/";
			$sCaminhoArquivo = dirname(__FILE__) . "/../paginas_geradas/".$this->oConstrutor->sArquivo."/".$this->sNomeArquivo;
			
			if(!is_dir($sCaminhoDiretorio))
				mkdir($sCaminhoDiretorio);
				
			if(file_exists($sCaminhoArquivo)){
				unlink($sCaminhoArquivo);
			}
			$pArquivo = fopen($sCaminhoArquivo,"a+");
			fputs($pArquivo,$sArquivo);
	}
}
?>