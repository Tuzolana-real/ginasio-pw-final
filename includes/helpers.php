<?php

/**
 * Funções auxiliares usadas em todo o sistema.
 * Basta fazer require_once deste ficheiro onde for preciso.
 */

/** Limpa recursivamente input do utilizador (proteção básica contra XSS). */
function sanitize_input($data)
{
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/** Redireciona para outro caminho e termina a execução. */
function redirect($path)
{
    header("Location: {$path}");
    exit;
}

/** Verifica se há um utilizador autenticado na sessão. */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/** Verifica se o utilizador autenticado tem um determinado perfil. */
function has_role($role)
{
    return is_logged_in() && ($_SESSION['user_role'] ?? null) === $role;
}

/** Guarda uma mensagem "flash" para mostrar na próxima página (sucesso/erro). */
function set_flash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** Lê (e apaga) a mensagem flash guardada, se existir. */
function get_flash()
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}