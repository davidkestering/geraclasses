<?php
$nHora = (int) date("H");
$sSaudacao = "";

switch(true) {
	case $nHora >= 6 && $nHora < 12:
		$sSaudacao = "Bom dia ";
	break;
	case $nHora >= 12 && $nHora < 18:
		$sSaudacao = "Boa tarde ";
	break;
	case ($nHora >= 18 && $nHora <= 23) || ($nHora >= 0 && $nHora < 6):
		$sSaudacao = "Boa noite ";
	break;
}

if(isset($_SESSION['oLoginAdm']) && is_object($_SESSION['oLoginAdm']->oUsuario)) {
	$sSaudacao .= "<strong>".$_SESSION['oLoginAdm']->oUsuario->getNmUsuario()."</strong>.";
}
?>
<div id="BARRA"><?php echo (isset($sSaudacao) && $sSaudacao != "") ? $sSaudacao : ""?></div>