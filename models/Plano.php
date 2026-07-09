<?php

require_once __DIR__ . '/Model.php';

class Plano extends Model
{
    protected $table = 'planos';

    public function searchByCodigo($codigo)
    {
        return $this->search('codigo', $codigo);
    }
}
