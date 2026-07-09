<?php

require_once __DIR__ . '/../models/Modalidade.php';
require_once __DIR__ . '/../includes/helpers.php';

class ModalidadeController
{
    private $modalidadeModel;

    public function __construct()
    {
        $this->modalidadeModel = new Modalidade();
    }

    public function listar()
    {
        return $this->modalidadeModel->findAll('nome ASC');
    }

    public function obter($id)
    {
        return $this->modalidadeModel->findById($id);
    }

    public function criar($dados)
    {
        $dados = sanitize_input($dados);

        if (!$this->validar($dados)) {
            return false;
        }

        $this->modalidadeModel->create($dados);
        set_flash('sucesso', 'Modalidade criada com sucesso.');
        return true;
    }

    public function atualizar($id, $dados)
    {
        $dados = sanitize_input($dados);

        if (!$this->validar($dados)) {
            return false;
        }

        $this->modalidadeModel->update($id, $dados);
        set_flash('sucesso', 'Modalidade atualizada com sucesso.');
        return true;
    }

    public function eliminar($id)
    {
        $this->modalidadeModel->delete($id);
        set_flash('sucesso', 'Modalidade eliminada com sucesso.');
    }

    public function pesquisarPorCategoria($categoria)
    {
        return $this->modalidadeModel->searchByCategoria($categoria);
    }

    private function validar($dados)
    {
        if (empty($dados['nome'])) {
            set_flash('erro', 'O nome da modalidade e obrigatorio.');
            return false;
        }

        if ($dados['vagas'] !== '' && (!ctype_digit((string) $dados['vagas']) || (int) $dados['vagas'] < 0)) {
            set_flash('erro', 'As vagas devem ser zero ou um numero inteiro positivo.');
            return false;
        }

        return true;
    }
}
