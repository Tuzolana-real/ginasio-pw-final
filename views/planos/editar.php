<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PlanoController();
$plano = $controller->obter($_GET['id'] ?? null);

if (!$plano) {
    redirect('/ginasio-pw-final/views/planos/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'codigo'        => $_POST['codigo'],
        'nome'          => $_POST['nome'],
        'preco'         => $_POST['preco'],
        'duracao_meses' => $_POST['duracao_meses'],
        'descricao'     => $_POST['descricao'],
    ];

    $controller->atualizar($plano['id'], $dados);
    redirect('/ginasio-pw-final/views/planos/listar.php');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Plano - Sistema de Ginasio</title>
</head>
<body>
    <h2>Editar Plano</h2>

    <form method="POST">
        <label>Codigo:</label><br>
        <input type="text" name="codigo" value="<?= htmlspecialchars($plano['codigo']) ?>" required><br><br>

        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($plano['nome']) ?>" required><br><br>

        <label>Preco (Kz):</label><br>
        <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($plano['preco']) ?>" required><br><br>

        <label>Duracao em meses:</label><br>
        <input type="number" name="duracao_meses" min="1" value="<?= htmlspecialchars($plano['duracao_meses']) ?>" required><br><br>

        <label>Descricao:</label><br>
        <textarea name="descricao" rows="4" cols="40"><?= htmlspecialchars($plano['descricao'] ?? '') ?></textarea><br><br>

        <button type="submit">Guardar Alteracoes</button>
    </form>

    <p><a href="listar.php">Voltar a lista</a></p>
</body>
</html>
