<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new ClienteController();

// Pesquisa por nome (requisito de pesquisa)
if (!empty($_GET['pesquisa'])) {
    $clientes = $controller->pesquisar($_GET['pesquisa']);
} else {
    $clientes = $controller->listar();
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes - Sistema de Ginásio</title>
</head>
<body>
    <h2>Clientes</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <p><a href="criar.php">+ Novo Cliente</a></p>

    <form method="GET">
        <input type="text" name="pesquisa" placeholder="Pesquisar por nome..." value="<?= htmlspecialchars($_GET['pesquisa'] ?? '') ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($clientes as $cliente): ?>
        <tr>
            <td>
                <?php if (!empty($cliente['foto'])): ?>
                    <img src="/ginasio-pw-final/assets/uploads/<?= htmlspecialchars($cliente['foto']) ?>" width="50">
                <?php else: ?>
                    Sem foto
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($cliente['nome']) ?></td>
            <td><?= htmlspecialchars($cliente['telefone'] ?? '') ?></td>
            <td><?= htmlspecialchars($cliente['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($cliente['estado']) ?></td>
            <td>
                <a href="editar.php?id=<?= $cliente['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $cliente['id'] ?>" onclick="return confirm('Tens a certeza que queres eliminar este cliente?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>