<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ModalidadeController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new ModalidadeController();

if (!empty($_GET['pesquisa'])) {
    $modalidades = $controller->pesquisarPorCategoria($_GET['pesquisa']);
} else {
    $modalidades = $controller->listar();
}

$flash = get_flash();
$pageTitle = 'Modalidades';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Modalidades</h2>
                <p class="muted-text">Gerir as modalidades e a sua capacidade.</p>
            </div>
            <a class="button" href="criar.php">+ Nova Modalidade</a>
        </div>

        <form method="GET" class="filter-form">
            <div class="form-field">
                <label for="pesquisa">Pesquisar por categoria</label>
                <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar por categoria..." value="<?= htmlspecialchars($_GET['pesquisa'] ?? '') ?>">
            </div>
            <button class="button" type="submit">Pesquisar</button>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Instrutor</th>
                        <th>Vagas</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modalidades as $modalidade): ?>
                    <tr>
                        <td><?= htmlspecialchars($modalidade['nome']) ?></td>
                        <td><?= htmlspecialchars($modalidade['categoria'] ?? '') ?></td>
                        <td><?= htmlspecialchars($modalidade['instrutor'] ?? '') ?></td>
                        <td><?= htmlspecialchars($modalidade['vagas'] ?? '0') ?></td>
                        <td><?= htmlspecialchars($modalidade['descricao'] ?? '') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $modalidade['id'] ?>">Editar</a> |
                            <a href="eliminar.php?id=<?= $modalidade['id'] ?>" onclick="return confirm('Tens a certeza que queres eliminar esta modalidade?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
