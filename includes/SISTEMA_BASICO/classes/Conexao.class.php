<?php
/**
*CLASSE DE CONEXAO COM BANCO DE DADOS. 
*/

class Conexao {

	/**
	* Ponteiro para a conexão com o banco de dados.
	* @access public
	*/
	var $pConexao;  # PRIVATE #
	/**
	* Ponteiro para o resultado de uma consulta ao banco.
	* @access public
	*/
	var $pConsulta; # PRIVATE #
	/**
	* Ponteiro para o banco de dados.
	* @access public
	*/
	var $pBanco;    # PRIVATE #
	/**
	* Mensagem de erro.
	* @access public
	*/
	var $sErro;     # PRIVATE #
	var $sServidor;
	/**
	* Construtor
	* @param string $sServidor Nome do banco de dados a ser conectado
	* @access public
	*/
	function Conexao($sServidor = 'BANCO') {
		$this->setServidor($sServidor);
		switch ($sServidor) {
			case 'LOCAL':
				$this->conectaBD("localhost","root","root","helloidioms");
			break;
			case 'BANCO':
				$this->conectaBD("localhost","root","root","helloidioms");
			break;
			default:
				die("Não foi possivel conectar ao servidor: $sServidor");
			break;
		}
	}
	
	/**
	* Conecta ao banco de dados especificado.
	* @param string $sHost Host a ser conectado.
	* @param string $sUser Usuário para a conexão.
	* @param string $sSenha Senha para a conexão com o banco de dados.
	* @param string $sBanco Banco de dados.
	* @access private
	*/
	function conectaBD($sHost,$sUser,$sSenha,$sBanco) {
		$this->pConexao = mysql_connect($sHost,$sUser,$sSenha) or die("Erro de conexao");
		mysql_select_db($sBanco,$this->pConexao);
	}


	// ************************** 
	/**
	* Executa uma consulta no banco de dados.
	* @param string $sSql SQL a ser executado no banco de dados.
	* @access public
	*/
	function execute($sSql) {
		//print($sSql . "<br>");
		$this->pConsulta = mysql_query($sSql,$this->getConexao());
		if (!$this->getConsulta()){
			$this->sErro = "Ocorreu o seguinte erro na consulta:".mysql_error();
		}
	}
	
	/**
	* Conta o número de registros resultantes de uma consulta.
	* @return integer Número de registros resultantes de uma consulta.
	* @access public
	*/
	function recordCount() {
		return (int) mysql_num_rows($this->getConsulta());
	}
	
	/**
	* Atribui a um objeto o resultado de uma consulta ao banco.
	* @return object Objeto contendo o resultado de uma consulta como atributos.
	* @access public
	*/
	function fetchObject() {
		return mysql_fetch_object($this->getConsulta());
	}

	// **************************
	
	/**
	* Fecha a conexão com o banco de dados.
	* @access public
	*/
	function close() {
		mysql_close($this->getConexao());
	}
	
	// **************************
	
	/**
	* Recupera o ponteiro de conexão com o BD.
	* @return array $this->pConexao Ponteiro para a conexao ao banco de dados.
	* @access public
	*/
	function getConexao() {
		return $this->pConexao;
	}
	
	/**
	* Recupera o ponteiro para uma consulta realizada no banco de dados.
	* @return array $this->pConexao Ponteiro para a conexao ao banco de dados.
	* @access public
	*/
	function getConsulta() {
		return $this->pConsulta;
	}
	
	/** 
	* Recupera mensagem de erro.
	* @return string $this->sErro Mensagem de erro após uma determinada operação.
	* @access public
	*/
	function getErro() {
		return $this->sErro;
	}
	
	function setServidor($sServidor){
		$this->sServidor = $sServidor;
	}
	
	function getServidor(){
		return $this->sServidor;
	}
	
	function escapeString($sAtributo) {
		return mysql_escape_string($sAtributo);
	}

	function unescapeString($sEscapedString){
		if (preg_match("/.*\\'.*/",$sEscapedString)){
			$sEscapedString = str_replace("\'","'",$sEscapedString);
		} 	
		if (preg_match('/.*\\".*/',$sEscapedString)){
			$sEscapedString = str_replace('\"','"',$sEscapedString);
		}
		return $sEscapedString;	
	}
	

	/**
	* Retorna o último ID inserido no MySQL.
	* @return integer Último ID auto-increment inserido no banco de dados.
	* @access public
	*/
	function getLastId(){
		return mysql_insert_id();
	}
}
?>