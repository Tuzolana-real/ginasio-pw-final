<?php

require_once __DIR__ . '/../models/Frequencia.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Modalidade.php';
require_once __DIR__ . '/../includes/helpers.php';

class FrequenciaController
{
    private $frequenciaModel;
    private $clienteModel;
    private $modalidadeModel;

    public function __construct()
    {
        $this->frequenciaModel = new Frequencia();
        $this->clienteModel = new Cliente();
        $this->modalidadeModel = new Modalidade();
    }

    public function listar()
    {
        return $this->frequenciaModel->findAllComDetalhes();
    }

    public function listarClientes()
    {
        return $this->clienteModel->findAll('nome ASC');
    }

    public function listarModalidades()
    {
        return $this->modalidadeModel->findAll('nome ASC');
    }

    public function registrarEntrada($dados)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['cliente_id'])) {
            set_flash('erro', 'Cliente e obrigatorio.');
            return false;
        }

        $entradaAberta = $this->frequenciaModel->findEntradaAberta($dados['cliente_id']);
        if ($entradaAberta) {
            set_flash('erro', 'Este cliente ja tem uma entrada aberta.');
            return false;
        }

        $dados['modalidade_id'] = empty($dados['modalidade_id']) ? null : $dados['modalidade_id'];
        $this->frequenciaModel->create($dados);
        set_flash('sucesso', 'Entrada registada com sucesso.');
        return true;
    }

    public function registrarSaida($id)
    {
        $this->frequenciaModel->registrarSaida($id);
        set_flash('sucesso', 'Saida registada com sucesso.');
    }
}
