<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new PagamentoController();

if (!empty($_GET['pesquisa_data'])) {
    $pagamentos = $controller->pesquisarPorData($_GET['pesquisa_data']);
} else {
    $pagamentos = $controller->listar();
}

$flash = get_flash();
$pageTitle = 'Pagamentos';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Pagamentos</h2>
                <p class="muted-text">Consulte pagamentos e o seu estado financeiro.</p>
            </div>
            <a class="button" href="criar.php">+ Novo Pagamento</a>
        </div>

        <form method="GET" class="filter-form">
            <div class="form-field">
                <label for="pesquisa_data">Pesquisar por data</label>
                <input type="date" id="pesquisa_data" name="pesquisa_data" value="<?= htmlspecialchars($_GET['pesquisa_data'] ?? '') ?>">
            </div>
            <button class="button" type="submit">Pesquisar</button>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Plano</th>
                        <th>Valor (Kz)</th>
                        <th>Data</th>
                        <th>Forma</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagamentos as $pagamento): ?>
                    <tr>
                        <td><?= htmlspecialchars($pagamento['cliente_nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($pagamento['plano_nome'] ?? '') ?></td>
                        <td><?= number_format($pagamento['valor'], 2) ?></td>
                        <td><?= htmlspecialchars($pagamento['data_pagamento']) ?></td>
                        <td><?= htmlspecialchars($pagamento['forma_pagamento']) ?></td>
                        <td><?= htmlspecialchars($pagamento['estado']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $pagamento['id'] ?>">Editar</a> |
                            <a href="eliminar.php?id=<?= $pagamento['id'] ?>" onclick="return confirm('Eliminar este pagamento?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>