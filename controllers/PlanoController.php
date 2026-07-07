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

    // Nota para a equipa: criar(), atualizar(), eliminar() e a pesquisa por
    // codigo ainda faltam ser implementados — ficam a cargo do Colega,
    // seguindo o mesmo padrão usado em ClienteController.php.
}