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
    require_csrf_token('/ginasio-pw-final/views/pagamentos/editar.php');

    $dados = [
        'valor'           => $_POST['valor'],
        'data_pagamento'  => $_POST['data_pagamento'],
        'forma_pagamento' => $_POST['forma_pagamento'],
        'estado'          => $_POST['estado'],
    ];

    $controller->atualizar($pagamento['id'], $dados);
    redirect('/ginasio-pw-final/views/pagamentos/listar.php');
}

$pageTitle = 'Editar Pagamento';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Editar Pagamento</h2>
                <p class="muted-text">Atualize a informação de pagamento registada.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="valor">Valor (Kz)</label>
                    <input type="number" id="valor" step="0.01" name="valor" value="<?= htmlspecialchars($pagamento['valor']) ?>" required>
                </div>
                <div class="form-field">
                    <label for="data_pagamento">Data do pagamento</label>
                    <input type="date" id="data_pagamento" name="data_pagamento" value="<?= htmlspecialchars($pagamento['data_pagamento']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="forma_pagamento">Forma de pagamento</label>
                    <select id="forma_pagamento" name="forma_pagamento">
                        <?php foreach (['Dinheiro', 'Transferencia', 'Multicaixa', 'Cartao'] as $forma): ?>
                            <option value="<?= $forma ?>" <?= $pagamento['forma_pagamento'] === $forma ? 'selected' : '' ?>><?= $forma ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="pago" <?= $pagamento['estado'] === 'pago' ? 'selected' : '' ?>>Pago</option>
                        <option value="pendente" <?= $pagamento['estado'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar Alterações</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>