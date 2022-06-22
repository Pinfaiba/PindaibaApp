<?php
require_once "JsonCompativel.php";

class Notificacao implements JsonSerializable, JsonCompativel {
    /**
     * @var int
     */
    private $idNotificacao;
    /**
     * @var Usuario
     */
    private $emissor;
    /**
     * @var Usuario
     */
    private $receptor;
    /**
     * @var string
     */
    private $titulo;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $data;
    /**
     * @var boolean
     */
    private $visualizado;

    /**
     * Notificacao constructor.
     * @param int $idNotificacao
     * @param Usuario $emissor
     * @param Usuario $receptor
     * @param string $titulo
     * @param string $descricao
     * @param string $data
     * @param bool $visualizado
     */
    public function __construct($idNotificacao = null, Usuario $emissor = null,
                                Usuario $receptor = null, $titulo = null, $descricao = null,
                                $data = null, $visualizado = null)
    {
        $this->idNotificacao = $idNotificacao;
        $this->emissor = $emissor;
        $this->receptor = $receptor;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->visualizado = $visualizado;
    }

    /**
     * Pega o json de Notificacao
     * @return string <p>
     * Retorna um json com os dados da tranferencia
     * </p>
     */
    public function getJson()
    {
        return "{" .
            "\"idNotificacao\" : \"$this->idNotificacao\", " .
            "\"emissor\" : " . $this->emissor->getJson() . ", " .
            "\"receptor\" : " . $this->receptor->getJson() . ", " .
            "\"titulo\" : \"$this->titulo\", " .
            "\"descricao\" : \"$this->descricao\", " .
            "\"data\" : \"$this->data\", " .
            "\"visualizado\" : \"$this->visualizado\"" .
            "}";
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
          "idNotificacao" => $this->getIdNotificacao(),
          "emissor" => $this->getEmissor(),
          "receptor" => $this->getReceptor(),
          "titulo" => $this->getTitulo(),
          "descricao" => $this->getDescricao(),
          "data" =>$this->getData(),
          "visualizado" => $this->isVisualizado()
        ];
    }

    /**
     * @return int
     */
    public function getIdNotificacao()
    {
        return $this->idNotificacao;
    }

    /**
     * @param int $idNotificacao
     */
    public function setIdNotificacao($idNotificacao)
    {
        $this->idNotificacao = $idNotificacao;
    }

    /**
     * @return Usuario
     */
    public function getEmissor()
    {
        return $this->emissor;
    }

    /**
     * @param Usuario $emissor
     */
    public function setEmissor($emissor)
    {
        $this->emissor = $emissor;
    }

    /**
     * @return Usuario
     */
    public function getReceptor()
    {
        return $this->receptor;
    }

    /**
     * @param Usuario $receptor
     */
    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
    }

    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isVisualizado()
    {
        return $this->visualizado;
    }

    /**
     * @param bool $visualizado
     */
    public function setVisualizado($visualizado)
    {
        $this->visualizado = $visualizado;
    }
}