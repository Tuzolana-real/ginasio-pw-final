<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/ExchangeRate.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new PlanoController();

if (!empty($_GET['pesquisa'])) {
    $planos = $controller->pesquisarPorCodigo($_GET['pesquisa']);
} else {
    $planos = $controller->listar();
}

$taxas = ExchangeRate::obterTaxas();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Planos - Sistema de Ginasio</title>
</head>
<body>
    <h2>Planos</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <?php if ($taxas === null): ?>
        <p style="color: orange"><em>Nao foi possivel obter as taxas de cambio no momento. A mostrar apenas os precos em Kwanza.</em></p>
    <?php endif; ?>

    <p><a href="criar.php">+ Novo Plano</a></p>

    <form method="GET">
        <input type="text" name="pesquisa" placeholder="Pesquisar por codigo..." value="<?= htmlspecialchars($_GET['pesquisa'] ?? '') ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>Codigo</th>
            <th>Nome</th>
            <th>Duracao</th>
            <th>Preco (Kz)</th>
            <th>Preco (USD)</th>
            <th>Preco (EUR)</th>
            <th>Acoes</th>
        </tr>
        <?php foreach ($planos as $plano): ?>
        <tr>
            <td><?= htmlspecialchars($plano['codigo']) ?></td>
            <td><?= htmlspecialchars($plano['nome']) ?></td>
            <td><?= htmlspecialchars($plano['duracao_meses']) ?> mes(es)</td>
            <td><?= number_format($plano['preco'], 2) ?></td>
            <td>
                <?= $taxas !== null && isset($taxas['USD'])
                    ? number_format($plano['preco'] * $taxas['USD'], 2)
                    : '-' ?>
            </td>
            <td>
                <?= $taxas !== null && isset($taxas['EUR'])
                    ? number_format($plano['preco'] * $taxas['EUR'], 2)
                    : '-' ?>
            </td>
            <td>
                <a href="editar.php?id=<?= $plano['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $plano['id'] ?>" onclick="return confirm('Tens a certeza que queres eliminar este plano?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
