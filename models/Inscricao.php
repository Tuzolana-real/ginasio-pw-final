<?php

require_once __DIR__ . '/Model.php';

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    /**
     * Devolve as inscrições já com o nome do cliente e do plano juntos
     * (JOIN), para não termos de fazer múltiplas queries nas views.
     */
    public function findAllComDetalhes()
    {
        $sql = "SELECT i.*, c.nome AS cliente_nome, p.nome AS plano_nome, p.preco AS plano_preco
                FROM inscricoes i
                JOIN clientes c ON c.id = i.cliente_id
                JOIN planos p ON p.id = i.plano_id
                ORDER BY i.data_inicio DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /** Pesquisa inscrições pela data de início (requisito de pesquisa por data). */
    public function searchByData($data)
    {
        $stmt = $this->db->prepare("SELECT * FROM inscricoes WHERE data_inicio = :data");
        $stmt->execute(['data' => $data]);
        return $stmt->fetchAll();
    }
}