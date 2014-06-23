<?php
//require_once($_SERVER['DOCUMENT_ROOT']."/siig/controller/Controller.php");

/**
 * Classe que implementa rotinas de manipulação de arquivos.
 * @author Equipe Seduc 
 * @version 0.5.32
 * @since 01/10/2006
 * @copyright Copyright © 2006, R & G Consultoria e Desenvolvimento de Sistemas Ltda.
 * @access public
 * @package saps
 */

class UtilsFile {

	private function __construct(){
	}

	public static function delete_directory($path){
		$dir = new RecursiveDirectoryIterator($path);
	    //Remove all files
	    foreach(new RecursiveIteratorIterator($dir) as $file){
	    	unlink($file);
	    }
	   	//Remove all subdirectories
	   	foreach($dir as $subDir){
	   		//If a subdirectory can't be removed, it's because it has subdirectories, so recursiveRemoveDirectory is called again passing the subdirectory as path
	        if(!@rmdir($subDir)){
	        	self::delete_directory($subDir);
	        }
	    }
	    //Remove main directory
	    @rmdir($path);
	    return true;
	}

    	public static function formatNome($frase){

		$palavras = str_word_count($frase, 1);
		$count_palavras = str_word_count($frase);

		for($i=0; $i < $count_palavras; $i++){

    		$palavra = (strlen($palavras[$i]) > 2) ? (ucwords(strtolower($palavras[$i]))) : (strtolower($palavras[$i]));

    		$nova_frase = ($i < $count_palavras) ? $palavra." " : $palavra;

    		print $nova_frase;

		}
	}
		
	/*public static function getIP(){		
		if(($_SERVER['REMOTE_ADDR'] == '192.168.200.112') || ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')){
			$ip = gethostbyname(trim($_SERVER['HTTP_X_FORWARDED_FOR']));
			$ip = ($hostnameWin) ? $hostnameWin : $_SERVER['REMOTE_ADDR'];			
		}else{	
			$ip = gethostbyname(trim($_SERVER['HTTP_X_FORWARDED_FOR']));	
			$ip = $_SERVER['REMOTE_ADDR'];					
		}

		
		return $ip;	
	}*/
	public static function getIP(){			
		return gethostbyname($_SERVER['HTTP_X_FORWARDED_FOR']);
	}

	

	public static function getIdAcao($id_menu,$id_modulo){
		$oSeg_acao = new Seg_acao();	
//		printvar($id_menu, $id_modulo);
		$aWheres[] = " cd_modulo = ".$id_modulo;
		$aWheres[] = " cd_menu = ".$id_menu;
		$aSeg_acao = $oSeg_acao->selectAcao($aWheres);	
		$aSeg_acao = $aSeg_acao[0];		
		return $aSeg_acao['cd_acao'];		
	}

	

	public static function setTransacao(){
		$acao = array();

		$acao['inc']['ds'] = 'INCLUIU';
		//$acao['inc']['cd'] = 0;
		$acao['alt']['ds'] = 'ALTEROU';
		//$acao['alt']['cd'] = 1;
		$acao['canc']['ds'] = 'CANCELOU';
		//$acao['canc']['cd'] = 2;
		$acao['reativar']['ds'] = 'REATIVOU';
		//$acao['reativar']['cd'] = 3;
		$acao['imprimir']['ds'] = 'IMPRIMIU';
		//$acao['imprimir']['cd'] = 4;
		$acao['consultar']['ds'] = 'CONSULTOU';
		//$acao['consultar']['cd'] = 5;		

		return $acao;
	}

	

	public static function getTransacao($entidade,$nome){

		$transacao = UtilsFile::setTransacao();

		return $transacao[$entidade][$nome];

	}

	

	public static function getLabel($entidade,$valor){

		$transacao = UtilsFile::setTransacao();

		$res = array_keys($transacao[$entidade],$valor);

		return $res[0];

	}

	

	#METODO GERA??O DE LOG	

	public static function geraLog($acao,$id_transacao){ #Transacao ? a acao em dotos os arquivos frmRes ex: inc, alt, canc, reativar, imprimir e consultar
		//printvar($max_id);
		$idusuario = Session::getSession("Usuario.id"); #Pega o id do usuario que esta logado
		$id_modulo = Session::getSession("Modulo.id_modulo"); #Pega o id do modulo acessado pelo usuario
		$id_menu = Session::getSession("Menu.id_menu"); #Pega o id do menu q o usuario esta acessando
		//printvardie($id_modulo, $id_menu, $idusuario);
		$ip 	   = UtilsFile::getIP(); #Pega o ip da maquina do usuario;
		$acaoId    = UtilsFile::getIdAcao($id_menu,$id_modulo); #Pega id da acao referente ao menu e modulo acessado;
		$transDs   = UtilsFile::getTransacao($acao,'ds'); #Pega a descricao da transacao
// 		$transCd   = UtilsFile::getTransacao($transacao,'cd'); #Pega o id da transacao
		$transCd   = $id_transacao; #Pega o id da transacao

		$oSeg_logging = new Seg_logging();
		$oSeg_loggingVO = new Seg_loggingVO();
	
		//SingletonPDO::beginTrans();
		$oSeg_loggingVO->set_cd_acao($acaoId);
		$oSeg_loggingVO->set_cd_usuario($idusuario);
		$oSeg_loggingVO->set_ip($ip);
		$oSeg_loggingVO->set_cd_transacao($transCd);
		$oSeg_loggingVO->set_descricao($transDs);

		$oSeg_logging->insert($oSeg_loggingVO,true);
		//printvar($oSeg_logging);die;
		return array($ip,$acaoId,$idusuario,$transDs,$transCd);
	}

