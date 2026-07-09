<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$utilizadorModel = new Utilizador();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = sanitize_input($_POST['nome']);
    $email = sanitize_input($_POST['email']);

    if (empty($nome) || empty($email)) {
        set_flash('erro', 'Nome e email são obrigatórios.');
    } else {
        $utilizadorModel->updatePerfil($_SESSION['user_id'], $nome, $email);
        $_SESSION['user_nome'] = $nome; // atualizar também na sessão atual
        set_flash('sucesso', 'Perfil atualizado com sucesso.');
    }

    redirect('/ginasio-pw-final/views/auth/perfil.php');
}

$utilizador = $utilizadorModel->findById($_SESSION['user_id']);
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Sistema de Ginásio</title>
</head>
<body>
    <h2>Meu Perfil</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($utilizador['email']) ?>" required><br><br>

        <button type="submit">Guardar Alterações</button>
    </form>

    <p><a href="alterar-senha.php">Trocar senha</a></p>
</body>
</html>