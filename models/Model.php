<?php

require_once __DIR__ . '/../config/Database.php';

/**
 * Classe base abstrata para todos os Models do sistema.
 * Cada Model concreto (Cliente, Plano, Inscricao...) estende esta classe
 * e só precisa de definir $table e, opcionalmente, $primaryKey.
 *
 * Todas as queries aqui usam Prepared Statements do PDO — nunca
 * concatenar valores diretamente na string SQL (proteção contra SQL Injection).
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Devolve todos os registos, opcionalmente ordenados. */
    public function findAll($orderBy = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy !== null) {
            $sql .= " ORDER BY {$orderBy}";
        }
        return $this->db->query($sql)->fetchAll();
    }

    /** Procura um registo pela chave primária. */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /** Insere um novo registo. $data = ['coluna' => valor, ...] */
    public function create($data)
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"
        );

        return $stmt->execute($data);
    }

    /** Atualiza um registo existente pelo id. */
    public function update($id, $data)
    {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "{$column} = :{$column}";
        }

        $data['__id'] = $id; // nome diferente para não colidir com colunas chamadas "id"

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE {$this->primaryKey} = :__id"
        );

        return $stmt->execute($data);
    }

    /** Elimina um registo pelo id. */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    /** Pesquisa simples por LIKE numa coluna (usar em nome, código, etc.). */
    public function search($column, $term)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} LIKE :term");
        $stmt->execute(['term' => "%{$term}%"]);
        return $stmt->fetchAll();
    }
}