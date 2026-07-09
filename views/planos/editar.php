<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PlanoController();
$plano = $controller->obter($_GET['id'] ?? null);

if (!$plano) {
    redirect('/ginasio-pw-final/views/planos/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/planos/editar.php');

    $dados = [
        'codigo'        => $_POST['codigo'],
        'nome'          => $_POST['nome'],
        'preco'         => $_POST['preco'],
        'duracao_meses' => $_POST['duracao_meses'],
        'descricao'     => $_POST['descricao'],
    ];

    $controller->atualizar($plano['id'], $dados);
    redirect('/ginasio-pw-final/views/planos/listar.php');
}

$pageTitle = 'Editar Plano';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Editar Plano</h2>
                <p class="muted-text">Atualize os dados do plano selecionado.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo" value="<?= htmlspecialchars($plano['codigo']) ?>" required>
                </div>
                <div class="form-field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($plano['nome']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="preco">Preço (Kz)</label>
                    <input type="number" id="preco" step="0.01" name="preco" value="<?= htmlspecialchars($plano['preco']) ?>" required>
                </div>
                <div class="form-field">
                    <label for="duracao_meses">Duração em meses</label>
                    <input type="number" id="duracao_meses" name="duracao_meses" min="1" value="<?= htmlspecialchars($plano['duracao_meses']) ?>" required>
                </div>
            </div>

            <div class="form-field">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($plano['descricao'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar Alterações</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
