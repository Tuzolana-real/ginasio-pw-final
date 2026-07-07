<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

if (is_logged_in()) {
    redirect('/ginasio-pw-final/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $lembrar = isset($_POST['lembrar']); // true se a checkbox foi marcada

    if ($auth->login($_POST['email'], $_POST['senha'], $lembrar)) {
        redirect('/ginasio-pw-final/index.php');
    } else {
        redirect('/ginasio-pw-final/views/auth/login.php');
    }
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Ginásio</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <label>
            <input type="checkbox" name="lembrar"> Lembrar-me
        </label><br><br>

        <button type="submit">Entrar</button>

        <p><a href="recuperar-senha.php">Esqueci a minha senha</a></p>
    </form>
</body>
</html>