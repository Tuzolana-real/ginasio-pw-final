<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestão de Ginásio</title>
</head>
<body>
    <h1>Sistema de Gestão de Ginásio</h1>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <?php if (is_logged_in()): ?>
        <p>Bem-vindo(a), <?= htmlspecialchars($_SESSION['user_nome']) ?>!</p>
        <p><a href="views/clientes/listar.php">Clientes</a> |
           <a href="views/inscricoes/listar.php">Inscrições</a> |
           <a href="views/pagamentos/listar.php">Pagamentos</a> |
           <a href="views/planos/listar.php">Planos</a> |
           <a href="views/auth/perfil.php">Meu Perfil</a> |
           <a href="views/auth/logout.php">Sair</a></p>
    <?php else: ?>
        <p><a href="views/auth/login.php">Entrar</a></p>
    <?php endif; ?>
</body>
</html>