<?php

/* RECEIVE VALUE */
//$validateValue=$_POST['validateValue'];
//$validateId=$_POST['validateId'];
//$validateError=$_POST['validateError'];

/* RECEIVE VALUE */
$validateValue= (isset($_GET['fieldValue'])) ? $_GET['fieldValue'] : $_GET['fDescricao'] ;
$validateId= (isset($_GET['fieldId'])) ? $_GET['fieldId'] : "fDescricao" ;
$nIdCategoria = (isset($_GET['fieldIdP'])) ? $_GET['fieldIdP'] : $_GET['fIdCategoria'];

$validateError= "Categoria já existente no banco de dados, por favor escolha outro nome!";
$validateSuccess= "Categoria disponível!";
$validateSuccess2= "A Categoria continua sendo a mesma!";

/* RETURN VALUE */
$arrayToJs = array();
$arrayToJs[0] = $validateId;
$arrayToJs[1] = $validateError;
	
require_once("../constantes.php");
require_once(PATH."/classes/FachadaFinanceiroBD.class.php");

$oFachadaFinanceiroAux = new FachadaFinanceiroBD();

$vWhereCategoriaAux = array("descricao = '".$validateValue."'","ativo = 1");
$sOrderCategoriaAux = "";
$voCategoriaAux = $oFachadaFinanceiroAux->recuperaTodosCategoria(BANCO,$vWhereCategoriaAux,$sOrderCategoriaAux);

$oCategoriaAux = $oFachadaFinanceiroAux->recuperaCategoria($nIdCategoria,BANCO);
if(is_object($oCategoriaAux)){
	$sCategoriaAux = $oCategoriaAux->getDescricao();
}

if(count($voCategoriaAux) > 0){
	if($sCategoriaAux == $voCategoriaAux[0]->getDescricao()){
		$arrayToJs[2] = "true";
		$arrayToJs[1] = $validateSuccess2;
	}else{
		$arrayToJs[2] = "false";
		$arrayToJs[1] = $validateError;
	}
}else{
	$arrayToJs[2] = "true";
	$arrayToJs[1] = $validateSuccess;
}

//echo '{"jsonValidateReturn":["'.$arrayToJs[0].'","'.$arrayToJs[1].'","'.$arrayToJs[2].'"]}';
echo '["'.$arrayToJs[0].'",'.$arrayToJs[2].',"'.$arrayToJs[1].'"]';


/*if($validateValue =="karnius"){		// validate??
	$arrayToJs[2] = "true";			// RETURN TRUE
	echo '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';			// RETURN ARRAY WITH success
}else{
	for($x=0;$x<1000000;$x++){
		if($x == 990000){
			$arrayToJs[2] = "false";
			echo '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';		// RETURN ARRAY WITH ERROR
		}
	}
}*/

?>