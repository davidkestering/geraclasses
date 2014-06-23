<?php
//á
require_once("Transacao.class.php");
/**
* Classe responsável pelas interações com o banco de dados da entidade Transacao
*/
class TransacaoBDParent {

var $oConexao;

	/**
	* Método responsável por construir Transacao
	* @param $oConexao Conexão com o banco de dados. 
	* @access public
	*/
	/*
	function TransacaoBD($sBanco){
		$this->oConexao = new Conexao($sBanco);
	}
	*/
	
	
	/**
	* Método responsável por atribuir valor ao atributo $oConexao
	* @param object $oConexao Conexão com o banco de dados.
	* @access public	
	*/
	function setConexao($oConexao){
		$this->oConexao = $oConexao;
	}
	
	
	/**
	* Método responsável recuperar o atributo $oConexao
	* @access public	
	* @return object $oConexao Conexão com o banco de dados.
	*/	
	function getConexao(){
		return $this->oConexao;
	}
	
	
	/**
	* Método responsável por recuperar Transacao
	* @param $nId nId
	
	* @access public
	* @return object Transacao
	*/
	function recupera($nId) {
		$oConexao = $this->getConexao();
		$sSql = "select *						 
				 from   seg_transacao
		  		 where  id = '$nId'";
		$oConexao->execute($sSql);
		$oReg = $oConexao->fetchObject();
		if ($oReg) {
			$oTransacao = new Transacao($oReg->id,$oReg->id_tipo_transacao,$oReg->id_usuario,utf8_encode($oConexao->unescapeString($oReg->objeto)),utf8_encode($oConexao->unescapeString($oReg->ip)),$oReg->dt_transacao,$oReg->publicado,$oReg->ativo);
			return $oTransacao;
		}
		return false;
	}


	/**
	* Método responsável por verificar presença de Transacao
	* @param $nId nId
	
	* @access public
	* @return boolean Indicando presença ou ausência de Transacao
	*/
	function presente($nId){

		$oConexao = $this->getConexao();
		$sSql = "select id
				 from   seg_transacao
				 where  id = '$nId'
				";
		$oConexao->execute($sSql);
		if ($oConexao->getConsulta())
			return ($oConexao->recordCount() > 0);		
		return 0;
		
	}


	/**
	* Método responsável por inserir Transacao
	* @param object $oTransacao Objeto a ser inserido.
	* @access public
	* @return boolean Indicando sucesso ou não da operação;
	*/
	function insere($oTransacao) {
		$oConexao = $this->getConexao();
		$sSql = "insert into seg_transacao (id_tipo_transacao,id_usuario,objeto,ip,dt_transacao,publicado,ativo) 
				 values ('".$oTransacao->getIdTipoTransacao()."','".$oTransacao->getIdUsuario()."','".utf8_decode($oConexao->escapeString($oTransacao->getObjeto()))."','".utf8_decode($oConexao->escapeString($oTransacao->getIp()))."','".$oTransacao->getDtTransacao()."','".$oTransacao->getPublicado()."','".$oTransacao->getAtivo()."')";		
		$oConexao->execute($sSql);		
		$nId = $oConexao->getLastId();
		if ($nId)
			return $nId;
		return $oConexao->getConsulta();
	}


	/**
	* Método responsável por alterar Transacao
	* @param object $oTransacao Objeto a ser alterado.
	* @access public
	* @return boolean Indicando presença ou ausência de Transacao
	*/
	function altera($oTransacao) {
		$oConexao = $this->getConexao();
		$sSql = "update seg_transacao 
				 set    id_tipo_transacao = '".$oTransacao->getIdTipoTransacao()."',
						id_usuario = '".$oTransacao->getIdUsuario()."',
						objeto = '".utf8_decode($oConexao->escapeString($oTransacao->getObjeto()))."',
						ip = '".utf8_decode($oConexao->escapeString($oTransacao->getIp()))."',
						dt_transacao = '".$oTransacao->getDtTransacao()."',
						publicado = '".$oTransacao->getPublicado()."',
						ativo = '".$oTransacao->getAtivo()."'
				 where  id = '".$oTransacao->getId()."' ";	
		$oConexao->execute($sSql);
		return $oConexao->getConsulta();
	}


	/**
	* Método responsável por recuperar todos os representantes da entidade Transacao
	* @access public
	* @return array $voObjeto Vetor de objetos com os representantes de Transacao
	*/
	function recuperaTodos($vWhere,$sOrder) {
		$oConexao = $this->getConexao();
		
		
		if (count($vWhere) > 0) {
			$sSql2 = "";
			foreach ($vWhere as $sWhere) {
				if($sWhere != "")
					$sSql2 .= $sWhere . " AND ";
			}
			if($sSql2 != ""){
				$sSql = "SELECT * 
				 FROM seg_transacao
				 WHERE
				 ";
				 $sSql = substr($sSql.$sSql2,0,-5);
			}else{
				$sSql = "SELECT * 
				 FROM seg_transacao ";
			}
		}
		else {
			$sSql = "SELECT * 
				 FROM seg_transacao ";
		}

		if ($sOrder) {
			$sSql .= " ORDER BY ".$sOrder;
		}

		$oConexao->execute($sSql);
		$voObjeto = array();
		while ($oReg = $oConexao->fetchObject()) {
			$oTransacao = new Transacao($oReg->id,$oReg->id_tipo_transacao,$oReg->id_usuario,utf8_encode($oConexao->unescapeString($oReg->objeto)),utf8_encode($oConexao->unescapeString($oReg->ip)),$oReg->dt_transacao,$oReg->publicado,$oReg->ativo);
			$voObjeto[] = $oTransacao;
			unset($oTransacao);
		}
		return $voObjeto;
	}


	/**
	* Método responsável por excluir Transacao
	* @access public
	* @return boolean Indicando sucesso ou não da operação
	*/
	function exclui($nId) {
		$oConexao = $this->getConexao();
		$sSql = "delete from seg_transacao
				 where  id = '$nId' ";					
		$oConexao->execute($sSql);
		return $oConexao->getConsulta();
	}


	/**
	* Método responsável por desativar um registro da Transacao
	* @access public
	* @return boolean Indicando sucesso ou não da operação
	*/
	function desativa($nId) {
		$oConexao = $this->getConexao();
		$sSql = "update seg_transacao
		 		 set ativo = '0'
				 where  id = '$nId' ";					
		$oConexao->execute($sSql);
		return $oConexao->getConsulta();
	}

}
?>