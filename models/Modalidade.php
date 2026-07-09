<?php

require_once __DIR__ . '/Model.php';

class Modalidade extends Model
{
    protected $table = 'modalidades';

    public function searchByCategoria($categoria)
    {
        return $this->search('categoria', $categoria);
    }
}
