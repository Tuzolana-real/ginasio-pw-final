<?php

require_once __DIR__ . '/../models/Utilizador.php';
require_once __DIR__ . '/../includes/helpers.php';

class AuthController
{
    private $utilizadorModel;

    public function __construct()
    {
        $this->utilizadorModel = new Utilizador();
    }

    /** Processa a tentativa de login vinda do formulário. */
    public function login($email, $senha, $lembrar = false)
    {
        $email = sanitize_input($email);

        $utilizador = $this->utilizadorModel->findByEmail($email);

        if (!$utilizador) {
            set_flash('erro', 'Email ou senha incorretos.');
            return false;
        }

        if (!$utilizador['ativo']) {
            set_flash('erro', 'Esta conta está inativa.');
            return false;
        }

        if (!password_verify($senha, $utilizador['senha'])) {
            set_flash('erro', 'Email ou senha incorretos.');
            return false;
        }

        // Login válido: guardar dados essenciais na sessão
        $_SESSION['user_id']    = $utilizador['id'];
        $_SESSION['user_nome']  = $utilizador['nome'];
        $_SESSION['user_role']  = $utilizador['perfil_id']; // por agora guardamos o id do perfil

        if ($lembrar) {
            // Cookie válido por 30 dias, guarda só o id (nunca a senha)
            setcookie('lembrar_me', $utilizador['id'], time() + (30 * 24 * 60 * 60), '/');
        }

        return true;
    }

    /** Termina a sessão do utilizador e limpa o cookie "lembrar-me", se existir. */
    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        if (isset($_COOKIE['lembrar_me'])) {
            setcookie('lembrar_me', '', time() - 3600, '/');
        }
    }
}