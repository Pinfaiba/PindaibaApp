<?php
/**
 * Realiza quebra de linha
 * @return string<p>
 * retorna uma quebra de linha
 * </p>
 */
function br(){
    return "<br/>";
}

/**
 * Realiza conexao
 * @return mysqli<p>
 * retorna uma conexao ja feita
 * </p>
 */
function conex()
{
    $servername = "localhost";
    $nomeUsuario = "root";
    $senha = "";
    $baseDados = 'pindaiba';

    return new mysqli($servername, $nomeUsuario, $senha, $baseDados);
}

/**
 * Verifica se é um json
 * @param String $json<p>
 * Obejeto que vai ser verificado se é uma string
 * </p>
 * @return bool<p>
 * retorna true caso seja json caso contrario retorna false
 * </p>
 */
function isJson($json){
    json_decode($json);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Converte para json
 * @param $dado mixed <p>
 * Dado que deve ser convertido
 * </p>
 * @return string <p>
 * Retorna o dado em formato json
 * </p>
 */
function converteJson($dado) {
    if (is_array($dado)) {
        if (isSequencial($dado)){
            $retorno = "[";
            for ($i = 0; $i < count($dado); $i++){
                if ($i != 0)
                    $retorno .= ", ";
                $retorno .= converteJson($dado[$i]);
            }
            return $retorno . "]";
        }else{
            $chaves = array_keys($dado);
            $retorno = "{";
            for ($i = 0; $i < count($chaves); $i++){
                if ($i != 0)
                    $retorno .= ", ";
                $retorno .= "\"$chaves[$i]\" : " . converteJson($dado[$chaves[$i]]);
            }
            return $retorno . "}";
        }
    } elseif (implementaInterface($dado)){
        return $dado->getJson();
    }
    return json_encode($dado);
}

/**
 * Verifica se é um array sequencial
 * @param $array array <p>
 * Array que vai ser verificado
 * </p>
 * @return bool <p>
 * Retorta true caso seja um array sequencial e false caso seja um associativo
 * </p>
 */
function isSequencial($array){
    return strpos(json_encode($array), "[") === 0;
}

/**
 * Verifica se a interface foi implementada
 * @param mixed $obj <p>
 * Objeto que vai ser verificado
 * </p>
 * @param string $interface <p>
 * Nome da interface
 * </p>
 * @return boolean <p>
 * Retorna true caso o objeto implementa a interface e retorna false caso o contrario
 * </p>
 */
function implementaInterface($obj, $interface = "JsonCompativel"){
    $retorno = false;
    $tipos = array_keys(class_implements($obj));

    for ($i = 0; $i < count($tipos) && !$retorno; $i++){
        if ($tipos[$i] == $interface)
            $retorno = true;
    }

    return $retorno;
}
