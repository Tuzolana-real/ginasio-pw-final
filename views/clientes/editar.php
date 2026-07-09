<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new ClienteController();
$cliente = $controller->obter($_GET['id']);

if (!$cliente) {
    redirect('/ginasio-pw-final/views/clientes/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/clientes/editar.php');

    $dados = [
        'nome'            => $_POST['nome'],
        'bi'              => $_POST['bi'],
        'data_nascimento' => $_POST['data_nascimento'],
        'genero'          => $_POST['genero'],
        'telefone'        => $_POST['telefone'],
        'email'           => $_POST['email'],
        'endereco'        => $_POST['endereco'],
        'estado'          => $_POST['estado'],
    ];

    $controller->atualizar($cliente['id'], $dados, $_FILES['foto'] ?? null);
    redirect('/ginasio-pw-final/views/clientes/listar.php');
}

$pageTitle = 'Editar Cliente';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Editar Cliente</h2>
                <p class="muted-text">Atualize os dados do cliente selecionado.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
                </div>
                <div class="form-field">
                    <label for="bi">BI</label>
                    <input type="text" id="bi" name="bi" value="<?= htmlspecialchars($cliente['bi'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($cliente['data_nascimento'] ?? '') ?>">
                </div>
                <div class="form-field">
                    <label for="genero">Género</label>
                    <select id="genero" name="genero">
                        <option value="M" <?= $cliente['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $cliente['genero'] === 'F' ? 'selected' : '' ?>>Feminino</option>
                        <option value="Outro" <?= $cliente['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>">
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-field">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco'] ?? '') ?>">
            </div>

            <div class="form-field">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="ativo" <?= $cliente['estado'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inativo" <?= $cliente['estado'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="form-field">
                <label>Foto atual</label>
                <?php if (!empty($cliente['foto'])): ?>
                    <img src="/ginasio-pw-final/assets/uploads/<?= htmlspecialchars($cliente['foto']) ?>" width="80" alt="Foto atual">
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="foto">Trocar foto (opcional)</label>
                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png">
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar Alterações</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>