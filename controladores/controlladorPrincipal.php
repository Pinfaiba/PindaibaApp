<?php

/**
 * Redireciona para a função que o usuario requisitou
 * @param $classe string <p>
 * Tipo de ação que foi solicitada pelo usuario
 * </p>
 * @param  $acao string <p>
 * ação que foi solicitada pelo usuario
 * </p>
 * @param $dados array <p>
 * Array associativo com os dados para a ação
 * </p>
 * @return string <p>
 * Json que será enviada para aplicação
 * </p>
 */
function redirecionaFuncao($classe, $acao, $dados)
{
    if ($classe == "usuario") {
        switch ($acao) {
            case "cadastrar":
                $usuario = cadastrar($dados["nome"], $dados["email"], sha1($dados["senha"]));

                if (is_string($usuario))
                    return '{"status" : "' . $usuario . '"}';

                return json_encode($usuario);
            case "logar":
                $usuario = logar($dados["email"], sha1($dados["senha"]) );

                if (is_string($usuario))
                    return '{"status" : "' . $usuario . '"}';

                return json_encode($usuario);
            default:
                return '{"erro":"' . Messages::ERROR_MESSAGE_003 . '"}';
        }
    } elseif ($classe == "transacao") {
        switch ($acao) {
            case "realizar":
                if (isset($dados["descricao"])) {
                    $realizada = realizarTransacao($dados["id"], $dados["alvo"], $dados["valor"], $dados["descricao"]);
                } else {
                    $realizada = realizarTransacao($dados["id"], $dados["alvo"], $dados["valor"]);
                }

                if (is_string($realizada))
                    return '{"status" : "' . $realizada . '"}';

                if ($realizada)
                    return '{"status":"não foi possivel realizar essa operação"}';
                else
                    return '{"status":"erro"}';

            case "confirma":
                if (isset($dados["transacao"]))
                    $erro = confirmarTransacao($dados["transacao"]);
                else
                    $erro = confirmarTransacaoNotificacao($dados["transacao"]);

                if ($erro)
                    return '{"status":"não foi possivel realizar a tranzação, entre em cotato com os desenvolvedores"}';
                else
                    return '{"status" : "transação realizada com sucesso"}';
            case "pegar":
                if (isset($dados["alvo"]))
                    $transacoes = getTransacoesAlvo($dados["logado"], $dados["alvo"]);
                else
                    $transacoes = getUltimasTransacoes($dados["logado"], $dados["quant"]);

                if (is_array($transacoes))
                    return json_encode($transacoes);
                else
                    return '{"status" : "' . $transacoes . '"}';
            default:
                return '{"erro":"' . Messages::ERROR_MESSAGE_003 . '"}';
        }
    } elseif ($classe == "notificacao") {
        switch ($acao) {
            case "visualizar":
                $visualizado = visualizarNotificacao($dados["notificacao"]);

                if (!isset($visualizado))
                    return '{"status" : "sucesso"}';
                else
                    return '{"status" : "' . $visualizado . '"}';

            case "pegarTodas":
                $notificacoes = getJsonNotificacoes($dados["logado"]);

                return $notificacoes;
            case "pegarNaoVisualizadas":
                $notificacoes = getJsonNaoVisualizadas($dados["logado"]);

                return  $notificacoes;
            default:
                return '{"erro":"' . Messages::ERROR_MESSAGE_003 . '"}';
        }
    } elseif ($classe == "contato"){
        switch ($acao) {
            case "pegar":
                $contatos = getAllContatos($dados["logado"]);

                if (is_array($contatos))
                    return json_encode($contatos);
                else
                    return '{"status":"' . $contatos . '"}';
            default:
                return '{"erro":"' . Messages::ERROR_MESSAGE_003 . '"}';
        }
    } else {
        return '{"erro":"' . Messages::ERROR_MESSAGE_004 . '"}';
    }
}