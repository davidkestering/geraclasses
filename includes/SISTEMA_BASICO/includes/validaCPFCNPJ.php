<?php

/* RECEIVE VALUE */
//$validateValue=$_POST['validateValue'];
//$validateId=$_POST['validateId'];
//$validateError=$_POST['validateError'];

/* RECEIVE VALUE */
$validateValue= (isset($_GET['fieldValue'])) ? $_GET['fieldValue'] : $_GET['fCPFCNPJ'] ;
$validateId= (isset($_GET['fieldId'])) ? $_GET['fieldId'] : "fCPFCNPJ" ;

/* RETURN VALUE */
$arrayToJs = array();
$arrayToJs[0] = $validateId;
	
//RECEBE OS DADOS DO FORMULÁRIO

switch(strlen($validateValue)){
	case 11:
		$validateError= "CPF Inválido";
		$validateSuccess= "CPF Válido";
		$arrayToJs[1] = $validateError;
		$cpf = $validateValue;
		$status = false;
		$arrayToJs[2] = "false";
		//VERIFICA SE O QUE FOI INFORMADO É NÚMERO
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
		
				//CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
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
		
				//CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
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
		
				//VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
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
					$arrayToJs[1] = $validateSuccess;
				}else{
					$status = false;
					$arrayToJs[2] = "false";
					$arrayToJs[1] = $validateError;
				}
			}//FECHA ELSE
		}//FECHA ELSE(is_numeric)
		
		//echo '{"jsonValidateReturn":["'.$arrayToJs[0].'","'.$arrayToJs[1].'","'.$arrayToJs[2].'"]}';
		echo '["'.$arrayToJs[0].'",'.$arrayToJs[2].']';
	break;
	case 14:
		$validateError= "CNPJ Inválido";
		$validateSuccess= "CNPJ Válido";
		$arrayToJs[1] = $validateError;
		$cnpj = $validateValue;
		$status = false;
		$arrayToJs[2] = "false";
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
		echo '["'.$arrayToJs[0].'",'.$arrayToJs[2].',"'.$arrayToJs[1].'"]';
	break;
	default:
		$validateError= "CPF/CNPJ Inválido";
		$validateSuccess= "CPF/CNPJ Válido";
		$arrayToJs[1] = $validateError;
		$arrayToJs[2] = "false";		
		//echo '{"jsonValidateReturn":["'.$arrayToJs[0].'","'.$arrayToJs[1].'","'.$arrayToJs[2].'"]}';
		echo '["'.$arrayToJs[0].'",'.$arrayToJs[2].',"'.$arrayToJs[1].'"]';
	break;
}

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