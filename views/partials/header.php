<?php
$pageTitle = $pageTitle ?? 'Sistema de Gestao de Ginasio';
$baseUrl = '/ginasio-pw-final';
$showSidebar = $showSidebar ?? true;
$showTopbarActions = $showTopbarActions ?? true;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <?php if ($showSidebar): ?>
        <?php require __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>

    <div class="app-main">
        <header class="topbar">
            <?php if ($showSidebar): ?>
                <button class="menu-toggle" type="button" data-sidebar-toggle aria-label="Abrir menu">Menu</button>
            <?php endif; ?>
            <div>
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <p><?= htmlspecialchars($_SESSION['user_nome'] ?? 'Visitante') ?></p>
            </div>
            <?php if ($showTopbarActions): ?>
                <div class="topbar-actions">
                    <a href="<?= $baseUrl ?>/views/auth/perfil.php">Perfil</a>
                    <a href="<?= $baseUrl ?>/views/auth/logout.php">Sair</a>
                </div>
            <?php endif; ?>
        </header>

        <main class="content">
