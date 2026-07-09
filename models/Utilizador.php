<?php

require_once __DIR__ . '/Model.php';

class Utilizador extends Model
{
    protected $table = 'utilizadores';

    /** Procura um utilizador pelo email (usado no login). */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /** Atualiza apenas a senha (já em hash) de um utilizador. */
    public function updateSenha($id, $novaSenhaHash)
    {
        $stmt = $this->db->prepare("UPDATE utilizadores SET senha = :senha WHERE id = :id");
        return $stmt->execute(['senha' => $novaSenhaHash, 'id' => $id]);
    }

    /** Atualiza o nome e o email de um utilizador (dados básicos do perfil). */
    public function updatePerfil($id, $nome, $email)
    {
        $stmt = $this->db->prepare("UPDATE utilizadores SET nome = :nome, email = :email WHERE id = :id");
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'id' => $id]);
    }
}