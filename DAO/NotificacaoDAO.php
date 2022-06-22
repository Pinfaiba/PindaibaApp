<?php
require_once "../modelos/Notificacao.php";
require_once "../util/Funcoes.php";

function visualizarNotificacao($notificacao){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql =
        "UPDATE notificacoes SET visualisado = true WHERE id_notificacao = ?";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statNotificacao);

    $statNotificacao = $notificacao;

    $stat->execute();

    $stat->close();
    $conex->close();

    if (isset($stat->error_list[0])){
        return "$stat->error";
    }
}

/**
 * Pega todas as notificacoes do usuario e armazena em um json
 * @param int $idUsuario <p>
 * id do usuario que solicitou as notifuicacoes
 * </p>
 * @return string <p>
 * Retorna um json com todas as notificacoes ou um json afirmando qual o tipo de erro
 * </p>
 */
function getJsonNotificacoes($idUsuario){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "{\"erro\" : \"Failed to connect to MySQL: $conex->connect_error\"}";
    }

    $sql = "call get_all_notificacoes(?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statUsuario);

    $statUsuario = $idUsuario;

    $stat->execute();

    $result = $stat->get_result();

    $retorno = "{\"erro\" : \"nenhuma notificação encontrada\"}";

    $notificacoes = $result->fetch_all(MYSQLI_ASSOC);

    if (count($notificacoes) > 0)
        $retorno = json_encode($notificacoes);

    if (isset($stat->error_list[0]))
        $retorno = "{\"erro\" : \"$stat->error\"}";

    $stat->close();
    $conex->close();

    return $retorno;
}

/**
 * Pega todas as notificacoes que nao foram visualizadas
 * @param $idUsuario int <p>
 * Id do usuario que solicitou as notificacoes
 * </p>
 * @return string <p>
 * </p>
 */
function getJsonNaoVisualizadas($idUsuario){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "{\"erro\" : \"Failed to connect to MySQL: $conex->connect_error\"}";
    }

    $sql = "call get_notificacoes_nao_visualizadas(?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statUsuario);

    $statUsuario = $idUsuario;

    $stat->execute();

    $result = $stat->get_result();

    $retorno = "{\"erro\" : \"nenhuma notificação encontrada\"}";

    $notificacoes = $result->fetch_all(MYSQLI_ASSOC);

    if (count($notificacoes) > 0)
        $retorno = json_encode($notificacoes);

    if (isset($stat->error_list[0]))
        $retorno = "{\"erro\" : \"$stat->error\"}";

    $stat->close();
    $conex->close();

    return $retorno;
}