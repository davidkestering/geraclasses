<?php
//á
class GeradoraInteriorFachada{

	var $oConstrutor;
    var	$sListaAtributosChave;
	var $sComentarioAtributosChave;
	var $sInteriorFachada;
	var $oTabela;



	function GeradoraInteriorFachada($oConstrutor){
		$this->oConstrutor = $oConstrutor;
	}
	
	function geraAtributos(){
		$vAtributos = $this->oConstrutor->vAtributos;
		if (count($vAtributos) > 0){
			foreach($vAtributos as $sAtributo){
				$sNomeAtributo = substr($sAtributo,1);
				$this->sAtributosConstrutor .= "\$".$sAtributo . ",";
				$this->sListaAtributos .= "var \$".$sAtributo . ";\n";
			}
			$this->sAtributosConstrutor = substr($this->sAtributosConstrutor,0,strlen($this->sAtributosConstrutor) - 1);
		}
	}

	function geraCampos(){
		$vCampos = $this->oConstrutor->vCampos;
		$vAtributos = $this->oConstrutor->vAtributos;
		if (count($vCampos) > 0){
			$vCamposReg = $vValoresNaoChave = $vComparacao = $vAtributosChave = $vCamposChave = $vCamposNaoChave = array();
			foreach($vCampos as $nIndice => $oCampo){
				if ($oCampo->Key == "PRI"){
					$sTeste = "\$".$vAtributos[$nIndice];
					array_push($vAtributosChave,$sTeste);
					$this->sComentarioAtributosChave .= "* @param $sTeste ".substr($sTeste,1)."\n\t";
					array_push($vCamposChave,$oCampo->Field);
					array_push($vComparacao,$oCampo->Field . " = " . $sTeste);
				} else {
					array_push($vCamposNaoChave,$oCampo->Field);
					array_push($vValoresNaoChave,"'\".\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."().\"'");
				}
				array_push($vCamposReg,"\$oReg->".$oCampo->Field);
			}
    		$this->sListaAtributosChave = join(",",$vAtributosChave);
    		$this->sListaCamposChave = join(",",$vCamposChave);	
    		$this->sListaCamposNaoChave = join(",",$vCamposNaoChave);	
    		$this->sListaValoresNaoChave = join(",",$vValoresNaoChave);		
    		$this->sComparacaoChaveAtributo = join(",\n\t\t\t\t\t",$vComparacao);	
			$this->sComentarioAtributosChave = substr($this->sComentarioAtributosChave,0,strlen($this->sComentarioAtributosChave)-2);
			$this->sListaCamposReg = join(",",$vCamposReg);
		}
	}

	function gera(){
			$this->geraAtributos();	
			$this->geraCampos();
			$sArquivo = "";
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_interior_fachada.txt");
			$sArquivo = join("",$vModelo);
			$sArquivo = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivo);
			$sArquivo = preg_replace("/#NOME_TABELA#/",$this->oConstrutor->sTabela,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CHAVE#/",$this->sListaAtributosChave,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",$this->sAtributosConstrutor,$sArquivo);
			$sArquivo = preg_replace("/#COMENTARIO_ATRIBUTOS_CHAVE#/",$this->sComentarioAtributosChave,$sArquivo);
			$this->sInteriorFachada = $sArquivo;
	}
}
?>