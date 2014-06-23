<?php

class GeradoraForm{

	var $oConstrutor;
	var $sBanco;
	var $sBancoPrimeiraMaiuscula;
	var $sAtributosChave;
	var $sAtributosChaveSepAnd;
	var $sAtributosFormChave;
	var $sLinhasAtributos;
	var $oTabela;
	var $sNomeArquivo;



	function GeradoraForm($sBanco,$oConstrutor){
		$this->oConstrutor = $oConstrutor;
		$this->sBanco = $sBanco;
		$this->sNomeArquivo = "insere_altera_".$this->oConstrutor->sArquivo.".php";
	}
	
	function geraAtributos(){
		$vAtributos = $this->oConstrutor->vAtributos;
		$vCampos = $this->oConstrutor->vCampos;		
		if (count($vAtributos) > 0){
			$vAtribChave = array();
			foreach($vAtributos as $nIndice => $sAtributo){
				$sNomeAtributo = substr($sAtributo,1);
				$oCampo = $vCampos[$nIndice];
				$sAux = substr($sAtributo,3);
				if ($oCampo->Key != "PRI"){ 
					/*
						Se o campo for chave estrangeira, deve ser dado um tratamento para
						que seja mostrado o valor do campo do BD. na tela de visualização, ao
						invés de um valor de chave.
					*/
if (substr($oCampo->Field,0,3) == "ID_"){
$sTrataEstrangeira1 = 
"
\t<?php	
\t\$vo$sAux = \$oFachada".$this->sBancoPrimeiraMaiuscula."->recuperaTodos$sAux(BANCO_".strtoupper($this->sBanco).");
\t\$".$sAtributo." = is_object(\$o".$this->oConstrutor->sClasse.") ? \$o".$this->oConstrutor->sClasse."->getId$sAux() : 0;
\t?>
";
$sTrataEstrangeira2 = 
"
\t<?php	
\tif(count(\$vo".$sAux.")) {
\t\tforeach(\$vo".$sAux." as \$o".$sAux."BD) {
\t?>
 ";
$sTrataEstrangeira3 = 
"\t\t<option value=\"<?php echo \$o".$sAux."BD->getId()?>\" <?php echo (\$o".$sAux."BD->getId() == \$".$sAtributo.") ? \"selected\" : \"\"?>><?php echo \$o".$sAux."BD->getNome()?></option>";
$this->sLinhasAtributos .=  
$sTrataEstrangeira1.
"\t<tr>\n".
"\t\t<td>".substr($sAtributo,1)."</td>\n".
"\t<td>
\t<select name=\"f".substr($sAtributo,1)."\">".
"\t".$sTrataEstrangeira2."".
"\t".$sTrataEstrangeira3."".
"
\t<?php 
		}
	} else {
\t?>
\t\t<option value=\"\">Não há ítens cadastrados no sistema!</option>	
\t<?php
\t}//if(count(\$vo".$sAux.")) {
\t?>
\t</select>
\t</td>
\t</tr>
";
} else {
$sDefinicaoAtributo = 
"<input name=\"f".substr($sAtributo,1)."\" type=\"text\" value=\"<?php echo (is_object(\$o".$this->oConstrutor->sClasse.")) ? (\$o".$this->oConstrutor->sClasse."->get".substr($sAtributo,1)."()) : \"\"?>\" lang=\"vazio\" />";
$this->sLinhasAtributos .= 
"\t<tr>
	\t<td><label id=\"f".substr($sAtributo,1)."\">".substr($sAtributo,1)."</label></td>
	\t<td>".$sDefinicaoAtributo . "</td>
\t</tr>";

}
} else {
$this->sAtributosFormChave 
.= '<input type="hidden" name="f'.substr($sAtributo,1).'" value="<?php echo (is_object(\$o'.$this->oConstrutor->sClasse.')) ? (\$o'.$this->oConstrutor->sClasse.'->get'.substr($sAtributo,1).'()) : ""?>">';
array_push($vAtribChave,"\$".$sAtributo);
}
			}
			$this->sAtributosChave .= join(",",$vAtribChave);

		}
	}
	

	function gera(){
			$vBanco = explode("_",$this->sBanco);
			foreach($vBanco as $sNome)
				$this->sBancoPrimeiraMaiuscula .= ucfirst($sNome);
				
			$this->geraAtributos();
			$sArquivo = "";
			$vModelo = file(dirname(__FILE__)."/../modelos/modelo_insere_altera.txt");
			$sArquivo = join("",$vModelo);

			$sArquivo = preg_replace("/#BANCO#/",strtoupper($this->sBanco),$sArquivo);
			$sArquivo = preg_replace("/#BANCO_PRIMEIRA_MAIUSCULA#/",$this->sBancoPrimeiraMaiuscula,$sArquivo);
			$sArquivo = preg_replace("/#JAVA_SCRIPT#/",$this->sBanco,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CHAVE_SEP_AND#/",str_replace(","," and ",$this->sAtributosChave),$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE#/",$this->oConstrutor->sClasse,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_SIMPLES#/",$this->oConstrutor->sClasseSimples,$sArquivo);
			$sArquivo = preg_replace("/#NOME_CLASSE_MINUSCULO#/",$this->oConstrutor->sArquivo,$sArquivo);
			$sArquivo = preg_replace("/#LISTA_ATRIBUTOS_CHAVE#/",$this->sAtributosChave,$sArquivo);
			$sArquivo = preg_replace("/#ATRIBUTOS_FORM_CHAVE#/",$this->sAtributosFormChave,$sArquivo);
			$sArquivo = preg_replace("/#LINHAS_DE_ATRIBUTOS#/",$this->sLinhasAtributos,$sArquivo);
			$sCaminhoDiretorio = dirname(__FILE__) . "/../paginas_geradas/".$this->oConstrutor->sArquivo."/";
			$sCaminhoArquivo = dirname(__FILE__) . "/../paginas_geradas/".$this->oConstrutor->sArquivo."/".$this->sNomeArquivo;
			echo $sCaminhoArquivo;
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