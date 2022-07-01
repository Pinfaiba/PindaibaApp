<?php
require_once "../modelos/Notificacao.php";
require_once "../util/Funcoes.php";

/**
 * @param $notificacao <p>
 * Id da notificação que será visualizada
 * </p>
 * @return string|void <p>
 * Em caso de erro retorna uma string informando qual o erro caso contrario retorna void
 * </p>
 */
function visualizarNotificacao($notificacao){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "erro ao conectar com o banco de dados";
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
        return "erro ao comunicar com o banco de dados";
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
        return "{\"status\" : \"erro ao conectar com o banco de dados\"}";
    }

    $sql = "call get_all_notificacoes(?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statUsuario);

    $statUsuario = $idUsuario;

    $stat->execute();

    $result = $stat->get_result();

    $retorno = "{\"status\" : \"nenhuma notificação encontrada\"}";

    $notificacoes = $result->fetch_all(MYSQLI_ASSOC);

    if (count($notificacoes) > 0)
        $retorno = json_encode($notificacoes);

    if (isset($stat->error_list[0]))
        $retorno = "{\"status\" : \"erro ao comunicar com o banco de dados\"}";

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
 * Retorna um json com as notificacoes não visualizadas ou um json afirmando qual o tipo de erro
 * </p>
 */
function getJsonNaoVisualizadas($idUsuario){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "{\"status\" : \"" . Messages::ERROR_MESSAGE_005 . "\"}";
    }

    $sql = "call get_notificacoes_nao_visualizadas(?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statUsuario);

    $statUsuario = $idUsuario;

    $stat->execute();

    $result = $stat->get_result();

    $retorno = "{\"status\" : \"nenhuma notificação encontrada\"}";

    $notificacoes = $result->fetch_all(MYSQLI_ASSOC);

    if (count($notificacoes) > 0)
        $retorno = json_encode($notificacoes);

    if (isset($stat->error_list[0]))
        $retorno = "{\"status\" : \"" . Messages::ERROR_MESSAGE_006 . "\"}";

    $stat->close();
    $conex->close();

    return $retorno;
}