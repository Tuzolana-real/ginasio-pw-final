<?php

require_once __DIR__ . '/../models/Pagamento.php';
require_once __DIR__ . '/../models/Inscricao.php';
require_once __DIR__ . '/../includes/helpers.php';


class PagamentoController
{
    private $pagamentoModel;
    private $inscricaoModel;

    public function __construct()
    {
        $this->pagamentoModel = new Pagamento();
        $this->inscricaoModel = new Inscricao();
    }

    public function listar()
    {
        return $this->pagamentoModel->findAllComDetalhes();
    }

    public function obter($id)
    {
        return $this->pagamentoModel->findById($id);
    }

    /** Lista usada para preencher o <select> de inscrições na view. */
    public function listarInscricoes()
    {
        return $this->inscricaoModel->findAllComDetalhes();
    }

    public function criar($dados)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['inscricao_id']) || empty($dados['valor']) || empty($dados['data_pagamento'])) {
            set_flash('erro', 'Inscrição, valor e data são obrigatórios.');
            return false;
        }

        if (!is_numeric($dados['valor']) || $dados['valor'] <= 0) {
            set_flash('erro', 'O valor deve ser um número positivo.');
            return false;
        }

        $precoEsperado = $this->obterPrecoDoPlano($dados['inscricao_id']);

        if ($precoEsperado !== null && bccomp((string) $dados['valor'], (string) $precoEsperado, 2) !== 0) {
            set_flash('erro', "O valor deve corresponder exatamente ao preço do plano: " . number_format($precoEsperado, 2) . " Kz.");
            return false;
        }

        $this->pagamentoModel->create($dados);
        set_flash('sucesso', 'Pagamento registado com sucesso.');
        return true;
    }

    public function atualizar($id, $dados)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['valor']) || !is_numeric($dados['valor']) || $dados['valor'] <= 0) {
            set_flash('erro', 'O valor deve ser um número positivo.');
            return false;
        }

        $pagamentoAtual = $this->pagamentoModel->findById($id);
        $precoEsperado = $this->obterPrecoDoPlano($pagamentoAtual['inscricao_id']);

        if ($precoEsperado !== null && bccomp((string) $dados['valor'], (string) $precoEsperado, 2) !== 0) {
            set_flash('erro', "O valor deve corresponder exatamente ao preço do plano: " . number_format($precoEsperado, 2) . " Kz.");
            return false;
        }

        $this->pagamentoModel->update($id, $dados);
        set_flash('sucesso', 'Pagamento atualizado com sucesso.');
        return true;
    }

    public function eliminar($id)
    {
        $this->pagamentoModel->delete($id);
        set_flash('sucesso', 'Pagamento eliminado com sucesso.');
    }

    public function pesquisarPorData($data)
    {
        return $this->pagamentoModel->searchByData($data);
    }

   /** Devolve o preço do plano associado a uma inscrição, ou null se não encontrar. */
    private function obterPrecoDoPlano($inscricaoId)
    {
        $inscricoes = $this->inscricaoModel->findAllComDetalhes();
        foreach ($inscricoes as $inscricao) {
            if ($inscricao['id'] == $inscricaoId) {
                return (float) $inscricao['plano_preco'];
            }
        }
        return null;
    }
}