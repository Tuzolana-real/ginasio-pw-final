<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/InscricaoController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new InscricaoController();

if (!empty($_GET['pesquisa_data'])) {
    $inscricoes = $controller->pesquisarPorData($_GET['pesquisa_data']);
} else {
    $inscricoes = $controller->listar();
}

$flash = get_flash();
$pageTitle = 'Inscrições';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Inscrições</h2>
                <p class="muted-text">Acompanhe as inscrições ativas e o seu estado.</p>
            </div>
            <a class="button" href="criar.php">+ Nova Inscrição</a>
        </div>

        <form method="GET" class="filter-form">
            <div class="form-field">
                <label for="pesquisa_data">Pesquisar por data de início</label>
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
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $inscricao): ?>
                    <tr>
                        <td><?= htmlspecialchars($inscricao['cliente_nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($inscricao['plano_nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($inscricao['data_inicio']) ?></td>
                        <td><?= htmlspecialchars($inscricao['data_fim']) ?></td>
                        <td><?= htmlspecialchars($inscricao['estado']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $inscricao['id'] ?>">Editar</a> |
                            <a href="eliminar.php?id=<?= $inscricao['id'] ?>" onclick="return confirm('Eliminar esta inscrição?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>