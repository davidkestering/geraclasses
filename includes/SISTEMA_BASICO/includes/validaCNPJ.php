<?php
//á
/* RECEIVE VALUE */
//$validateValue=$_POST['validateValue'];
//$validateId=$_POST['validateId'];
//$validateError=$_POST['validateError'];

/* RECEIVE VALUE */
$validateValue= (isset($_GET['fieldValue'])) ? $_GET['fieldValue'] : $_GET['fCPFCNPJ'] ;
$validateId= (isset($_GET['fieldId'])) ? $_GET['fieldId'] : $_GET['fieldId'] ;

$validateError= "CNPJ Inválido";
$validateSuccess= "CNPJ Válido";

/* RETURN VALUE */
$arrayToJs = array();
$arrayToJs[0] = $validateId;
$arrayToJs[1] = $validateError;
	
//RECEBE OS DADOS DO FORMULÁRIO
$cnpj = $validateValue;
$status = false;
$arrayToJs[2] = "false";

if (strlen($cnpj) == 14){
	if(!is_numeric($cnpj)) {
		$status = false;
		$arrayToJs[2] = "false";
	}else{
		$soma1 =	($cnpj[0] * 5) +
					($cnpj[1] * 4) +
					($cnpj[2] * 3) +
					($cnpj[3] * 2) +
					($cnpj[4] * 9) +
					($cnpj[5] * 8) +
					($cnpj[6] * 7) +
					($cnpj[7] * 6) +
					($cnpj[8] * 5) +
					($cnpj[9] * 4) +
					($cnpj[10] * 3) +
					($cnpj[11] * 2);
		$resto1 = $soma1 % 11;
		$digito1 = $resto1 < 2 ? 0 : 11 - $resto1;
		
		$soma2 =	($cnpj[0] * 6) +
					($cnpj[1] * 5) +
					($cnpj[2] * 4) +
					($cnpj[3] * 3) +
					($cnpj[4] * 2) +
					($cnpj[5] * 9) +
					($cnpj[6] * 8) +
					($cnpj[7] * 7) +
					($cnpj[8] * 6) +
					($cnpj[9] * 5) +
					($cnpj[10] * 4) +
					($cnpj[11] * 3) +
					($cnpj[12] * 2);
		$resto2 = $soma2 % 11;
		$digito2 = $resto2 < 2 ? 0 : 11 - $resto2;		
		if(($cnpj[12] == $digito1) && ($cnpj[13] == $digito2)){
			$status = true;
			$arrayToJs[2] = "true";
		}
	}
}

//echo '{"jsonValidateReturn":["'.$arrayToJs[0].'","'.$arrayToJs[1].'","'.$arrayToJs[2].'"]}';
echo '["'.$arrayToJs[0].'",'.$arrayToJs[2].']';
//echo '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';

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