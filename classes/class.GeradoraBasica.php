<?php
//á
class GeradoraBasica{
	
	var $oConstrutor;
	var $sGetSet;
	var $sAtributosConstrutor;
	var $sListaAtributos;
	var $sInicializacao;
	var $sComentarioAtributos;
	var $sNomeArquivo;
	var $sNomeArquivoParent;

	function GeradoraBasica($oConstrutor){
		$this->oConstrutor = $oConstrutor;
		//$this->sNomeArquivo = "class.".$this->oConstrutor->sClasse.".php";
		//$this->sNomeArquivoParent = "class.".$this->oConstrutor->sClasse."Parent.php";
		$this->sNomeArquivo = $this->oConstrutor->sClasse.".class.php";
		$this->sNomeArquivoParent = $this->oConstrutor->sClasse."Parent.class.php";
	}
	
	function geraAtributos(){
		$vAtributos = $this->oConstrutor->vAtributos;
		if (count($vAtributos) > 0){
			foreach($vAtributos as $sAtributo){
				$sNomeAtributo = substr($sAtributo,1);
				$this->sComentarioAtributos .= "* @param \$$sAtributo ".$sNomeAtributo."\n\t";
				$this->sAtributosConstrutor .= "\$".$sAtributo . ",";
				$this->sListaAtributos .= "/**\n* $sAtributo\n* @access private\n*/\nvar \$".$sAtributo . ";\n";
				$this->sGetSet .= "/**\n\t* Recupera o valor do atributo \$$sAtributo. \n\t* @return \$$sAtributo $sNomeAtributo\n\t*/\n\tfunction get".$sNomeAtributo."(){\n\t\treturn \$this->".$sAtributo.";\n\t}\n\t/**\n\t* Atribui valor ao atributo \$$sAtributo. \n\t* @param \$$sAtributo $sNomeAtributo\n\t* @access public\n\t*/\n\tfunction set".$sNomeAtributo."(\$".$sAtributo."){\n\t\t\$this->".$sAtributo." = \$".$sAtributo.";\n\t}\n\n\t";
				$this->sInicializacao .= "\$this->set".substr($sAtributo,1)."(\$$sAtributo);\n\t\t";
			}
			$this->sAtributosConstrutor = substr($this->sAtributosConstrutor,0,strlen($this->sAtributosConstrutor) - 1);
			$this->sComentarioAtributos = substr($this->sComentarioAtributos,0,strlen($this->sComentarioAtributos) - 2);
		}
	}
	
	function gera(){
			$this->geraAtributos();
			$sArquivo = "";
			$sArquivoParent = "";
			$sCaminhoArquivo = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivo;
			$sCaminhoArquivoParent = dirname(__FILE__) . "/../classes_geradas/".$this->sNomeArquivoParent;
			
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_classe_basica.txt");
			$sArquivo = join("",$vModelo);
			
			$sArquivo = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS#/",$this->sListaAtributos,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",$this->sAtributosConstrutor,$sArquivo);
			$sArquivo = preg_replace("/#INICIALIZACAO#/",$this->sInicializacao,$sArquivo);
			$sArquivo = preg_replace("/#GET_SET#/",$this->sGetSet,$sArquivo);
			$sArquivo = preg_replace("/#COMENTARIO_ATRIBUTOS#/",$this->sComentarioAtributos,$sArquivo);
			
			if (file_exists($sCaminhoArquivo)){
				unlink($sCaminhoArquivo);
			}
			$pArquivo = fopen($sCaminhoArquivo,"a+");
			fputs($pArquivo,$sArquivo);
			
			
			$vModeloParent = file(dirname(__FILE__)."/../modelos/modelo_classe_basica_parent.txt");
			$sArquivoParent = join("",$vModeloParent);
			$sArquivoParent = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivoParent);
			$sArquivoParent = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_ATRIBUTOS#/",$this->sListaAtributos,$sArquivoParent);
			$sArquivoParent = preg_replace("/#LISTA_ATRIBUTOS_CONSTRUTOR#/",$this->sAtributosConstrutor,$sArquivoParent);
			$sArquivoParent = preg_replace("/#INICIALIZACAO#/",$this->sInicializacao,$sArquivoParent);
			$sArquivoParent = preg_replace("/#GET_SET#/",$this->sGetSet,$sArquivoParent);
			$sArquivoParent = preg_replace("/#COMENTARIO_ATRIBUTOS#/",$this->sComentarioAtributos,$sArquivoParent);

			if (file_exists($sCaminhoArquivoParent)){
				unlink($sCaminhoArquivoParent);
			}
			$pArquivoParent = fopen($sCaminhoArquivoParent,"a+");
			fputs($pArquivoParent,$sArquivoParent);
			//highlight_string($sArquivo);
	}

}
?>