	function desabilitaTeclaF5(){ #ADD EM TODOS OS ARUIVOS (res...php)
		
		$html =  print('<html>');
		$html .= print('<head>');
		$html .= print('<script type="text/javascript" src="../js/shortcuts.js"></script>');					
		$html .= print('<script type="text/javascript">');
		$html .= print('function arquivo(arq){'); #recebe o nome_do_arquivo.php (res...)
			//$html .= print('alert(arq);');
			$html .= print('var file = arq;');
			$html .= print('var res = file.substring(0, 3);');#separa as 3 primeiras letras (res)
			//$html .= print('alert(res);');
			$html .= print('if(res == "res"){'); #faz a codi��o			
			$html .= print('shortcut.add("F5",function(){'); #ativa a tecla F5 apenas para enviar o alert abaixo
			$html .= print('alert("VOC\xCA REALIZOU UMA OPERA\xC7\xC3O ILEGAL.");');
			$html .= print('return true;');
			$html .= print('});');
			$html .= print('}');	
			//$html .= print('alert("passou");');	
		$html .= print('}');
		$html .= print('</script>');
		$html .= print('');
		$html .= print('</head>');
		$html .= print('');
		$html .= print('<body>');		
		$html .= print('</body>');
		$html .= print('</html>');
		
		$arquivo = explode("/",$_SERVER['PHP_SELF']); #pega o caminho do arquivo
		//printvar($arquivo);
		$arq = $arquivo[4]; #pega o nome_do_arquivo.php, neste caso o arquivo(resFrm...php)
		print"<script>arquivo('".$arq."');</script>"; #chama a fun��o js
		//return $html;		
	}
	/*function remove_acentos( $msg )
	{
		$a = array(
			'/[�����]/'   => 'A',
			'/[�����]/'    => 'a',
			'/[����]/'      => 'E',
			'/[����]/'      => 'e',
			'/[����]/'        => 'I',
			'/[����]/'          => 'i',
			'/[�����]/' => 'O',
			'/[�����]/'   => 'o',
			'/[����]/'    => 'U',
			'/[����]/'     => 'u',
			'/�/'            => 'c',
			'/�/'            => 'C'
		);
		return preg_replace( array_keys($a), array_values($a), $msg);
	}*/
	
	function RemoveAcentos ($sString="", $mesma=1) {
	    if($sString != "") {
	        $com_acento = "à á â ã ä è é ê ë ì í î ï ò ó ô õ ö ù ú û ü À �? Â Ã Ä È É Ê Ë Ì �? Î Ò Ó Ô Õ Ö Ù Ú Û Ü ç Ç ñ Ñ";
	        $sem_acento = "a a a a a e e e e i i i i o o o o o u u u u A A A A A E E E E I I I O O O O O U U U U c C n N";
	        $c = explode(' ',$com_acento);
	        $s = explode(' ',$sem_acento);
	        $i=0;
	        foreach ($c as $letra) {
	            if (ereg($letra, $sString)) {
	                $pattern[] = $letra;
	                $replacement[] = $s[$i];
	            }
	            $i=$i+1;
	        }
	        if (isset($pattern)) {
	            $i=0;
	            foreach ($pattern as $letra) {
	                $sString = eregi_replace($letra, $replacement[$i], $sString);
	                $i=$i+1;
	            }
	            return $sString;
	        }
	        if ($mesma != 0) {
	             return $sString;
	        }
	    }
	return "";
	}
	function tipo_tramitacao($tramitacao){
		switch($tramitacao){
			case "AB":
				$nm = "CADASTRO";
			break;
			case "EN":
				$nm = "ENCAMINHADO";
			break;
			case "RE":
				$nm = "RECEBIDO";
			break;
			case "LP":
				$nm = "LIBERADO PARA ANÁLISE";
			break;
			case "AP":
				$nm = "ACEIT0 PELO ANALISADOR";
			break;
			case "AN":
				$nm = "ANALISADO/DESPACHADO";
			break;
			case "DP":
				$nm = "DEVOLVIDO AO PROTOCOLISTA";
			break;
			case "AD":
				$nm = "DEVOLUÇÃO ACEITA";
			break;
			case "FI":
				$nm = "FINALIZADO";
			break;
            case "DS":
				$nm = "DESARQUIVADO";
			break;
			case "SU":
				$nm = "SUSPENSO";
			break;			
			case "CA":
				$nm = "CANCELADO";
			break;
			case "DE":
				$nm = "EM DILIGENCIA";
			break;
			case "AT":
				$nm = "ATIVO";
			break;
		}
		return htmlentities($nm,ENT_QUOTES,"UTF-8");
	}	

