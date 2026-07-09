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

    /** Procura um utilizador por token de recuperação válido. */
    public function findByResetToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE reset_token = :token AND reset_token_expira_em > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    /** Guarda o token de recuperação de senha para um utilizador. */
    public function setResetToken($id, $token, $expiraEm)
    {
        $stmt = $this->db->prepare("UPDATE utilizadores SET reset_token = :token, reset_token_expira_em = :expira_em WHERE id = :id");
        return $stmt->execute(['token' => $token, 'expira_em' => $expiraEm, 'id' => $id]);
    }

    /** Limpa o token de recuperação de senha após a redefinição. */
    public function clearResetToken($id)
    {
        $stmt = $this->db->prepare("UPDATE utilizadores SET reset_token = NULL, reset_token_expira_em = NULL WHERE id = :id");
        return $stmt->execute(['id' => $id]);
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