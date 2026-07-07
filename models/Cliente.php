<?php

require_once __DIR__ . '/Model.php';

class Cliente extends Model
{
    protected $table = 'clientes';

    /** Pesquisa clientes pelo nome (requisito de pesquisa do enunciado). */
    public function searchByNome($nome)
    {
        return $this->search('nome', $nome);
    }
}