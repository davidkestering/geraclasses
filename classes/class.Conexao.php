<?php

class Conexao {
	var $pConexao;
	var $pConsulta;
	var $sHost;
	var $sSenha;
	var $sUsuario;
	var $sBanco;
	var $sTipo;

	function __construct($sHost,$sUsuario,$sSenha,$sTipo,$sBanco){
		$this->sHost = $sHost;
		$this->sUsuario = $sUsuario;
		$this->sSenha = $sSenha;
		$this->sBanco = $sBanco;
		$this->sTipo = $sTipo;
		$this->conecta();
	}


	function conecta(){
		switch ($this->sTipo){
			case "MySQL":
				$this->pConexao = mysqli_connect($this->sHost,$this->sUsuario,$this->sSenha) or die("Não foi possível conectar ao banco de dados!");
				echo mysqli_error($this->pConexao);
				$this->escolheBanco();
			break;
		}
	}
	
	function escolheBanco(){
		switch ($this->sTipo){
			case "MySQL":
				if(!$this->pConexao) return false;
				if(!$this->sBanco) return false;

				mysqli_select_db($this->pConexao,$this->sBanco);
			break;
		}
	}
	
	function consulta($sSQL){
		switch ($this->sTipo){
			case "MySQL":
				if(!$this->pConexao) return false;
				if(!$this->sBanco) return false;
				
				$this->pConsulta = mysqli_query($this->pConexao,$sSQL) or die("Não foi possível executar a consulta:".$sSQL);
			break;
		}
	}
	
	
	function pegaConsulta(){
		return $this->pConsulta;
	}
	
	function pegaConexao(){
		return $this->pConexao;
	}
	
	function pegaObjetoResultado(){
		switch ($this->sTipo){
			case "MySQL":
				return mysqli_fetch_object($this->pegaConsulta());
			break;
		}	
	}
	
	function pegaVetorResultado(){
		switch ($this->sTipo){
			case "MySQL":
				return mysqli_fetch_array($this->pegaConsulta());
			break;
		}	
	}
	
	function contaRegistros(){
		switch ($this->sTipo){
			case "MySQL":
				return mysqli_num_rows($this->pegaConsulta());
			break;
		}
	}
	
	function fechaConexao(){
		switch ($this->sTipo){
			case "MySQL":
				return mysqli_close($this->pegaConexao());
			break;
		}
	}
}
?>