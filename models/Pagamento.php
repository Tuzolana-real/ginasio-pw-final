<?php

require_once __DIR__ . '/Model.php';

class Pagamento extends Model
{
    protected $table = 'pagamentos';

    /**
     * Devolve os pagamentos já com o nome do cliente e do plano juntos (JOIN),
     * evitando múltiplas queries nas views.
     */
    public function findAllComDetalhes()
    {
        $sql = "SELECT pg.*, c.nome AS cliente_nome, p.nome AS plano_nome
                FROM pagamentos pg
                JOIN inscricoes i ON i.id = pg.inscricao_id
                JOIN clientes c ON c.id = i.cliente_id
                JOIN planos p ON p.id = i.plano_id
                ORDER BY pg.data_pagamento DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /** Pesquisa pagamentos pela data (requisito de pesquisa por data). */
    /** Pesquisa pagamentos pela data (requisito de pesquisa por data), já com detalhes. */
    public function searchByData($data)
    {
        $sql = "SELECT pg.*, c.nome AS cliente_nome, p.nome AS plano_nome
                FROM pagamentos pg
                JOIN inscricoes i ON i.id = pg.inscricao_id
                JOIN clientes c ON c.id = i.cliente_id
                JOIN planos p ON p.id = i.plano_id
                WHERE pg.data_pagamento = :data
                ORDER BY pg.data_pagamento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['data' => $data]);
        return $stmt->fetchAll();
    }
}