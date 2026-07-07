<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/InscricaoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new InscricaoController();
$inscricao = $controller->obter($_GET['id']);

if (!$inscricao) {
    redirect('/ginasio-pw-final/views/inscricoes/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'cliente_id'    => $_POST['cliente_id'],
        'plano_id'      => $_POST['plano_id'],
        'modalidade_id' => $_POST['modalidade_id'] ?? null,
        'data_inicio'   => $_POST['data_inicio'],
        'estado'        => $_POST['estado'],
    ];

    $controller->atualizar($inscricao['id'], $dados);
    redirect('/ginasio-pw-final/views/inscricoes/listar.php');
}

$clientes = $controller->listarClientes();
$planos   = $controller->listarPlanos();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Inscrição - Sistema de Ginásio</title>
</head>
<body>
    <h2>Editar Inscrição</h2>

    <form method="POST">
        <label>Cliente:</label><br>
        <select name="cliente_id" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>" <?= $cliente['id'] == $inscricao['cliente_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Plano:</label><br>
        <select name="plano_id" required>
            <?php foreach ($planos as $plano): ?>
                <option value="<?= $plano['id'] ?>" <?= $plano['id'] == $inscricao['plano_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($plano['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Data de início:</label><br>
        <input type="date" name="data_inicio" value="<?= htmlspecialchars($inscricao['data_inicio']) ?>" required><br><br>

        <label>Estado:</label><br>
        <select name="estado">
            <option value="ativa" <?= $inscricao['estado'] === 'ativa' ? 'selected' : '' ?>>Ativa</option>
            <option value="expirada" <?= $inscricao['estado'] === 'expirada' ? 'selected' : '' ?>>Expirada</option>
            <option value="cancelada" <?= $inscricao['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select><br><br>

        <button type="submit">Guardar Alterações</button>
    </form>

    <p><a href="listar.php">Voltar à lista</a></p>
</body>
</html>