<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/InscricaoController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new InscricaoController();

if (!empty($_GET['pesquisa_data'])) {
    $inscricoes = $controller->pesquisarPorData($_GET['pesquisa_data']);
} else {
    $inscricoes = $controller->listar();
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Inscrições - Sistema de Ginásio</title>
</head>
<body>
    <h2>Inscrições</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <p><a href="criar.php">+ Nova Inscrição</a></p>

    <form method="GET">
        <label>Pesquisar por data de início:</label>
        <input type="date" name="pesquisa_data" value="<?= htmlspecialchars($_GET['pesquisa_data'] ?? '') ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>Cliente</th>
            <th>Plano</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($inscricoes as $inscricao): ?>
        <tr>
            <td><?= htmlspecialchars($inscricao['cliente_nome'] ?? '') ?></td>
            <td><?= htmlspecialchars($inscricao['plano_nome'] ?? '') ?></td>
            <td><?= htmlspecialchars($inscricao['data_inicio']) ?></td>
            <td><?= htmlspecialchars($inscricao['data_fim']) ?></td>
            <td><?= htmlspecialchars($inscricao['estado']) ?></td>
            <td>
                <a href="editar.php?id=<?= $inscricao['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $inscricao['id'] ?>" onclick="return confirm('Eliminar esta inscrição?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>