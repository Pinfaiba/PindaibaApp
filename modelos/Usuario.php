<?php
require_once "JsonCompativel.php";

class Usuario implements JsonSerializable, JsonCompativel {
    /**
     * @var int|null
     */
    private $idUsuario;
    /**
     * @var string|null
     */
    private $nome;
    /**
     * @var string|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $senha;
    /**
     * @var string|null
     */
    private $urlImg;

    /**
     * Usuario constructor.
     * @param $idUsuario int
     * @param $nome string
     * @param $email string
     * @param $senha string
     * @param $urlImg string
     */
    public function __construct($idUsuario = null, $nome = null, $email = null, $senha = null, $urlImg = null)
    {
        $this->idUsuario = $idUsuario;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->urlImg = $urlImg;
    }

    public function getJson(){
        return
            "{" .
            "  \"idUsuario\" : \"$this->idUsuario\", " .
            "  \"nome\" : \"$this->nome\", " .
            "  \"email\" : \"$this->email\", " .
            "  \"urlImg\" : \"$this->urlImg\" " .
            "}";
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'idUsuario' => $this->getIdUsuario(),
            'nome' => $this->getNome(),
            'email' => $this->getEmail(),
            'urlImg' => $this->getUrlImg()
        ];
    }

    /**
     * @return int|null
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param int|null $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * @return string|null
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string|null $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param string|null $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return string|null
     */
    public function getUrlImg()
    {
        return $this->urlImg;
    }

    /**
     * @param string|null $urlImg
     */
    public function setUrlImg($urlImg)
    {
        $this->urlImg = $urlImg;
    }
}