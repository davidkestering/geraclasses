<?php
//á
class Upload {
	
	/**
	* Construtor de Upload
	*/
	function __construct(){
	}
	
	function fazUploadArquivo($vsDiretorioDestino,$sOrigem,$oObjeto,$sElemento,$sTipo){
		/*echo "<br />";
		print_r($vsDiretorioDestino);
		echo "<br />";
		print_r($sOrigem);
		echo "<br />";
		print_r($oObjeto);
		echo "<br />";
		print_r($sElemento);
		echo "<br />";
		print_r($sTipo);
		echo "<br />";
		die();*/
		
		$getElemento = "get".$sElemento;
		switch($sTipo){
			case "ARQUIVO":
				if(isset($vsDiretorioDestino) && count($vsDiretorioDestino) > 0){
					foreach($vsDiretorioDestino as $sDiretorioDestino) {
						if(isset($sDiretorioDestino) && $sDiretorioDestino != "" && is_dir($sDiretorioDestino)){
							$sDestinoFinal = $sDiretorioDestino.$oObjeto->$getElemento();
							copy($sOrigem,$sDestinoFinal);
						}//if(isset($sDiretorioDestino) && $sDiretorioDestino != "" && is_dir($sDiretorioDestino)){
					}//foreach($vsDiretorioDestino as $nTamanho => $sDiretorioDestino) {
				}//if(isset($vsDiretorioDestino) && count($vsDiretorioDestino) > 0){
			break;
			case "IMAGEM":
				if(isset($vsDiretorioDestino) && count($vsDiretorioDestino) > 0){
					$nCount = 0;
					foreach($vsDiretorioDestino as $nTamanho => $sDiretorioDestino) {
						if(isset($sDiretorioDestino) && $sDiretorioDestino != "" && is_dir($sDiretorioDestino)){
							$sDestinoFinal = $sDiretorioDestino.$oObjeto->$getElemento();
							if(copy($sOrigem,$sDestinoFinal)) {
								$vImagem = getimagesize($sDestinoFinal);
								$nLargura = floor($vImagem[0]);
								$nAltura = floor($vImagem[1]);
					
								if($nLargura > $nTamanho || $nAltura > $nTamanho) {
									if($nTamanho != 116){
										if($nLargura > $nAltura) {
											$nMaior = $nLargura;
											$nMenor = $nAltura;
											$nProporcao = 100 - (100 * $nTamanho / $nMaior);
											$nMenor = floor($nMenor - ($nMenor * $nProporcao / 100));
											$vDimensao['largura'] = $nTamanho;
											$vDimensao['altura'] = $nMenor;
										} else {
											$nMaior = $nAltura;
											$nMenor = $nLargura;
											$nProporcao = 100 - (100 * $nTamanho / $nMaior);
											$nMenor = floor($nMenor - ($nMenor * $nProporcao / 100));
											$vDimensao['altura'] = $nTamanho;
											$vDimensao['largura'] = $nMenor;
										}
									}else{
										$nMaior = $nLargura;
										$nMenor = $nAltura;
										$nProporcao = 100 - (100 * $nTamanho / $nMaior);
										$nMenor = floor($nMenor - ($nMenor * $nProporcao / 100));
										$vDimensao['largura'] = $nTamanho;
										$vDimensao['altura'] = $nMenor;
									}
								} else {
									$vDimensao['largura'] = $nLargura;
									$vDimensao['altura'] = $nAltura;
								}//if($nLargura > $nTamanho || $nAltura > $nTamanho) {
			
								// FORMATO DAS FOTOS
								$sNomeArquivo = strtolower($sDestinoFinal);
								$sTipo = substr($sNomeArquivo,strlen($sNomeArquivo)-4,4);
			
								if($sTipo == ".jpg")
									$iFotoOriginal = imagecreatefromjpeg($sDestinoFinal);
								elseif($sTipo == ".gif")
									$iFotoOriginal = imagecreatefromgif($sDestinoFinal);
									
								$iFotoFinal = imagecreatetruecolor($vDimensao['largura'],$vDimensao['altura']);
								imagecopyresampled($iFotoFinal,$iFotoOriginal,0,0,0,0,$vDimensao['largura'],$vDimensao['altura'],$nLargura,$nAltura);
								
								if($sTipo == ".jpg")
									imagejpeg($iFotoFinal,$sDestinoFinal);
								elseif($sTipo == ".gif")
									imagegif($iFotoFinal,$sDestinoFinal);
								
								$nCount++;
									
								//die();
								if($nCount == count($vsDiretorioDestino)) {
									//unlink($sOrigemFinal);
									$nCount = 0;
								}//if($nCount == count($vsDiretorioDestino)) {
							}//if(copy($sOrigem,$sDestinoFinal)) {
						}//if(isset($sDiretorioDestino) && $sDiretorioDestino != "" && is_dir($sDiretorioDestino)){
					}//foreach($vsDiretorioDestino as $nTamanho => $sDiretorioDestino) {
				}//if(isset($vsDiretorioDestino) && count($vsDiretorioDestino) > 0){
			break;
		}//switch($sTipo){
		
		return false;
	}
	