	function upload($arquivo,$caminho,$tmp_name){
		if(!(empty($arquivo))){
			$arquivo1 = $arquivo;
			$arquivo_minusculo = strtolower($arquivo);
			$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","é","è","ó","ò","+","=","*","&","(",")","!","#","?","`","ã"," ","©");
			$arquivo_tratado = str_replace($caracteres,"",$arquivo_minusculo);
			$destino = $caminho."/".$arquivo_tratado;
			if(move_uploaded_file($tmp_name,$destino)){
				return true;
			}else{
				return false;
			}
		}
	}

	function GeradorSenha($tipo="L N L N L N") 
	{
	/* o explode retira os espa�os presentes entre as letras (L) e n�meros (N) */        
	        $tipo = explode(" ", $tipo);
	/* Cria��o de um padr�o de letras e n�meros (no meu caso, usei letras mai�sculas
	 * mas voc� pode intercalar maiusculas e minusculas, ou adaptar ao seu modo.)
	 */
	        $padrao_letras = "A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|X|W|Y|Z";
	        $padrao_numeros = "0|1|2|3|4|5|6|7|8|9";
	/* criando os arrays, que armazenar�o letras e n�meros
	 * o explode retire os separadores | para utilizar as letras e n�meros
	 */
	        $array_letras = explode("|", $padrao_letras);
	        $array_numeros = explode("|", $padrao_numeros);
	/* cria a senha baseado nas informa��es da fun��o (L para letras e N para n�meros) */
	        $senha = "";
	        for ($i=0; $i<sizeOf($tipo); $i++) {
	            if ($tipo[$i] == "L") {
	                $senha.= $array_letras[array_rand($array_letras,1)];
	            } else {
	                if ($tipo[$i] == "N") {
	                    $senha.= $array_numeros[array_rand($array_numeros,1)];
	                }
	            }
	        }
	// informa qual foi a senha gerada para o usu�rio naquele momento
	        //echo "A senha gerada &#233;: " . $senha;
	        return $senha;
	}
}


	// FUN��O QUE 
    function printvar($args)
    {		
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha   = $dbt[0]['line']; 
        $arquivo = $dbt[0]['file'];
        echo "<fieldset style='border:1px solid; border-color:#F00;background-color:#FFF000;legend'><b>Arquivo:</b>".$arquivo."<b><br>Linha:</b><legend><b>Debug On : printvar ( )</b></legend> $linha</fieldset>";
        
        $args = func_get_args();				
        foreach($args as $idx=> $arg)
        {
            echo "<fieldset style='background-color:#CBA; border:1px solid; border-color:#00F;'><legend><b>ARG[$idx]</b></legend>";			
			echo "<pre style='background-color:#CBA; width:100%; heigth:100%;'>";
            print_r($arg);
			echo "</pre>";
            echo "</fieldset><br>";
        }
    }
    
    function printvardie($args) {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha   = $dbt[0]['line']; 
        $arquivo = $dbt[0]['file'];
        echo "<fieldset style='border:1px solid; border-color:#F00;background-color:#FFF000;legend'><b>Arquivo:</b>".$arquivo."<b><br>Linha:</b><legend><b>Debug On : printvardie ( )</b></legend> $linha</fieldset>";
        
        foreach($args as $idx=> $arg)
        {
            echo "<fieldset style='background-color:#CBA; border:1px solid; border-color:#00F;'><legend><b>ARG[$idx]</b></legend>";			
			echo "<pre style='background-color:#CBA; width:100%; heigth:100%;'>";
            print_r($arg);
            echo "</pre>";
            echo "</fieldset><br>";
        }
        die();
    }

    /**
     *  Mesma funcao do printvar mas não imprime com formatacao html
     * facilitando a exibicao no firebug
     * @param <type> $args
     * @since 27/05/2009
     * @author Philipe Barra
     */
    function printVarAjax($args) {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha   = $dbt[0]['line'];
        $arquivo = $dbt[0]['file'];
        echo "=================================================================================\n";
        echo "Arquivo:".$arquivo."\nLinha:$linha\nDebug On : printvarajax ( )\n";
		echo "=================================================================================\n";
		
        foreach($args as $idx=> $arg)
        {
            echo "-----  ARG[$idx]  -----\n";
            print_r($arg);
            echo "\n \n";
        }
    }

    /**
     *  Mesma funcao do printdie mas não imprime com formatacao html
     * facilitando a exibicao no firebug
     * @param <type> $args
     * @since 25/05/2009
     * @author Philipe Barra
     */
    function printVarDieAjax($args) {
        $args = func_get_args();
        $dbt = debug_backtrace();
        $linha   = $dbt[0]['line'];
        $arquivo = $dbt[0]['file'];
        echo "=================================================================================\n";
        echo "Arquivo:".$arquivo."\nLinha:$linha\nDebug On : printvardieajax ( )\n";
		echo "=================================================================================\n";
		
        foreach($args as $idx=> $arg)
        {
            echo "-----  ARG[$idx]  -----\n";
            print_r($arg);
            echo "\n \n";
        }
        die();
    }
?>