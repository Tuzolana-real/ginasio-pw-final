<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PlanoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'codigo'        => $_POST['codigo'],
        'nome'          => $_POST['nome'],
        'preco'         => $_POST['preco'],
        'duracao_meses' => $_POST['duracao_meses'],
        'descricao'     => $_POST['descricao'],
    ];

    $controller->criar($dados);
    redirect('/ginasio-pw-final/views/planos/listar.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Plano - Sistema de Ginasio</title>
</head>
<body>
    <h2>Novo Plano</h2>

    <?php if ($flash): ?>
        <p style="color: red"><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Codigo:</label><br>
        <input type="text" name="codigo" required><br><br>

        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Preco (Kz):</label><br>
        <input type="number" step="0.01" name="preco" required><br><br>

        <label>Duracao em meses:</label><br>
        <input type="number" name="duracao_meses" min="1" required><br><br>

        <label>Descricao:</label><br>
        <textarea name="descricao" rows="4" cols="40"></textarea><br><br>

        <button type="submit">Guardar</button>
    </form>

    <p><a href="listar.php">Voltar a lista</a></p>
</body>
</html>
