<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $utilizadorModel = new Utilizador();
    $utilizador = $utilizadorModel->findByEmail($email);

    // Por segurança, mostramos sempre a mesma mensagem,
    // quer o email exista quer não (evita confirmar quais emails estão registados)
    if ($utilizador) {
        // Aqui, num sistema real, geraria um token e enviaria um email.
        // Como é simulado, só mostramos a mensagem de confirmação.
    }

    set_flash('sucesso', 'Se o email existir no sistema, foram enviadas instruções de recuperação.');
    redirect('/ginasio-pw-final/views/auth/recuperar-senha.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Sistema de Ginásio</title>
</head>
<body>
    <h2>Recuperar Senha</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Email da tua conta:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Enviar instruções</button>
    </form>

    <p><a href="login.php">Voltar ao login</a></p>
</body>
</html>