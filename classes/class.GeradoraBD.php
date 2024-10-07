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



	function __construct($oConstrutor){
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
						array_push($vAtribuicaoNaoChave,$oCampo->Field . " = " . "'\".\$oConexao->escapeString(\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."()).\"'");
						array_push($vValoresNaoChave,"'\".\$oConexao->escapeString(\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."()).\"'");
					} else {
						array_push($vAtribuicaoNaoChave,$oCampo->Field . " = " . "'\".\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."().\"'");
						array_push($vValoresNaoChave,"'\".\$o".$this->oConstrutor->sClasse."->get".substr($vAtributos[$nIndice],1)."().\"'");
					}
				}
				if(substr($vAtributos[$nIndice],0,1) == "s")
					array_push($vCamposReg,"\$oConexao->unescapeString(\$oReg->".$oCampo->Field.")");
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
			$sArquivo = preg_replace_callback("/#NOME_CLASSE#/",function($matches) {return $this->oConstrutor->sClasse;},$sArquivo);
			$sArquivo = preg_replace_callback("/#NOME_CLASSE_SIMPLES#/",function($matches) {return $this->oConstrutor->sClasseSimples;},$sArquivo);
			$sArquivo = preg_replace_callback("/#LISTA_ATRIBUTOS#/",function($matches) {return $this->sListaAtributos;},$sArquivo);
			$sArquivo = preg_replace_callback("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",function($matches) {return $this->sAtributosConstrutor;},$sArquivo);
			$sArquivo = preg_replace_callback("/#NOME_TABELA#/",function($matches) {return strtolower($this->oConstrutor->sTabela);},$sArquivo);
			$sArquivo = preg_replace_callback("/#COMPARACAO_CHAVE_ATRIBUTO#/",function($matches) {return $this->sComparacaoChaveAtributo;},$sArquivo);
			$sArquivo = preg_replace_callback("/#COMPARACAO_CHAVE_ATRIBUTO_ESP#/",function($matches) {return $this->sComparacaoChaveAtributoEsp;},$sArquivo);
			$sArquivo = preg_replace_callback("/#LISTA_CAMPOS_CHAVE#/",function($matches) {return $this->sListaCamposChave;},$sArquivo);
			$sArquivo = preg_replace_callback("/#LISTA_CAMPOS_NAO_CHAVE#/",function($matches) {return $this->sListaCamposNaoChave;},$sArquivo);
			$sArquivo = preg_replace_callback("/#VALORES_NAO_CHAVE#/",function($matches) {return $this->sListaValoresNaoChave;},$sArquivo);
			$sArquivo = preg_replace_callback("/#LISTA_ATRIBUTOS_CHAVE#/",function($matches) {return $this->sListaAtributosChave;},$sArquivo);
			$sArquivo = preg_replace_callback("/#LISTA_CAMPOS_REG#/",function($matches) {return $this->sListaCamposReg;},$sArquivo);
			$sArquivo = preg_replace_callback("/#COMENTARIO_ATRIBUTOS_CHAVE#/",function($matches) {return $this->sComentarioAtributosChave;},$sArquivo);
			$sArquivo = preg_replace_callback("/#ATRIBUICAO_NAO_CHAVE#/",function($matches) {return $this->sAtribuicaoNaoChave;},$sArquivo);
			$sCaminhoArquivo = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivo;
			if (file_exists($sCaminhoArquivo)){
				unlink($sCaminhoArquivo);
			}
			$pArquivo = fopen($sCaminhoArquivo,"a+");
			fputs($pArquivo,$sArquivo);
			

			$sArquivoParent = "";
			$vModeloParent = file(dirname(__FILE__)."/../modelos/modelo_classe_bd_parent.txt");
			$sArquivoParent = join("",$vModeloParent);
			$sArquivoParent = preg_replace_callback("/#NOME_CLASSE#/",function($matches) {return $this->oConstrutor->sClasse;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#NOME_CLASSE_SIMPLES#/",function($matches) {return $this->oConstrutor->sClasseSimples;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#LISTA_ATRIBUTOS#/",function($matches) {return $this->sListaAtributos;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",function($matches) {return $this->sAtributosConstrutor;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#NOME_TABELA#/",function($matches) {return strtolower($this->oConstrutor->sTabela);},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#COMPARACAO_CHAVE_ATRIBUTO#/",function($matches) {return $this->sComparacaoChaveAtributo;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#COMPARACAO_CHAVE_ATRIBUTO_ESP#/",function($matches) {return $this->sComparacaoChaveAtributoEsp;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#LISTA_CAMPOS_CHAVE#/",function($matches) {return $this->sListaCamposChave;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#LISTA_CAMPOS_NAO_CHAVE#/",function($matches) {return $this->sListaCamposNaoChave;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#VALORES_NAO_CHAVE#/",function($matches) {return $this->sListaValoresNaoChave;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#LISTA_ATRIBUTOS_CHAVE#/",function($matches) {return $this->sListaAtributosChave;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#LISTA_CAMPOS_REG#/",function($matches) {return $this->sListaCamposReg;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#COMENTARIO_ATRIBUTOS_CHAVE#/",function($matches) {return $this->sComentarioAtributosChave;},$sArquivoParent);
			$sArquivoParent = preg_replace_callback("/#ATRIBUICAO_NAO_CHAVE#/",function($matches) {return $this->sAtribuicaoNaoChave;},$sArquivoParent);
			$sCaminhoArquivoParent = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivoParent;
			if (file_exists($sCaminhoArquivoParent)){
				unlink($sCaminhoArquivoParent);
			}
			$pArquivoParent = fopen($sCaminhoArquivoParent,"a+");
			fputs($pArquivoParent,$sArquivoParent);
	}
}
?>