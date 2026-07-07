<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$utilizadorModel = new Utilizador();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual = $_POST['senha_atual'];
    $novaSenha  = $_POST['nova_senha'];
    $confirmar  = $_POST['confirmar_senha'];

    $utilizador = $utilizadorModel->findById($_SESSION['user_id']);

    if (!password_verify($senhaAtual, $utilizador['senha'])) {
        set_flash('erro', 'A senha atual está incorreta.');
    } elseif ($novaSenha !== $confirmar) {
        set_flash('erro', 'A nova senha e a confirmação não coincidem.');
    } elseif (strlen($novaSenha) < 6) {
        set_flash('erro', 'A nova senha deve ter pelo menos 6 caracteres.');
    } else {
        $utilizadorModel->updateSenha($_SESSION['user_id'], password_hash($novaSenha, PASSWORD_DEFAULT));
        set_flash('sucesso', 'Senha alterada com sucesso.');
    }

    redirect('/ginasio-pw-final/views/auth/alterar-senha.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha - Sistema de Ginásio</title>
</head>
<body>
    <h2>Alterar Senha</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Senha atual:</label><br>
        <input type="password" name="senha_atual" required><br><br>

        <label>Nova senha:</label><br>
        <input type="password" name="nova_senha" required><br><br>

        <label>Confirmar nova senha:</label><br>
        <input type="password" name="confirmar_senha" required><br><br>

        <button type="submit">Alterar Senha</button>
    </form>
</body>
</html>