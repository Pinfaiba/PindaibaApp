<?php
require_once "modelos/Usuario.php";
require_once "util/Funcoes.php";

/**
 * Login do usuario
 * @param string $emailLogar<p>
 * Email do usuario que vai logar
 * </p>
 * @param string $senhaLogar<p>
 * Senha do usuario que vai logar
 * </p>
 * @return Usuario|String <p>
 * Retorna o usuario já logado
 * </p>
 */
function logar($emailLogar, $senhaLogar){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: $conex->connect_error";
    }

    $sql =
        "SELECT id_usuario, nome, email, senha, url_imagem FROM " .
        "  usuarios WHERE " .
        "  email LIKE ?";

    $stat = $conex->prepare($sql);

    $stat->bind_param("s", $statEmail);

    $statEmail = $emailLogar;

    $stat->execute();

    $stat->bind_result($id, $nome, $email, $senha, $urlImg);

    $stat->fetch();

    $retorno = new Usuario($id, $nome, $email, $senha, $urlImg);

    if (isset($stat->error_list[0]))
        $retorno = "$stat->error";

    if ($senha != $senhaLogar)
        $retorno = "senha incorreta";

    if ($id == null)
        $retorno = "Email não encontrado";

    $stat->close();
    $conex->close();

    return $retorno;
}

/**
 * Cadastro de usuario
 * @param string $nomeCadastro <p>
 * Nome do usuario a ser cadastrado
 * </p>
 * @param string $emailCadastro <p>
 * Email a ser cadastrado
 * </p>
 * @param string $senhaCadastro <p>
 * Senha do usuario a ser cadastrado
 * </p>
 * @return Usuario|String<p>
 * Retorna o usuario já logado e cadastrado no sistema ou uma string informando
 * porque nao foi possivel cadastrar
 * </p>
 */
function cadastrar($nomeCadastro, $emailCadastro, $senhaCadastro){
    $conex = conex();
    if ($conex -> connect_errno) {
        return "Failed to connect to MySQL: " . $conex -> connect_error;
    }

    $sql = "SELECT cadastrar(?, ?, ?) as u";

    $stat = $conex->prepare($sql);

    $stat->bind_param("sss", $statNome, $statEmail, $statSenha);

    $statNome = $nomeCadastro;
    $statEmail = $emailCadastro;
    $statSenha = $senhaCadastro;

    $stat->execute();

    $stat->bind_result($realizou);

    $stat->fetch();

    if (!$realizou){
        $stat->close();
        $conex->close();
        return "Este email já existe";
    }

    if (isset($stat->error_list[0])){
        $stat->close();
        $conex->close();
        return "$stat->error";
    }

    return logar($emailCadastro, $senhaCadastro);
}