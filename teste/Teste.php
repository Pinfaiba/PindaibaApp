<?php
require_once "../modelos/Usuario.php";
require_once "../DAO/UsuarioDAO.php";
require_once "../DAO/NotificacaoDAO.php";
require_once "../DAO/TransacaoDAO.php";
require_once "../DAO/ContatoDAO.php";

header('Content-Type: application/json;charset=utf-8');
$resultado = json_encode("fudeu");

$resultado = getTransacoesAlvo(2, 3);

$json = json_encode($resultado);

$resultado = getJsonNotificacoes(2);

visualizarNotificacao(1);

echo json_encode(getAllContatos(3));

