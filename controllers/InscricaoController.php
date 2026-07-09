<?php

require_once __DIR__ . '/../models/Inscricao.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Plano.php';
require_once __DIR__ . '/../models/Modalidade.php';
require_once __DIR__ . '/../includes/helpers.php';

class InscricaoController
{
    private $inscricaoModel;
    private $clienteModel;
    private $planoModel;
    private $modalidadeModel;

    public function __construct()
    {
        $this->inscricaoModel = new Inscricao();
        $this->clienteModel   = new Cliente();
        $this->planoModel     = new Plano();
        $this->modalidadeModel = new Modalidade();
    }

    public function listar()
    {
        return $this->inscricaoModel->findAllComDetalhes();
    }

    public function obter($id)
    {
        return $this->inscricaoModel->findById($id);
    }

    /** Listas usadas para preencher os <select> de cliente e plano nas views. */
    public function listarClientes()
    {
        return $this->clienteModel->findAll('nome ASC');
    }

    public function listarPlanos()
    {
        return $this->planoModel->findAll('nome ASC');
    }

    public function listarModalidades()
    {
        return $this->modalidadeModel->findAll('nome ASC');
    }

    public function criar($dados)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['cliente_id']) || empty($dados['plano_id']) || empty($dados['data_inicio'])) {
            set_flash('erro', 'Cliente, plano e data de início são obrigatórios.');
            return false;
        }

        $dados['data_fim'] = $this->calcularDataFim($dados['data_inicio'], $dados['plano_id']);
        $dados['modalidade_id'] = empty($dados['modalidade_id']) ? null : $dados['modalidade_id'];

        $this->inscricaoModel->create($dados);
        set_flash('sucesso', 'Inscrição criada com sucesso.');
        return true;
    }

    public function atualizar($id, $dados)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['cliente_id']) || empty($dados['plano_id']) || empty($dados['data_inicio'])) {
            set_flash('erro', 'Cliente, plano e data de início são obrigatórios.');
            return false;
        }

        $dados['data_fim'] = $this->calcularDataFim($dados['data_inicio'], $dados['plano_id']);
        $dados['modalidade_id'] = empty($dados['modalidade_id']) ? null : $dados['modalidade_id'];

        $this->inscricaoModel->update($id, $dados);
        set_flash('sucesso', 'Inscrição atualizada com sucesso.');
        return true;
    }

    public function eliminar($id)
    {
        $this->inscricaoModel->delete($id);
        set_flash('sucesso', 'Inscrição eliminada com sucesso.');
    }

    public function pesquisarPorData($data)
    {
        return $this->inscricaoModel->searchByData($data);
    }

    /**
     * Regra de negócio: data_fim = data_inicio + duracao_meses do plano escolhido.
     */
    private function calcularDataFim($dataInicio, $planoId)
    {
        $plano = $this->planoModel->findById($planoId);
        $duracaoMeses = $plano ? (int) $plano['duracao_meses'] : 1;

        $data = new DateTime($dataInicio);
        $data->modify("+{$duracaoMeses} months");

        return $data->format('Y-m-d');
    }
}
