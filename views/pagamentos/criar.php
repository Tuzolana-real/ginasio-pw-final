<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new PagamentoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/pagamentos/criar.php');

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
$pageTitle = 'Novo Pagamento';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Novo Pagamento</h2>
                <p class="muted-text">Registe um novo pagamento para uma inscrição.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="inscricao_id">Inscrição</label>
                <select id="inscricao_id" name="inscricao_id" required>
                    <option value="">-- Selecionar --</option>
                    <?php foreach ($inscricoes as $inscricao): ?>
                        <option value="<?= $inscricao['id'] ?>">
                            <?= htmlspecialchars($inscricao['cliente_nome']) ?> — <?= htmlspecialchars($inscricao['plano_nome']) ?>
                            (<?= number_format($inscricao['plano_preco'], 2) ?> Kz)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="valor">Valor (Kz)</label>
                    <input type="number" id="valor" step="0.01" name="valor" required>
                </div>
                <div class="form-field">
                    <label for="data_pagamento">Data do pagamento</label>
                    <input type="date" id="data_pagamento" name="data_pagamento" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="forma_pagamento">Forma de pagamento</label>
                    <select id="forma_pagamento" name="forma_pagamento">
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Transferencia">Transferência</option>
                        <option value="Multicaixa">Multicaixa</option>
                        <option value="Cartao">Cartão</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="pago">Pago</option>
                        <option value="pendente">Pendente</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>