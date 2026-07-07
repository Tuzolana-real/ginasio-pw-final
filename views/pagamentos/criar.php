<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new PagamentoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'inscricao_id'    => $_POST['inscricao_id'],
        'valor'           => $_POST['valor'],
        'data_pagamento'  => $_POST['data_pagamento'],
        'forma_pagamento' => $_POST['forma_pagamento'],
        'estado'          => $_POST['estado'],
    ];

    $controller->criar($dados);
    redirect('/ginasio-pw-final/views/pagamentos/listar.php');
}

$inscricoes = $controller->listarInscricoes();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Pagamento - Sistema de Ginásio</title>
</head>
<body>
    <h2>Novo Pagamento</h2>

    <?php if ($flash): ?>
        <p style="color: red"><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Inscrição:</label><br>
        <select name="inscricao_id" required>
            <option value="">-- Selecionar --</option>
            <?php foreach ($inscricoes as $inscricao): ?>
                <option value="<?= $inscricao['id'] ?>">
                    <?= htmlspecialchars($inscricao['cliente_nome']) ?> — <?= htmlspecialchars($inscricao['plano_nome']) ?>
                    (<?= number_format($inscricao['plano_preco'], 2) ?> Kz)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Valor (Kz):</label><br>
        <input type="number" step="0.01" name="valor" required><br><br>

        <label>Data do pagamento:</label><br>
        <input type="date" name="data_pagamento" required><br><br>

        <label>Forma de pagamento:</label><br>
        <select name="forma_pagamento">
            <option value="Dinheiro">Dinheiro</option>
            <option value="Transferencia">Transferência</option>
            <option value="Multicaixa">Multicaixa</option>
            <option value="Cartao">Cartão</option>
        </select><br><br>

        <label>Estado:</label><br>
        <select name="estado">
            <option value="pago">Pago</option>
            <option value="pendente">Pendente</option>
        </select><br><br>

        <button type="submit">Guardar</button>
    </form>

    <p><a href="listar.php">Voltar à lista</a></p>
</body>
</html>