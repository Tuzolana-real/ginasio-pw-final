<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new ClienteController();

if (!empty($_GET['pesquisa'])) {
    $clientes = $controller->pesquisar($_GET['pesquisa']);
} else {
    $clientes = $controller->listar();
}

$flash = get_flash();
$pageTitle = 'Clientes';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Clientes</h2>
                <p class="muted-text">Gestão da base de clientes do ginásio.</p>
            </div>
            <a class="button" href="criar.php">+ Novo Cliente</a>
        </div>

        <form method="GET" class="filter-form">
            <div class="form-field">
                <label for="pesquisa">Pesquisar por nome</label>
                <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar por nome..." value="<?= htmlspecialchars($_GET['pesquisa'] ?? '') ?>">
            </div>
            <button class="button" type="submit">Pesquisar</button>
        </form>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td>
                            <?php if (!empty($cliente['foto'])): ?>
                                <img src="/ginasio-pw-final/assets/uploads/<?= htmlspecialchars($cliente['foto']) ?>" width="50" alt="Foto de <?= htmlspecialchars($cliente['nome']) ?>">
                            <?php else: ?>
                                Sem foto
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($cliente['nome']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($cliente['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($cliente['estado']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $cliente['id'] ?>">Editar</a> |
                            <a href="eliminar.php?id=<?= $cliente['id'] ?>" onclick="return confirm('Tens a certeza que queres eliminar este cliente?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>