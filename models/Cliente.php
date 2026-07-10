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

    /** Procura cliente pelo BI, que é um campo único. */
    public function findByBI($bi)
    {
        if (empty($bi)) {
            return false;
        }

        return $this->findByColumn('bi', $bi);
    }
}