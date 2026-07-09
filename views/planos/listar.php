<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/ExchangeRate.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PlanoController();

if (!empty($_GET['pesquisa'])) {
    $planos = $controller->pesquisarPorCodigo($_GET['pesquisa']);
} else {
    $planos = $controller->listar();
}

$taxas = ExchangeRate::obterTaxas();
$flash = get_flash();
$pageTitle = 'Planos';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Planos</h2>
                <p class="muted-text">Gerir os planos de treino e respectivos preços.</p>
            </div>
            <a class="button" href="criar.php">+ Novo Plano</a>
        </div>

        <?php if ($taxas === null): ?>
            <p class="muted-text"><em>Nao foi possivel obter as taxas de cambio no momento. A mostrar apenas os precos em Kwanza.</em></p>
        <?php endif; ?>

        <form method="GET" class="filter-form">
            <div class="form-field">
                <label for="pesquisa">Pesquisar por código</label>
                <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar por código..." value="<?= htmlspecialchars($_GET['pesquisa'] ?? '') ?>">
            </div>
            <button class="button" type="submit">Pesquisar</button>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Duração</th>
                        <th>Preço (Kz)</th>
                        <th>Preço (USD)</th>
                        <th>Preço (EUR)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planos as $plano): ?>
                    <tr>
                        <td><?= htmlspecialchars($plano['codigo']) ?></td>
                        <td><?= htmlspecialchars($plano['nome']) ?></td>
                        <td><?= htmlspecialchars($plano['duracao_meses']) ?> mes(es)</td>
                        <td><?= number_format($plano['preco'], 2) ?></td>
                        <td>
                            <?= $taxas !== null && isset($taxas['USD'])
                                ? number_format($plano['preco'] * $taxas['USD'], 2)
                                : '-' ?>
                        </td>
                        <td>
                            <?= $taxas !== null && isset($taxas['EUR'])
                                ? number_format($plano['preco'] * $taxas['EUR'], 2)
                                : '-' ?>
                        </td>
                        <td>
                            <a href="editar.php?id=<?= $plano['id'] ?>">Editar</a> |
                            <a href="eliminar.php?id=<?= $plano['id'] ?>" onclick="return confirm('Tens a certeza que queres eliminar este plano?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
