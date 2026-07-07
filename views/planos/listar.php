<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/ExchangeRate.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new PlanoController();
$planos = $controller->listar();

// Busca as taxas UMA vez só (evita chamar a API várias vezes, uma por plano)
$taxas = ExchangeRate::obterTaxas();

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Planos - Sistema de Ginásio</title>
</head>
<body>
    <h2>Planos</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <?php if ($taxas === null): ?>
        <p style="color: orange"><em>Não foi possível obter as taxas de câmbio no momento. A mostrar apenas os preços em Kwanza.</em></p>
    <?php endif; ?>

    <table border="1" cellpadding="8">
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Duração</th>
            <th>Preço (Kz)</th>
            <th>Preço (USD)</th>
            <th>Preço (EUR)</th>
        </tr>
        <?php foreach ($planos as $plano): ?>
        <tr>
            <td><?= htmlspecialchars($plano['codigo']) ?></td>
            <td><?= htmlspecialchars($plano['nome']) ?></td>
            <td><?= $plano['duracao_meses'] ?> mês(es)</td>
            <td><?= number_format($plano['preco'], 2) ?></td>
            <td>
                <?= $taxas !== null && isset($taxas['USD'])
                    ? number_format($plano['preco'] * $taxas['USD'], 2)
                    : '—' ?>
            </td>
            <td>
                <?= $taxas !== null && isset($taxas['EUR'])
                    ? number_format($plano['preco'] * $taxas['EUR'], 2)
                    : '—' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>