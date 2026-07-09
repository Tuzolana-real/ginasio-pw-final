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

/** Devolve a URL base da aplicação, lida do ficheiro de configuração. */
function site_url()
{
    static $url = null;

    if ($url !== null) {
        return $url;
    }

    $configFile = __DIR__ . '/../config/env.php';
    $config = file_exists($configFile) ? require $configFile : [];

    $url = rtrim($config['APP_URL'] ?? 'http://localhost/ginasio-pw-final', '/');
    return $url;
}

/** Envia um email simples usando a função mail() do PHP. */
function send_mail($to, $subject, $message, $fromEmail = null, $fromName = null)
{
    $configFile = __DIR__ . '/../config/env.php';
    $config = file_exists($configFile) ? require $configFile : [];

    $fromEmail = $fromEmail ?? ($config['MAIL_FROM'] ?? 'no-reply@ginasio.local');
    $fromName = $fromName ?? ($config['MAIL_FROM_NAME'] ?? 'Sistema de Ginásio');

    $headers = [];
    $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
    $headers[] = 'Reply-To: ' . $fromEmail;
    $headers[] = 'X-Mailer: PHP/' . phpversion();

    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/** Envia o email de recuperação de senha com um link para redefinir a palavra-passe. */
function send_password_reset_email($to, $nome, $token)
{
    $link = site_url() . '/views/auth/redefinir-senha.php?token=' . urlencode($token);
    $subject = 'Recuperação de senha - Sistema de Ginásio';
    $message = "Olá {$nome},\n\n"
        . "Recebemos um pedido para redefinir a tua password.\n"
        . "Clica no link abaixo para criar uma nova palavra-passe:\n\n"
        . $link . "\n\n"
        . "Se não foste tu a pedir esta recuperação, ignora esta mensagem.\n\n"
        . "Sistema de Ginásio";

    return send_mail($to, $subject, $message);
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

/** Gera ou devolve o token CSRF da sessão atual. */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/** Devolve o campo hidden para incluir nos formulários. */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/** Garante que o token CSRF recebido é válido para esta sessão. */
function require_csrf_token($redirectTo = null)
{
    $expectedToken = $_SESSION['csrf_token'] ?? '';
    $receivedToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($expectedToken, $receivedToken)) {
        set_flash('erro', 'Sessão expirada. Por favor, tente novamente.');
        redirect($redirectTo ?? ($_SERVER['REQUEST_URI'] ?? '/ginasio-pw-final/index.php'));
    }
}

/** Verifica se o utilizador autenticado tem um determinado perfil. */
function has_role($role)
{
    return is_logged_in() && ($_SESSION['user_role'] ?? null) === $role;
}

/**
 * Protege uma página, exigindo sessão iniciada e (opcionalmente) um
 * conjunto de perfis autorizados. Redireciona e bloqueia se não cumprir.
 *
 * Exemplo de uso, no topo de uma view:
 *   require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);
 */
function require_perfil($perfisPermitidos = null)
{
    if (!is_logged_in()) {
        redirect('/ginasio-pw-final/views/auth/login.php');
    }

    if ($perfisPermitidos !== null && !in_array($_SESSION['user_role'], $perfisPermitidos)) {
        set_flash('erro', 'Não tens permissão para aceder a esta página.');
        redirect('/ginasio-pw-final/index.php');
    }
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

// IDs correspondentes à tabela "perfis" (ver database/ginasio.sql)
define('PERFIL_ADMIN', 1);
define('PERFIL_RECEPCIONISTA', 2);
define('PERFIL_CLIENTE', 3);