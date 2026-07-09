<?php

require_once __DIR__ . '/../config/Database.php';

class RelatorioController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function clientesPorEstado()
    {
        $sql = "SELECT estado, COUNT(*) AS total
                FROM clientes
                GROUP BY estado
                ORDER BY estado";
        return $this->db->query($sql)->fetchAll();
    }

    public function receitaMensal()
    {
        $sql = "SELECT DATE_FORMAT(data_pagamento, '%Y-%m') AS mes, SUM(valor) AS total
                FROM pagamentos
                WHERE estado = 'pago'
                GROUP BY DATE_FORMAT(data_pagamento, '%Y-%m')
                ORDER BY mes DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function frequenciaPorModalidade()
    {
        $sql = "SELECT COALESCE(m.nome, 'Sem modalidade') AS modalidade, COUNT(f.id) AS total
                FROM frequencia f
                LEFT JOIN modalidades m ON m.id = f.modalidade_id
                GROUP BY m.nome
                ORDER BY total DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function resumoDashboard()
    {
        return [
            'clientes' => $this->valorUnico("SELECT COUNT(*) FROM clientes"),
            'clientes_ativos' => $this->valorUnico("SELECT COUNT(*) FROM clientes WHERE estado = 'ativo'"),
            'inscricoes_ativas' => $this->valorUnico("SELECT COUNT(*) FROM inscricoes WHERE estado = 'ativa'"),
            'pagamentos_pendentes' => $this->valorUnico("SELECT COUNT(*) FROM pagamentos WHERE estado = 'pendente'"),
            'receita_mes' => $this->valorUnico(
                "SELECT COALESCE(SUM(valor), 0)
                 FROM pagamentos
                 WHERE estado = 'pago'
                 AND YEAR(data_pagamento) = YEAR(CURRENT_DATE)
                 AND MONTH(data_pagamento) = MONTH(CURRENT_DATE)"
            ),
            'entradas_hoje' => $this->valorUnico(
                "SELECT COUNT(*)
                 FROM frequencia
                 WHERE DATE(data_hora_entrada) = CURRENT_DATE"
            ),
        ];
    }

    public function inscricoesRecentes($limite = 5)
    {
        $stmt = $this->db->prepare(
            "SELECT i.id, i.data_inicio, i.data_fim, i.estado,
                    c.nome AS cliente_nome, p.nome AS plano_nome
             FROM inscricoes i
             JOIN clientes c ON c.id = i.cliente_id
             JOIN planos p ON p.id = i.plano_id
             ORDER BY i.id DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function pagamentosRecentes($limite = 5)
    {
        $stmt = $this->db->prepare(
            "SELECT pg.valor, pg.data_pagamento, pg.estado,
                    c.nome AS cliente_nome, p.nome AS plano_nome
             FROM pagamentos pg
             JOIN inscricoes i ON i.id = pg.inscricao_id
             JOIN clientes c ON c.id = i.cliente_id
             JOIN planos p ON p.id = i.plano_id
             ORDER BY pg.id DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function valorUnico($sql)
    {
        return $this->db->query($sql)->fetchColumn();
    }
}
