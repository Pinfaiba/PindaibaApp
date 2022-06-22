<?php
require_once "JsonCompativel.php";

class Transacao implements JsonSerializable, JsonCompativel {
    /**
     * @var int
     */
    private $idTransacao;
    /**
     * @var Usuario
     */
    private $emissor;
    /**
     * @var Usuario
     */
    private $receptor;
    /**
     * @var String
     */
    private $descricao;
    /**
     * @var float
     */
    private $valor;
    /**
     * @var boolean
     */
    private $confirmado;
    /**
     * @var string
     */
    private $data;

    /**
     * Transacao constructor.
     * @param int $idTransacao
     * @param Usuario $emissor
     * @param Usuario $receptor
     * @param String $descricao
     * @param float $valor
     * @param bool $confirmado
     * @param string $data
     */
    public function __construct($idTransacao = null, Usuario $emissor = null, Usuario $receptor = null, $descricao = null,
                                $valor = null, $confirmado = null, $data = null)
    {
        $this->idTransacao = $idTransacao;
        $this->emissor = $emissor;
        $this->receptor = $receptor;
        $this->descricao = $descricao;
        $this->valor = $valor;
        $this->confirmado = $confirmado;
        $this->data = $data;
    }

    /**
     * Pega o json da transferencia
     * @return string <p>
     * Retorna um json com os dados da transferencia
     * </p>
     */
    public function getJson(){
        return "{ " .
            "  \"idTransacao\" : \"$this->idTransacao\", " .
            "  \"emissor\" : " . $this->emissor->getJson() . ", " .
            "  \"receptor\" : " . $this->receptor->getJson() . ", " .
            "  \"descricao\" : \"$this->descricao\", " .
            "  \"valor\" : \"$this->valor\", " .
            "  \"confirmado\" : \"$this->confirmado\", " .
            "  \"data\" : \"$this->data\" " .
            "}";
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'idTransacao' => $this->getIdTransacao(),
            'emissor' => $this->emissor->jsonSerialize(),
            'receptor' => $this->receptor->jsonSerialize(),
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'confirmado' => $this->confirmado,
            'data' => $this->data
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{ " .
            "  'idTransacao' : '$this->idTransacao', " .
            "  'emissor' : " . json_encode($this->emissor) . ", " .
            "  'receptor' : " . json_encode($this->receptor) . ", " .
            "  'descricao' : '$this->descricao', " .
            "  'valor' : '$this->valor', " .
            "  'confirmado' : '$this->confirmado', " .
            "  'data' : '$this->data' " .
            "}";
    }

    /**
     * @return int
     */
    public function getIdTransacao()
    {
        return $this->idTransacao;
    }

    /**
     * @param int $idTransacao
     */
    public function setIdTransacao($idTransacao)
    {
        $this->idTransacao = $idTransacao;
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
     * @return String
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param String $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return bool
     */
    public function isConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * @param bool $confirmado
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;
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
}