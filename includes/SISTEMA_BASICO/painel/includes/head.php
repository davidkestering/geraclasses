<title>Portal ORM &bull; área Administrativa</title>
<?php
//á
	$sCaminho = "../../../../";
?>
<link href="<?php echo $sCaminho?>css/padrao.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $sCaminho?>css/impressao.css" media="print" rel="stylesheet" type="text/css">
<link href="<?php echo $sCaminho?>css/calendar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $sCaminho?>css/categoria.css" rel="stylesheet" type="text/css" />

<script type="text/JavaScript" src="<?php echo $sCaminho?>js/controle.js"></script>
<script type="text/JavaScript" src="<?php echo $sCaminho?>js/calendar.js"></script>
<script type="text/JavaScript" src="<?php echo $sCaminho?>js/calendar-pt-br.js"></script>
<script type="text/JavaScript" src="<?php echo $sCaminho?>js/calendar-setup.js"></script>
<script type="text/JavaScript" src="<?php echo $sCaminho?>js/jquery-1.7.min.js"></script>

<!--
INFELIZMENTE NAO FUNCIONA POR QUE EM TODO REFRESH O CODIGO ENTENDE QUE ESTÁ SAINDO DA PAGINA
CONTROLE PARA FECHAMENTO DE NAVEGADOR E DE PÁGINA 
<script type="text/javascript">
	/*function fechaSite(){
		$(window).unload(function(e){
			if(recarregada == 0){
				var resposta = confirm("Tem certeza que deseja sair do sistema do Portal ORM?")
				if (resposta == true){
					$.ajax({
						type: 'POST',
						data: 'sOP=Logoff',
						url:'administrativo/login/processa.php',
						success: function(retorno){
							alert("Usuário deslogado do sistema com sucesso!");
						}
					});
				//}else{
					//alert("Não");
				}
			}
        });
		var recarregada = 0;
	}*/
</script>
-->