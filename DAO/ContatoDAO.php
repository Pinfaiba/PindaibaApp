<?php
require_once "../modelos/Usuario.php";
require_once "../util/Funcoes.php";

/**
 * Pega todos os contatos de um usuario
 * @param $usuario int <p>
 * Id do usuario que solicitou os contatos
 * </p>
 * @return Usuario[]|string <p>
 * Retorna um array de usuario ou uma  string informando o porque nao fo possivel retornar os contatos
 * </p>
 */
function getAllContatos($usuario){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql = "CALL get_all_contatos(?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statLogado);

    $statLogado = $usuario;

    $stat->execute();

    $result = $stat->get_result();

    $contatos = $result->fetch_all(MYSQLI_ASSOC);

    $retorno = array();

    for ($i = 0; $i < count($contatos); $i++){
        $retorno[$i] = new Usuario($contatos[$i]["id_usuario"], $contatos[$i]["nome"],
                                    $contatos[$i]["email"], "", $contatos[$i]["url"]);
    }

    if (count($retorno) < 1)
        $retorno = 'nenhum contato encontrar encontrada';

    if (isset($stat->error_list[0]))
        $retorno = "$stat->error";

    return $retorno;
}