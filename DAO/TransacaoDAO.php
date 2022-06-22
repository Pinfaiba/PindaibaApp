<?php
require_once "modelos/Transacao.php";
require_once "util/Funcoes.php";

/**
 * Realiza transacao
 * @param $logado int <p>
 * Id do usuario que solicitou a transacao
 * </p>
 * @param $alvo int <p>
 * Id do usuario que vai participar da transacao
 * </p>
 * @param $valor float <p>
 * Valor da transacao
 * </p>
 * @param string $descricao <p>
 * Descricao da transacao. Exemplo: "conta de luz"
 * </p>
 * @return bool|string <p>
 * Retorna true caso tenha realizado a transacao com sucesso,
 * false caso nao tenha realizado e uma string de erro caso ocorra um erro no banco de dados
 * </p>
 */
function realizarTransacao($logado, $alvo, $valor, $descricao = "nenhuma descrição"){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql = "SELECT realizar_transacao(?, ?, ?, ?) AS realizado";

    $stat = $conex->prepare($sql);

    $stat->bind_param("iisd", $statLogado, $statAlvo, $statDescricao, $statValor);

    $statLogado = $logado;
    $statAlvo = $alvo;
    $statDescricao = $descricao;
    $statValor = (double) $valor;

    $stat->execute();

    $stat->bind_result($realizou);

    $stat->fetch();

    if (isset($stat->error_list[0])){
        $stat->close();
        $conex->close();
        return "$stat->error";
    }

    return ((boolean) $realizou);
}

/**
 * Confirma uma transacao usando uma notificacao
 * @param $notificacao int <p>
 * Id da notificacao
 * </p>
 * @return bool|string <p>
 * Retorna true caso tenha confirmado a transacao com sucesso,
 * false caso nao tenha confirmado e uma string de erro caso ocorra um erro no banco de dados
 * </p>
 */
function confirmarTransacaoNotificacao($notificacao){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql = "SELECT confirmar_transacao(?) AS confirmado";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statNotificacao);

    $statNotificacao = $notificacao;

    $stat->execute();

    $stat->bind_result($confirmou);

    $stat->fetch();

    if (isset($stat->error_list[0])){
        $stat->close();
        $conex->close();
        return "$stat->error";
    }

    return ((boolean) $confirmou);
}

/**
 * Confirma transacao usando a propia transacao
 * @param $transacao <p>
 * Id da transacao
 * </p>
 * @return void|string <p>
 * Retorna uma string caso ocorra algum erro informando qual o erro, caso contrario retorna void
 * </p>
 */
function confirmarTransacao($transacao){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql = "UPDATE transacoes SET confirmado = true WHERE id_transacao = ?";

    $stat = $conex->prepare($sql);

    $stat->bind_param("i", $statTransacao);

    $statTransacao = $transacao;

    $stat->execute();

    $stat->close();
    $conex->close();

    if (isset($stat->error_list[0])){
        return "$stat->error";
    }
}

/**
 * Pega as transacoes com outro usuario
 * @param $logado int <p>
 * Id do usuario que solicitou as transacoes
 * </p>
 * @param $alvo int <p>
 * Id do usuario em que o foi solicitados as transacoes
 * </p>
 * @return Transacao[]|string <p>
 * Retorna todas as transacoes feitas entre os dois usuarios ou uma string informando
 * porque não foi possivel pegar as informacoes
 * </p>
 */
function getTransacoesAlvo($logado, $alvo){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql = "call get_transacoes_alvo(?, ?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("ii", $statLogado, $statAlvo);

    $statLogado = $logado;
    $statAlvo = $alvo;

    $stat->execute();

    $result = $stat->get_result();

    $transacoes = $result->fetch_all(MYSQLI_ASSOC);

    $retorno = array();

    for ($i = 0; $i < count($transacoes); $i++) {
        $emissor = new Usuario($transacoes[$i]["id_emissor"], $transacoes[$i]["nome_emissor"], $transacoes[$i]["email_emissor"],
                                $transacoes[$i]["senha_emissor"], $transacoes[$i]["url_img_emissor"]);

        $receptor = new Usuario($transacoes[$i]["id_receptor"], $transacoes[$i]["nome_receptor"], $transacoes[$i]["email_receptor"],
                                $transacoes[$i]["senha_receptor"], $transacoes[$i]["url_img_receptor"]);

        $obj = new Transacao($transacoes[$i]["transacao"], $emissor, $receptor, $transacoes[$i]["descricao"],
                                $transacoes[$i]["valor"], $transacoes[$i]["confirmado"], $transacoes[$i]["data"]);

        $retorno[$i] = $obj;
    }

    if (count($retorno) < 1)
        $retorno = "nenhuma transacao encontrada";

    if (isset($stat->error_list[0]))
        $retorno = "$stat->error";

    return $retorno;
}

/**
 * Pega as ultimas transacoes feitas
 * @param $usuario int <p>
 * Id do usuario que solicitou as ultimas transaçoes
 * </p>
 * @param $quant int <p>
 * Quantidade de transacoes a ser retornada
 * </p>
 * @return Transacao[]|string <p>
 * Retorna um array com todas as ultimas transacoes ou uma string informando
 * o porque não foi possivel pegar esses dados
 * </p>
 */
function getUltimasTransacoes($usuario, $quant){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql = "call get_ultimas_transacoes(?, ?)";

    $stat = $conex->prepare($sql);

    $stat->bind_param("ii", $statUsuario, $statQuant);

    $statUsuario = $usuario;
    $statQuant = $quant;

    $stat->execute();

    $result = $stat->get_result();

    $transacoes = $result->fetch_all(MYSQLI_ASSOC);

    $retorno = array();

    for ($i = 0; $i < count($transacoes); $i++) {
        $emissor = new Usuario($transacoes[$i]["id_emissor"], $transacoes[$i]["nome_emissor"], $transacoes[$i]["email_emissor"],
                                $transacoes[$i]["senha_emissor"], $transacoes[$i]["url_img_emissor"]);

        $receptor = new Usuario($transacoes[$i]["id_receptor"], $transacoes[$i]["nome_receptor"], $transacoes[$i]["email_receptor"],
                                $transacoes[$i]["senha_receptor"], $transacoes[$i]["url_img_receptor"]);

        $obj = new Transacao($transacoes[$i]["transacao"], $emissor, $receptor, $transacoes[$i]["descricao"],
                                $transacoes[$i]["valor"], $transacoes[$i]["confirmado"], $transacoes[$i]["data"]);

        $retorno[$i] = $obj;
    }

    if (count($retorno) < 1)
        $retorno = "nenhuma transacao encontrada";

    if (isset($stat->error_list[0]))
        $retorno = "$stat->error";

    return $retorno;
}
