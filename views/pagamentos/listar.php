<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new PagamentoController();

if (!empty($_GET['pesquisa_data'])) {
    $pagamentos = $controller->pesquisarPorData($_GET['pesquisa_data']);
} else {
    $pagamentos = $controller->listar();
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pagamentos - Sistema de Ginásio</title>
</head>
<body>
    <h2>Pagamentos</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <p><a href="criar.php">+ Novo Pagamento</a></p>

    <form method="GET">
        <label>Pesquisar por data:</label>
        <input type="date" name="pesquisa_data" value="<?= htmlspecialchars($_GET['pesquisa_data'] ?? '') ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>Cliente</th>
            <th>Plano</th>
            <th>Valor (Kz)</th>
            <th>Data</th>
            <th>Forma</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($pagamentos as $pagamento): ?>
        <tr>
            <td><?= htmlspecialchars($pagamento['cliente_nome'] ?? '') ?></td>
            <td><?= htmlspecialchars($pagamento['plano_nome'] ?? '') ?></td>
            <td><?= number_format($pagamento['valor'], 2) ?></td>
            <td><?= htmlspecialchars($pagamento['data_pagamento']) ?></td>
            <td><?= htmlspecialchars($pagamento['forma_pagamento']) ?></td>
            <td><?= htmlspecialchars($pagamento['estado']) ?></td>
            <td>
                <a href="editar.php?id=<?= $pagamento['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $pagamento['id'] ?>" onclick="return confirm('Eliminar este pagamento?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>