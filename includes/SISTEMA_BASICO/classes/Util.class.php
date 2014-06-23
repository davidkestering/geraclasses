<?php

class Util {

    function Util() {
        
    }

    static function getMsg($mod) {

        switch ($mod) {
            case "S" :
                $msg = "A operação foi realizada com sucesso.";
                break;
            case "E" :
                $msg = "A operação não foi realizada com sucesso!";
                break;
            case "N" :
                $msg = "Nenhum registro foi encontrado, verifique os dados informados!";
                break;
        }

        return $msg;
    }

    static function getDataAtual() {
        return date("Y-m-d");
    }

    static function getHoraAtual() {
        return date("H:i:s");
    }

    static function converteData($data) {
        //return $this->converteAmdParaDma($data);
        return Util::converteAmdParaDma($data);
    }

    static function converteDataBanco($data) {
        //return $this->converteDmaParaAmd($data);
        return Util::converteDmaParaAmd($data);
    }

    static function converteMdaParaDma(&$data) {
        $data = substr($data, 0, 10);
        list ( $mes, $dia, $ano ) = explode("/", $data);
        $data = $dia . "/" . $mes . "/" . $ano;
        return $data;
    }

    static function converteDmaParaMda(&$data) {
        $data = substr($data, 0, 10);
        list ( $dia, $mes, $ano ) = explode("/", $data);
        $data = $mes . "/" . $dia . "/" . $ano;
        return $data;
    }

    static function converteDmaParaAmd(&$data) {
        $data = substr($data, 0, 10);
        list ( $dia, $mes, $ano ) = explode("/", $data);
        $data = $ano . "-" . $mes . "-" . $dia;
        return $data;
    }

    static function converteAmdParaDma(&$data) {
        $data = substr($data, 0, 10);
        list ( $ano, $mes, $dia ) = explode("-", $data);
        $data = $dia . "/" . $mes . "/" . $ano;
        return $data;
    }

    ## funções de formatação e tratamento de strings

    static function forValorBanco($valor) {
        return str_replace(",", ".", $valor);
    }

    static function forValor($valor) {
        return str_replace(".", ",", $valor);
    }

    static function forStringBanco($str) {
        $str = addslashes($str);
        return $str;
    }

    static function forString($str) {
        $str = stripslashes($str);
        return $str;
    }

    static function encode() {
        $vetParametros = func_get_args ();
        while ($parametro = array_shift($vetParametros)) {
            $vetEncode [] .= urlencode($parametro);
        }
        return implode("|", $vetEncode);
    }

    static function decode($codigo) {
        $vetVarDecode = explode("|", $codigo);
        while ($varDecode = urldecode(array_shift($vetVarDecode))) {
            $vetVar [] = $varDecode;
        }
        return $vetVar;
    }

    static function iterateMenu($vetor, $atributoLabel, $atributoValor = false, $valorPadrao = false) {
        foreach ($vetor as $objeto) {
            $strValor = "\$objeto->get" . (($atributoValor) ? $atributoValor : $atributoLabel) . "()";
            if (is_array($atributoLabel))
                for ($i = 0; $i < count($atributoLabel); $i++)
                    $strLabel .= "\$objeto->get" . $atributoLabel [$i] . "()" . (($i == count($atributoLabel) - 1) ? '' : '." - ".');
            else
                $strLabel = "\$objeto->get" . $atributoLabel . "()";

            eval("\$strValor = $strValor;");
            eval("\$strLabel = $strLabel;");
            print "<option value='" . $strValor . "'" . (($valorPadrao) ? (($strValor == $valorPadrao) ? "selected " : "") : '') . " >" . $strLabel . "</option>\n";
            $strLabel = '';
        }
    }

    static function viewAgregation($pk, $nameObject, $property) {
        if (is_array($pk))
            $pk = implode(",", $pk);
        eval("\$object = Fachada::get$nameObject($pk);");
        if ($object)
            eval("\$valor = \$object->get$property();");
        return $valor;
    }

    static function data_parcelas($dias, $parcelas, $data_inicio) {
        $data = explode("/", trim($data_inicio));

        if ($data[0] > 0 && $data[0] < 32 && $data[1] > 0 && $data[1] < 13) {
            $array = array();
            $array[] = $data_inicio;
            for ($i = 1; $i < $parcelas; $i++) {
                $valor_novo = $dias * ($i);

                if ($valor_novo < 30) {
                    //$array[] = date("Y-m-d", mktime(0, 0, 0, 0, date("d")+$valor_novo, 0));
                    $repeticao = "$valor_novo Day";
                } else {
                    $Month = $valor_novo / 30;
                    $Day = 30 * ($Month - (floor($Month)));
                    //$array[] = date("Y-m-d", mktime(0, 0, 0, date("m")+$Month, date("d")+$Day, 0));
                    $repeticao = (floor($Month)) . " Month" . " $Day Day";
                }

                $array[] = date("d/m/Y", strtotime($repeticao, strtotime(datatousa($data_inicio))));
            }

            return $array;
        } else {
            return false;
        }
    }

    static function convert_porc_to_decimal($PORCENTAGEM, $VALOR_TOTAL) {
        return (($PORCENTAGEM * $VALOR_TOTAL) / 100);
    }

    static function calcula_valor_com_juros($DATA_PAGAMENTO, $DATA_VENCIMENTO, $VALOR, $VALOR_ATRASO, $TIPO_ATRASO, $VALOR_ATRASO_REPETICAO, $TIPO_ATRASO_REPETICAO, $FREQ_ATRASO_REPETICAO) {
        $DIAS_ATRASO = 0;

        if ($DATA_VENCIMENTO) {
            if ($DATA_PAGAMENTO == 0) {
                $DATA_PAGAMENTO = strtotime(date("Y-m-d"));
            }

            $DIAS_ATRASO = (($DATA_PAGAMENTO / (60 * 60 * 24)) - ($DATA_VENCIMENTO / (60 * 60 * 24))) / $FREQ_ATRASO_REPETICAO;

            if ($DIAS_ATRASO > 0) {
                if ($TIPO_ATRASO == 'porcentagem') {
                    $VALOR_ATRASO = Util::convert_porc_to_decimal($VALOR_ATRASO, $VALOR);
                }
                if ($TIPO_ATRASO_REPETICAO == 'porcentagem') {
                    $VALOR_ATRASO_REPETICAO = Util::convert_porc_to_decimal($VALOR_ATRASO_REPETICAO, $VALOR);
                }
                
                return $VALOR + $VALOR_ATRASO + ($VALOR_ATRASO_REPETICAO * $DIAS_ATRASO);
            } else {
                return $VALOR;
            }
        } else {
            return $VALOR;
        }
    }

}
?>