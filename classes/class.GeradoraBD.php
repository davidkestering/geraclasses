<?php
//á
class GeradoraBD{

	var $oConstrutor;
	var $sAtributosConstrutor;
	var $sListaAtributos;
    var	$sListaAtributosChave;
	var $sListaCamposReg;
    var	$sListaCamposChave;
    var $sListaCamposNaoChave;
    var	$sListaValoresNaoChave;
    var $sComparacaoChaveAtributo;
    var $sComparacaoChaveAtributoEsp;
	var $sComentarioAtributosChave;
	var $sConteudoClasse;
	var $sAtribuicaoNaoChave;
	var $oTabela;
	var $sNomeArquivo;
	var $sNomeArquivoParent;



	function GeradoraBD($oConstrutor){
		$this->oConstrutor = $oConstrutor;
		//$this->sNomeArquivo = "class.".$this->oConstrutor->sClasse."BD.php";
		//$this->sNomeArquivoParent = "class.".$this->oConstrutor->sClasse."BDParent.php";
		$this->sNomeArquivo = $this->oConstrutor->sClasse."BD.class.php";
		$this->sNomeArquivoParent = $this->oConstrutor->sClasse."BDParent.class.php";
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
			$vAtribuicaoNaoChave = $vCamposReg = $vValoresNaoChave = $vComparacao =  $vComparacaoEsp = $vAtributosChave = $vCamposChave = $vCamposNaoChave = array();
			foreach($vCampos as $nIndice => $oCampo){
				if ($oCampo->Key == "PRI"){
					$sTeste = "\$".$vAtributos[$nIndice];
					$sTeste2 = $vAtributos[$nIndice];
					array_push($vAtributosChave,$sTeste);
					$this->sComentarioAtributosChave .= "* @param $sTeste ".substr($sTeste,1)."\n\t";
					array_push($vCamposChave,$oCampo->Field);
					array_push($vComparacao,$oCampo->Field . " = '" . $sTeste."'");
					array_push($vComparacaoEsp,$oCampo->Field . " = '\".\$o".$this->oConstrutor->sClasse."->get" . substr($sTeste2,1)."().\"'");
				} else {
					
					array_push($vCamposNaoChave,$oCampo->Field);
					if(substr($vAtributos[$nIndice],0,1) == "s") {
						array_push($vAtribuicaoNaoChave,$oCampo->Field . " = " . "'\".utf8_decode(\$oConexao->escapeString(\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."())).\"'");
						array_push($vValoresNaoChave,"'\".utf8_decode(\$oConexao->escapeString(\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."())).\"'");
					} else {
						array_push($vAtribuicaoNaoChave,$oCampo->Field . " = " . "'\".\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."().\"'");
						array_push($vValoresNaoChave,"'\".\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."().\"'");
					}
				}
				if(substr($vAtributos[$nIndice],0,1) == "s")
					array_push($vCamposReg,"utf8_encode(\$oConexao->unescapeString(\$oReg->".$oCampo->Field."))");
				else
					array_push($vCamposReg,"\$oReg->".$oCampo->Field);
			}
    		$this->sListaAtributosChave = join(",",$vAtributosChave);	
    		$this->sListaCamposChave = join(",",$vCamposChave);	
    		$this->sListaCamposNaoChave = join(",",$vCamposNaoChave);	
    		$this->sListaValoresNaoChave = join(",",$vValoresNaoChave);		
    		$this->sComparacaoChaveAtributo = join("\n\t\t\t\t and \t",$vComparacao);
    		$this->sComparacaoChaveAtributoEsp = join("\n\t\t\t\t and \t",$vComparacaoEsp);
			$this->sAtribuicaoNaoChave = join(",\n\t\t\t\t\t\t",$vAtribuicaoNaoChave);	
			$this->sListaCamposReg = join(",",$vCamposReg);
		}
	}

	function gera(){
			$this->geraAtributos();
			$this->geraCampos();
			$sArquivo = "";
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_classe_bd.txt");
			$sArquivo = join("",$vModelo);
			$sArquivo = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS#/",$this->sListaAtributos,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",$this->sAtributosConstrutor,$sArquivo);
			$sArquivo = preg_replace("/#NOME_TABELA#/",strtolower($this->oConstrutor->sTabela),$sArquivo);
			$sArquivo = preg_replace("/#COMPARACAO_CHAVE_ATRIBUTO#/",$this->sComparacaoChaveAtributo,$sArquivo);
			$sArquivo = preg_replace("/#COMPARACAO_CHAVE_ATRIBUTO_ESP#/",$this->sComparacaoChaveAtributoEsp,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_CAMPOS_CHAVE#/",$this->sListaCamposChave,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_CAMPOS_NAO_CHAVE#/",$this->sListaCamposNaoChave,$sArquivo);
			$sArquivo = preg_replace("/#VALORES_NAO_CHAVE#/",$this->sListaValoresNaoChave,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CHAVE#/",$this->sListaAtributosChave,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_CAMPOS_REG#/",$this->sListaCamposReg,$sArquivo);
			$sArquivo = preg_replace("/#COMENTARIO_ATRIBUTOS_CHAVE#/",$this->sComentarioAtributosChave,$sArquivo);
			$sArquivo = preg_replace("/#ATRIBUICAO_NAO_CHAVE#/",$this->sAtribuicaoNaoChave,$sArquivo);
			$sCaminhoArquivo = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivo;
			if (file_exists($sCaminhoArquivo)){
				unlink($sCaminhoArquivo);
			}
			$pArquivo = fopen($sCaminhoArquivo,"a+");
			fputs($pArquivo,$sArquivo);
			

			$sArquivoParent = "";
			$vModeloParent = file(dirname(__FILE__)."/../modelos/modelo_classe_bd_parent.txt");
			$sArquivoParent = join("",$vModeloParent);
			$sArquivoParent = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivoParent);
			$sArquivoParent = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_ATRIBUTOS#/",$this->sListaAtributos,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",$this->sAtributosConstrutor,$sArquivoParent);
			$sArquivoParent = preg_replace("/#NOME_TABELA#/",strtolower($this->oConstrutor->sTabela),$sArquivoParent);
			$sArquivoParent = preg_replace("/#COMPARACAO_CHAVE_ATRIBUTO#/",$this->sComparacaoChaveAtributo,$sArquivoParent);
			$sArquivoParent = preg_replace("/#COMPARACAO_CHAVE_ATRIBUTO_ESP#/",$this->sComparacaoChaveAtributoEsp,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_CAMPOS_CHAVE#/",$this->sListaCamposChave,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_CAMPOS_NAO_CHAVE#/",$this->sListaCamposNaoChave,$sArquivoParent);
			$sArquivoParent = preg_replace("/#VALORES_NAO_CHAVE#/",$this->sListaValoresNaoChave,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_ATRIBUTOS_CHAVE#/",$this->sListaAtributosChave,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_CAMPOS_REG#/",$this->sListaCamposReg,$sArquivoParent);
			$sArquivoParent = preg_replace("/#COMENTARIO_ATRIBUTOS_CHAVE#/",$this->sComentarioAtributosChave,$sArquivoParent);
			$sArquivoParent = preg_replace("/#ATRIBUICAO_NAO_CHAVE#/",$this->sAtribuicaoNaoChave,$sArquivoParent);
			$sCaminhoArquivoParent = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivoParent;
			if (file_exists($sCaminhoArquivoParent)){
				unlink($sCaminhoArquivoParent);
			}
			$pArquivoParent = fopen($sCaminhoArquivoParent,"a+");
			fputs($pArquivoParent,$sArquivoParent);
	}
}
?>