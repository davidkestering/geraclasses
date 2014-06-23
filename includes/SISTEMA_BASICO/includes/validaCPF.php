<?php

/* RECEIVE VALUE */
//$validateValue=$_POST['validateValue'];
//$validateId=$_POST['validateId'];
//$validateError=$_POST['validateError'];

/* RECEIVE VALUE */
$validateValue=$_GET['fieldValue'];
$validateId=$_GET['fieldId'];

$validateError= "CPF Invแlido";
$validateSuccess= "CPF Vแlido";

/* RETURN VALUE */
$arrayToJs = array();
$arrayToJs[0] = $validateId;
$arrayToJs[1] = $validateError;
	
//RECEBE OS DADOS DO FORMULมRIO
$cpf = $validateValue;
$status = false;
$arrayToJs[2] = "false";

//VERIFICA SE O QUE FOI INFORMADO ษ NฺMERO
if(!is_numeric($cpf)) {
	$status = false;
	$arrayToJs[2] = "false";
}else {
	//VERIFICA
	if( ($cpf == '11111111111') || ($cpf == '22222222222') || ($cpf == '33333333333') || ($cpf == '44444444444') || ($cpf == '55555555555') || ($cpf == '66666666666') || ($cpf == '77777777777') || ($cpf == '88888888888') || ($cpf == '99999999999') || ($cpf == '00000000000') || (strlen($cpf) != 11)) {
		$status = false;
		$arrayToJs[2] = "false";
	}else {
		//PEGA O DIGITO VERIFIACADOR
		$dv_informado = substr($cpf, 9,2);
		
		for($i=0; $i<=8; $i++) {
			$digito[$i] = substr($cpf, $i,1);
		}

		//CALCULA O VALOR DO 10บ DIGITO DE VERIFICAวยO
		$posicao = 10;
		$soma = 0;

		for($i=0; $i<=8; $i++) {
			$soma = $soma + $digito[$i] * $posicao;
			$posicao = $posicao - 1;
		}

		$digito[9] = $soma % 11;
		
		if($digito[9] < 2) {
			$digito[9] = 0;
		}else {
			$digito[9] = 11 - $digito[9];
		}

		//CALCULA O VALOR DO 11บ DIGITO DE VERIFICAวรO
		$posicao = 11;
		$soma = 0;

		for ($i=0; $i<=9; $i++) {
			$soma = $soma + $digito[$i] * $posicao;
			$posicao = $posicao - 1;
		}
		
		$digito[10] = $soma % 11;
		
		if ($digito[10] < 2) {
			$digito[10] = 0;
		}else {
			$digito[10] = 11 - $digito[10];
		}

		//VERIFICA SE O DV CALCULADO ษ IGUAL AO INFORMADO
		$dv = $digito[9] * 10 + $digito[10];
		/*if ($dv != $dv_informado) {
			$status = false;
			$arrayToJs[2] = "false";
		}else{
			$status = true;
			$arrayToJs[2] = "true";
		}*/
		if($dv == $dv_informado){
			$status = true;
			$arrayToJs[2] = "true";
		}else{
			$status = false;
			$arrayToJs[2] = "false";
		}
	}//FECHA ELSE
}//FECHA ELSE(is_numeric)

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