<?php
require_once "controladores/controlladorPrincipal.php";
require_once "DAO/UsuarioDAO.php";
require_once "DAO/TransacaoDAO.php";
require_once "util/Messages.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo != "POST") {
    echo '{"erro":"Método não disponível"}';
    exit();
}

if (!array_key_exists('classe', $_GET)) {
    echo '{"erro":"' . Messages::ERROR_MESSAGE_001 . '"}';
    exit;
}

$path = explode('/', $_GET['classe']);

if (count($path) == 0 || $path[0] == "") {
    echo '{"erro":"' . Messages::ERROR_MESSAGE_002 . '"}';
    exit;
}

$parametros = $path;

$classe = $path[0];

unset($parametros[0]);

header('Content-type: application/json');

$body = file_get_contents('php://input');
$dados = json_decode($body, true);


echo redirecionaFuncao($classe, $parametros[1], $dados);

