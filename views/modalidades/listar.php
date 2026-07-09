<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ModalidadeController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new ModalidadeController();

if (!empty($_GET['pesquisa'])) {
    $modalidades = $controller->pesquisarPorCategoria($_GET['pesquisa']);
} else {
    $modalidades = $controller->listar();
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Modalidades - Sistema de Ginasio</title>
</head>
<body>
    <h2>Modalidades</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <p><a href="criar.php">+ Nova Modalidade</a></p>

    <form method="GET">
        <input type="text" name="pesquisa" placeholder="Pesquisar por categoria..." value="<?= htmlspecialchars($_GET['pesquisa'] ?? '') ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <table border="1" cellpadding="8">
        <tr>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Instrutor</th>
            <th>Vagas</th>
            <th>Descricao</th>
            <th>Acoes</th>
        </tr>
        <?php foreach ($modalidades as $modalidade): ?>
        <tr>
            <td><?= htmlspecialchars($modalidade['nome']) ?></td>
            <td><?= htmlspecialchars($modalidade['categoria'] ?? '') ?></td>
            <td><?= htmlspecialchars($modalidade['instrutor'] ?? '') ?></td>
            <td><?= htmlspecialchars($modalidade['vagas'] ?? '0') ?></td>
            <td><?= htmlspecialchars($modalidade['descricao'] ?? '') ?></td>
            <td>
                <a href="editar.php?id=<?= $modalidade['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $modalidade['id'] ?>" onclick="return confirm('Tens a certeza que queres eliminar esta modalidade?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
