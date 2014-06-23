<?php
session_start();

switch ($_POST['sOP']){
	case "Conectar":
		if (count($_POST) > 0){
			$vNomeParametro = array("fHost","fUsuario","fSenha","fTipo","fNome");
			$nContador = 0;
			foreach($_POST as $sNomeVariavel => $vrValorVariavel){
				if(in_array($sNomeVariavel,$vNomeParametro)){
					$nContador++;
				}
			}//foreach($_POST as $sNomeVariavel => $vrValorVariavel){

			if (count($vNomeParametro) != $nContador){
				die("Os parâmetros esperados para conexão com o banco de dados, não foram todos passados!");
			}//if (count($vNomeParametro) != $nContador){
		} else {
			die("Não foram passados parâmetros esperados por essa página!");
		}//if (count($_POST) > 0){

		$_SESSION["vParametroConexao"] = array();
		$_SESSION["vParametroConexao"]['HOST'] = $_POST["fHost"];
		$_SESSION["vParametroConexao"]['USUARIO'] = $_POST["fUsuario"];
		$_SESSION["vParametroConexao"]['SENHA'] = $_POST["fSenha"];
		$_SESSION["vParametroConexao"]['TIPO'] = $_POST["fTipo"];
		$_SESSION["vParametroConexao"]['BANCO'] = $_POST["fNome"];

		header("Location: lista_tabelas.php");
		exit;
	break;

	case "Gerar":

		
	$vParametroConexao = array();
	$vParametroConexao = $_SESSION['vParametroConexao'];

	
		if (!isset($_SESSION['vParametroConexao'])){
			header("Location:index.php");
			exit;
		}


		$fTabela = array();
		
		$fTabela = $_POST['fTabela'];
		
		if ($fTabela && is_array($fTabela) && count($fTabela) > 0){
			include_once("classes/class.Conexao.php");
			include_once("classes/class.Banco.php");
			include_once("classes/class.Tabela.php");
			include_once("classes/class.Construtor.php");
			include_once("classes/class.GeradoraBasica.php");
			include_once("classes/class.GeradoraBD.php");
			include_once("classes/class.GeradoraInteriorFachada.php");
    		include_once("classes/class.GeradoraFachada.php");
			//include_once("classes/class.GeradoraForm.php");
			//include_once("classes/class.GeradoraProcessa.php");
			//include_once("classes/class.GeradoraVisualiza.php");

			$oConexao = new Conexao($vParametroConexao['HOST'],$vParametroConexao['USUARIO'],$vParametroConexao['SENHA'],$vParametroConexao['TIPO'],$vParametroConexao['BANCO']);
	        $oBanco = new Banco($oConexao->sBanco);

			$vNomeClasses = array();
			$sFachada = "";
			
			foreach($fTabela as $sNomeTabela){
				$oConstrutor = new Construtor($sNomeTabela);
				$oGeradoraBasica = new GeradoraBasica($oConstrutor);
				$oGeradoraBasica->gera();
				array_push($vNomeClasses,$oGeradoraBasica->oConstrutor->sClasse.".class.php");
				array_push($vNomeClasses,$oGeradoraBasica->oConstrutor->sClasse."BD.class.php");
?>

				
<p><a href="classes_geradas/<?php echo $oGeradoraBasica->oConstrutor->sClasse?>.class.txt">Baixar</a> 
</p>
<p><strong>* Classe Básica - Apenas com Get e Set</strong></p>
<iframe width="600" height="400" src="classes_geradas/<?php echo $oGeradoraBasica->oConstrutor->sClasse?>.class.txt"></iframe>
<?php
				$oGeradoraBD = new GeradoraBD($oConstrutor);
				$oGeradoraBD->gera();

				?>
				
<p><a href="classes_geradas/<?php echo $oGeradoraBD->oConstrutor->sClasse?>BD.class.txt">Baixar</a> 
</p>
<p><strong>* Classe BD - Com os métodos que interagem com o Banco de Dados</strong></p>
<iframe width="600" height="400" src="classes_geradas/<?php echo $oGeradoraBD->oConstrutor->sClasse?>BD.class.txt"></iframe>
				<?php
				$oGeradoraInteriorFachada = new GeradoraInteriorFachada($oConstrutor);
				$oGeradoraInteriorFachada->gera();
				$sFachada .= "\n\t".$oGeradoraInteriorFachada->sInteriorFachada;
				
				//$oGeradoraForm = new GeradoraForm($vParametroConexao['BANCO'],$oConstrutor);
				//$oGeradoraForm->gera();
				
				//$oGeradoraProcessa = new GeradoraProcessa($vParametroConexao['BANCO'],$oConstrutor);
				//$oGeradoraProcessa->gera();

				//$oGeradoraVisualiza = new GeradoraVisualiza($vParametroConexao['BANCO'],$oConstrutor);
				//$oGeradoraVisualiza->gera();
				
				unset($oGeradoraBasica);
				unset($oGeradoraBD);
				unset($oGeradoraInteriorFachada);
				//unset($oGeradoraForm);
				//unset($oGeradoraProcessa);
				//unset($oGeradoraVisualiza);

			}
			$oGeradoraFachada = new GeradoraFachada($vParametroConexao['BANCO'],$sFachada,$vNomeClasses);
			$oGeradoraFachada->gera();
			$sNomeArquivoSemDir = "Fachada".$vParametroConexao['BANCO']."BD.class.php";
			unset($oGeradoraFachada);	
			
				?>
				
<p><a href="classes_geradas/<?php echo $sNomeArquivoSemDir?>">Baixar</a> 
</p>
<p><strong>* Fachada BD </strong></p>
<!--<iframe width="600" height="400" src="classes_geradas/<?php//$sNomeArquivoSemDir?>"></iframe>-->
<?php
		} else {
			die("Você precisa selecionar as tabelas do banco de dados!");
		}
	break;

	default:
		die("Nenhuma operação válida foi selecionada!");
	break;
}
?>