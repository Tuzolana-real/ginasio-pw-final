<?php

require_once __DIR__ . '/Model.php';

class Frequencia extends Model
{
    protected $table = 'frequencia';

    public function findAllComDetalhes()
    {
        $sql = "SELECT f.*, c.nome AS cliente_nome, m.nome AS modalidade_nome
                FROM frequencia f
                JOIN clientes c ON c.id = f.cliente_id
                LEFT JOIN modalidades m ON m.id = f.modalidade_id
                ORDER BY f.data_hora_entrada DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findEntradaAberta($clienteId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM frequencia
             WHERE cliente_id = :cliente_id AND data_hora_saida IS NULL
             ORDER BY data_hora_entrada DESC
             LIMIT 1"
        );
        $stmt->execute(['cliente_id' => $clienteId]);
        return $stmt->fetch();
    }

    public function registrarSaida($id)
    {
        $stmt = $this->db->prepare("UPDATE frequencia SET data_hora_saida = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
