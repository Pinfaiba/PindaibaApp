<?php
require_once "DAO/UsuarioDAO.php";
require_once "DAO/TransacaoDAO.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo != "POST") {
    echo '{"erro":"Método não disponível"}';
    exit();
}

if (!array_key_exists('classe', $_GET)) {
    echo '{"erro":"Erro n° 001, entre em contato com os desenvolvedores!"}';
    exit;
}

$path = explode('/', $_GET['classe']);

if (count($path) == 0 || $path[0] == "") {
    echo '{"erro":"Erro n° 002, entre em contato com os desenvolvedores!"}';
    exit;
}

$parametros = $path;
$classe = $path[0];

unset($parametros[0]);

header('Content-type: application/json');

$body = file_get_contents('php://input');
$dados = json_decode($body, true);

if ($classe == "usuario") {
    switch ($parametros[1]) {
        case "cadastrar":
            $usuario = cadastrar($dados["nome"], $dados["email"], $dados["senha"]);
            echo json_encode($usuario);
            exit();
        case "logar":
            $usuario = logar($dados["email"], $dados["senha"]);
            echo json_encode($usuario);
            exit();
        default:
            echo '{"erro":"Erro n° 003, entre em contato com os desenvolvedores!"}';
            exit();
    }
} elseif ($classe == "transacao") {
    switch ($parametros[1]) {
        case "realizar":
            if (isset($dados["descricao"])) {
                $realizada = realizarTransacao($dados["id"], $dados["alvo"], $dados["valor"], $dados["descricao"]);
            } else {
                $realizada = realizarTransacao($dados["id"], $dados["alvo"], $dados["valor"]);
            }

            if ($realizada) {
                echo '{"status":"sucesso"}';
            } else {
                echo '{"status":"erro"}';
            }
            exit();
        default:
            echo '{"erro":"Erro n° 004, entre em contato com os desenvolvedores!"}';
            exit();
    }
}

//require_once "teste/Teste.php";