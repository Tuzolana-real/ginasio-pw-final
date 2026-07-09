<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PlanoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/planos/criar.php');

    $dados = [
        'codigo'        => $_POST['codigo'],
        'nome'          => $_POST['nome'],
        'preco'         => $_POST['preco'],
        'duracao_meses' => $_POST['duracao_meses'],
        'descricao'     => $_POST['descricao'],
    ];

    $controller->criar($dados);
    redirect('/ginasio-pw-final/views/planos/listar.php');
}

$flash = get_flash();
$pageTitle = 'Novo Plano';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Novo Plano</h2>
                <p class="muted-text">Adicione um novo plano ao catálogo do ginásio.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo" required>
                </div>
                <div class="form-field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="preco">Preço (Kz)</label>
                    <input type="number" id="preco" step="0.01" name="preco" required>
                </div>
                <div class="form-field">
                    <label for="duracao_meses">Duração em meses</label>
                    <input type="number" id="duracao_meses" name="duracao_meses" min="1" required>
                </div>
            </div>

            <div class="form-field">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4"></textarea>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
