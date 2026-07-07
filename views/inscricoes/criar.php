<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/InscricaoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new InscricaoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'cliente_id'    => $_POST['cliente_id'],
        'plano_id'      => $_POST['plano_id'],
        'modalidade_id' => $_POST['modalidade_id'] ?? null,
        'data_inicio'   => $_POST['data_inicio'],
        'estado'        => 'ativa',
    ];

    $controller->criar($dados);
    redirect('/ginasio-pw-final/views/inscricoes/listar.php');
}

$clientes = $controller->listarClientes();
$planos   = $controller->listarPlanos();
$flash    = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Inscrição - Sistema de Ginásio</title>
</head>
<body>
    <h2>Nova Inscrição</h2>

    <?php if ($flash): ?>
        <p style="color: red"><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Cliente:</label><br>
        <select name="cliente_id" required>
            <option value="">-- Selecionar --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Plano:</label><br>
        <select name="plano_id" required>
            <option value="">-- Selecionar --</option>
            <?php foreach ($planos as $plano): ?>
                <option value="<?= $plano['id'] ?>">
                    <?= htmlspecialchars($plano['nome']) ?> (<?= $plano['duracao_meses'] ?> mês(es) - <?= number_format($plano['preco'], 2) ?> Kz)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Data de início:</label><br>
        <input type="date" name="data_inicio" required><br><br>

        <p><em>A data de fim é calculada automaticamente com base na duração do plano.</em></p>

        <button type="submit">Guardar</button>
    </form>

    <p><a href="listar.php">Voltar à lista</a></p>
</body>
</html>