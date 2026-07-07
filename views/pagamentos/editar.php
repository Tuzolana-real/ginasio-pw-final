<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new PagamentoController();
$pagamento = $controller->obter($_GET['id']);

if (!$pagamento) {
    redirect('/ginasio-pw-final/views/pagamentos/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'valor'           => $_POST['valor'],
        'data_pagamento'  => $_POST['data_pagamento'],
        'forma_pagamento' => $_POST['forma_pagamento'],
        'estado'          => $_POST['estado'],
    ];

    $controller->atualizar($pagamento['id'], $dados);
    redirect('/ginasio-pw-final/views/pagamentos/listar.php');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Pagamento - Sistema de Ginásio</title>
</head>
<body>
    <h2>Editar Pagamento</h2>

    <form method="POST">
        <label>Valor (Kz):</label><br>
        <input type="number" step="0.01" name="valor" value="<?= htmlspecialchars($pagamento['valor']) ?>" required><br><br>

        <label>Data do pagamento:</label><br>
        <input type="date" name="data_pagamento" value="<?= htmlspecialchars($pagamento['data_pagamento']) ?>" required><br><br>

        <label>Forma de pagamento:</label><br>
        <select name="forma_pagamento">
            <?php foreach (['Dinheiro', 'Transferencia', 'Multicaixa', 'Cartao'] as $forma): ?>
                <option value="<?= $forma ?>" <?= $pagamento['forma_pagamento'] === $forma ? 'selected' : '' ?>><?= $forma ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Estado:</label><br>
        <select name="estado">
            <option value="pago" <?= $pagamento['estado'] === 'pago' ? 'selected' : '' ?>>Pago</option>
            <option value="pendente" <?= $pagamento['estado'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
        </select><br><br>

        <button type="submit">Guardar Alterações</button>
    </form>

    <p><a href="listar.php">Voltar à lista</a></p>
</body>
</html>