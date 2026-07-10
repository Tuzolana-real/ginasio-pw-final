<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$dados = [
    'nome'            => '',
    'bi'              => '',
    'data_nascimento' => '',
    'genero'          => 'M',
    'telefone'        => '',
    'email'           => '',
    'endereco'        => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/clientes/criar.php');

    $controller = new ClienteController();

    $dados = sanitize_input($_POST);

    if ($controller->criar($dados, $_FILES['foto'] ?? null)) {
        redirect('/ginasio-pw-final/views/clientes/listar.php');
    }
}

$flash = get_flash();
$pageTitle = 'Novo Cliente';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Novo Cliente</h2>
                <p class="muted-text">Registe um novo cliente no sistema.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($dados['nome']) ?>">
                </div>
                <div class="form-field">
                    <label for="bi">BI</label>
                    <input type="text" id="bi" name="bi" value="<?= htmlspecialchars($dados['bi']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($dados['data_nascimento']) ?>">
                </div>
                <div class="form-field">
                    <label for="genero">Género</label>
                    <select id="genero" name="genero">
                        <option value="M" <?= $dados['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $dados['genero'] === 'F' ? 'selected' : '' ?>>Feminino</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($dados['telefone']) ?>">
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>">
                </div>
            </div>

            <div class="form-field">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($dados['endereco']) ?>">
            </div>

            <div class="form-field">
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png">
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>