	/**
	* Faz a exclusão do arquivo do Artigo no Servidor. 
	* @param $nAtivo Ativo
	* @access public
	*/
	function excluiArquivoServidor($sDiretorio,$sNomeArquivo){
		$sDestino = $sDiretorio . $sNomeArquivo;
		if(isset($sDestino) && file_exists($sDestino) && isset($sNomeArquivo) && $sNomeArquivo != "")
			return unlink($sDestino);
		
		return 0;
	}
	
	//MODELO ORIGINAL
	/*
	function fazUploadArquivo($vsDiretorioDestino,$sOrigem){
	
		foreach($vsDiretorioDestino as $nTamanho => $sDiretorioDestino) {
			$sDestinoFinal = $sDiretorioDestino.$this->getFoto();
			if(is_dir($sDiretorioDestino)) {
				if(copy($sOrigem,$sDestinoFinal)) {
					$vImagem = getimagesize($sDestinoFinal);
					$nLargura = floor($vImagem[0]);
					$nAltura = floor($vImagem[1]);
		
					if($nLargura > $nTamanho || $nAltura > $nTamanho) {
						if($nTamanho != 116){
							if($nLargura > $nAltura) {
								$nMaior = $nLargura;
								$nMenor = $nAltura;
								$nProporcao = 100 - (100 * $nTamanho / $nMaior);
								$nMenor = floor($nMenor - ($nMenor * $nProporcao / 100));
								$vDimensao['largura'] = $nTamanho;
								$vDimensao['altura'] = $nMenor;
							} else {
								$nMaior = $nAltura;
								$nMenor = $nLargura;
								$nProporcao = 100 - (100 * $nTamanho / $nMaior);
								$nMenor = floor($nMenor - ($nMenor * $nProporcao / 100));
								$vDimensao['altura'] = $nTamanho;
								$vDimensao['largura'] = $nMenor;
							}
						}else{
							$nMaior = $nLargura;
							$nMenor = $nAltura;
							$nProporcao = 100 - (100 * $nTamanho / $nMaior);
							$nMenor = floor($nMenor - ($nMenor * $nProporcao / 100));
							$vDimensao['largura'] = $nTamanho;
							$vDimensao['altura'] = $nMenor;
						}
					} else {
						$vDimensao['largura'] = $nLargura;
						$vDimensao['altura'] = $nAltura;
					}//if($nLargura > $nTamanho || $nAltura > $nTamanho) {
					

					// FORMATO DAS FOTOS
					$sNomeArquivo = strtolower($sDestinoFinal);
					$sTipo = substr($sNomeArquivo,strlen($sNomeArquivo)-4,4);

					if($sTipo == ".jpg")
						$iFotoOriginal = imagecreatefromjpeg($sDestinoFinal);
					elseif($sTipo == ".gif")
						$iFotoOriginal = imagecreatefromgif($sDestinoFinal);
						
					$iFotoFinal = imagecreatetruecolor($vDimensao['largura'],$vDimensao['altura']);
					imagecopyresampled($iFotoFinal,$iFotoOriginal,0,0,0,0,$vDimensao['largura'],$vDimensao['altura'],$nLargura,$nAltura);
					
					if($sTipo == ".jpg")
						imagejpeg($iFotoFinal,$sDestinoFinal);
					elseif($sTipo == ".gif")
						imagegif($iFotoFinal,$sDestinoFinal);
					
					$nCount++;
						
					//die();
					if($nCount == count($vsDiretorioDestino)) {
						//unlink($sOrigemFinal);
						$nCount = 0;
					}
				}
			}//if(copy($sOrigemFinal,$sDestinoFinal) && is_readable($sDestinoFinal)) {
		}//foreach($vsDiretorioDestino as $nTamanho => $sDiretorioDestino) {
		
		return false;
	}
	*/
	//FIM MODELO ORIGINAL
}
?>