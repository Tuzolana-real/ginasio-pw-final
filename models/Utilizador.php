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
}