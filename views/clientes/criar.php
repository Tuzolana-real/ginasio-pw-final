<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/clientes/criar.php');

    $controller = new ClienteController();

    $dados = [
        'nome'            => $_POST['nome'],
        'bi'              => $_POST['bi'],
        'data_nascimento' => $_POST['data_nascimento'],
        'genero'          => $_POST['genero'],
        'telefone'        => $_POST['telefone'],
        'email'           => $_POST['email'],
        'endereco'        => $_POST['endereco'],
    ];

    $controller->criar($dados, $_FILES['foto'] ?? null);
    redirect('/ginasio-pw-final/views/clientes/listar.php');
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
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-field">
                    <label for="bi">BI</label>
                    <input type="text" id="bi" name="bi">
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento">
                </div>
                <div class="form-field">
                    <label for="genero">Género</label>
                    <select id="genero" name="genero">
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone">
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>

            <div class="form-field">
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco">
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