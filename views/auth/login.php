<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

if (is_logged_in()) {
    redirect('/ginasio-pw-final/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/auth/login.php');

    $auth = new AuthController();
    $lembrar = isset($_POST['lembrar']);

    if ($auth->login($_POST['email'], $_POST['senha'], $lembrar)) {
        redirect('/ginasio-pw-final/index.php');
    } else {
        redirect('/ginasio-pw-final/views/auth/login.php');
    }
}

$flash = get_flash();
$pageTitle = 'Login';
$showSidebar = false;
$showTopbarActions = false;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="auth-shell">
    <section class="panel auth-card">
        <h2>Login</h2>
        <p class="muted-text">Entre para aceder ao painel do ginásio.</p>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-field">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <label class="form-field" style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="lembrar"> Lembrar-me
            </label>

            <div class="form-actions">
                <button class="button" type="submit">Entrar</button>
            </div>
        </form>

        <div class="auth-link-row">
            <a href="recuperar-senha.php">Esqueci a minha senha</a>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>