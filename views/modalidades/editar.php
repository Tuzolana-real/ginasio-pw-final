<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ModalidadeController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new ModalidadeController();
$modalidade = $controller->obter($_GET['id'] ?? null);

if (!$modalidade) {
    redirect('/ginasio-pw-final/views/modalidades/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/modalidades/editar.php');

    $dados = [
        'nome'      => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'categoria' => $_POST['categoria'],
        'instrutor' => $_POST['instrutor'],
        'vagas'     => $_POST['vagas'],
    ];

    $controller->atualizar($modalidade['id'], $dados);
    redirect('/ginasio-pw-final/views/modalidades/listar.php');
}

$pageTitle = 'Editar Modalidade';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Editar Modalidade</h2>
                <p class="muted-text">Atualize a modalidade selecionada.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($modalidade['nome']) ?>" required>
                </div>
                <div class="form-field">
                    <label for="categoria">Categoria</label>
                    <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($modalidade['categoria'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="instrutor">Instrutor</label>
                    <input type="text" id="instrutor" name="instrutor" value="<?= htmlspecialchars($modalidade['instrutor'] ?? '') ?>">
                </div>
                <div class="form-field">
                    <label for="vagas">Vagas</label>
                    <input type="number" id="vagas" name="vagas" min="0" value="<?= htmlspecialchars($modalidade['vagas'] ?? '0') ?>">
                </div>
            </div>

            <div class="form-field">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($modalidade['descricao'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar Alterações</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
