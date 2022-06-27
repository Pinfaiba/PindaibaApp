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
function redirecionaFuncao($classe, $acao, $dados){
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
            default:
                return '{"erro":"Erro n° 004, entre em contato com os desenvolvedores!"}';
        }
    }
}