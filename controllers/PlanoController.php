<?php

require_once __DIR__ . '/../models/Plano.php';
require_once __DIR__ . '/../includes/helpers.php';

class PlanoController
{
    private $planoModel;

    public function __construct()
    {
        $this->planoModel = new Plano();
    }

    public function listar()
    {
        return $this->planoModel->findAll('nome ASC');
    }

    public function obter($id)
    {
        return $this->planoModel->findById($id);
    }

    public function criar($dados)
    {
        $dados = sanitize_input($dados);

        if (!$this->validar($dados)) {
            return false;
        }

        $this->planoModel->create($dados);
        set_flash('sucesso', 'Plano criado com sucesso.');
        return true;
    }

    public function atualizar($id, $dados)
    {
        $dados = sanitize_input($dados);

        if (!$this->validar($dados)) {
            return false;
        }

        $this->planoModel->update($id, $dados);
        set_flash('sucesso', 'Plano atualizado com sucesso.');
        return true;
    }

    public function eliminar($id)
    {
        $this->planoModel->delete($id);
        set_flash('sucesso', 'Plano eliminado com sucesso.');
    }

    public function pesquisarPorCodigo($codigo)
    {
        return $this->planoModel->searchByCodigo($codigo);
    }

    private function validar($dados)
    {
        if (empty($dados['codigo']) || empty($dados['nome']) || empty($dados['preco']) || empty($dados['duracao_meses'])) {
            set_flash('erro', 'Codigo, nome, preco e duracao sao obrigatorios.');
            return false;
        }

        if (!is_numeric($dados['preco']) || $dados['preco'] <= 0) {
            set_flash('erro', 'O preco deve ser um numero positivo.');
            return false;
        }

        if (!ctype_digit((string) $dados['duracao_meses']) || (int) $dados['duracao_meses'] <= 0) {
            set_flash('erro', 'A duracao deve ser um numero inteiro positivo.');
            return false;
        }

        return true;
    }
}
