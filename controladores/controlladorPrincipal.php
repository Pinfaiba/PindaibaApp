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
                $usuario = cadastrar($dados["nome"], $dados["email"], $dados["senha"]);
                return json_encode($usuario);
            case "logar":
                $usuario = logar($dados["email"], $dados["senha"]);
                return json_encode($usuario);
            default:
                return '{"erro":"Erro n° 003, entre em contato com os desenvolvedores!"}';
        }
    } elseif ($classe == "transacao") {
        switch ($acao) {
            case "realizar":
                if (isset($dados["descricao"])) {
                    $realizada = realizarTransacao($dados["id"], $dados["alvo"], $dados["valor"], $dados["descricao"]);
                } else {
                    $realizada = realizarTransacao($dados["id"], $dados["alvo"], $dados["valor"]);
                }

                if ($realizada) {
                    return '{"status":"sucesso"}';
                } else {
                    return '{"status":"erro"}';
                }

            case "confirma":
                if (isset($dados["transacao"]))
                    $erro = confirmarTransacao($dados["transacao"]);
                else
                    $erro = confirmarTransacaoNotificacao($dados["transacao"]);

                if ($erro)
                    return '{"status":"erro"}';
                else
                    return '{"status" : "sucesso"}';
            case "pegar":
                if (isset($dados["alvo"]))
                    $transacoes = getTransacoesAlvo($dados["logado"], $dados["alvo"]);
                else
                    $transacoes = getUltimasTransacoes($dados["logado"], $dados["quant"]);

                if (is_array($transacoes))
                    return json_encode($transacoes);
                else
                    return '{"status":"erro"}';
            default:
                return '{"erro":"Erro n° 004, entre em contato com os desenvolvedores!"}';
        }
    } elseif ($classe == "notificacao") {
        switch ($acao) {
            case "visualizar":
                $visualizado = visualizarNotificacao($dados["notificacao"]);

                if (!isset($visualizado))
                    return '{"status" : "sucesso"}';
                else
                    return '{"status":"erro"}';
            case "pegarTodas":
                $notificacoes = getJsonNotificacoes($dados["logado"]);

                return $notificacoes;
            case "pegarNaoVisualizadas":
                $notificacoes = getJsonNaoVisualizadas($dados["logado"]);

                return  $notificacoes;
            default:
                return '{"erro":"Erro n° 004, entre em contato com os desenvolvedores!"}';
        }
    } elseif ($classe == "contato"){
        switch ($acao) {
            case "pegar":
                $contatos = getAllContatos($dados["logado"]);

                if (is_array($contatos))
                    return json_encode($contatos);
                else
                    return '{"status":"erro"}';
            default:
                return '{"erro":"Erro n° 004, entre em contato com os desenvolvedores!"}';
        }
    } else {
        return '{"erro":"Erro n° 004, entre em contato com os desenvolvedores!"}';
    }
}