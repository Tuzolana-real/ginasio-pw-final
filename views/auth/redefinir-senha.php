<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

$token = $_GET['token'] ?? '';
$utilizadorModel = new Utilizador();
$utilizador = $utilizadorModel->findByResetToken($token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/auth/redefinir-senha.php?token=' . urlencode($token));

    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if ($novaSenha !== $confirmarSenha) {
        set_flash('erro', 'As senhas não coincidem.');
        redirect('/ginasio-pw-final/views/auth/redefinir-senha.php?token=' . urlencode($token));
    }

    if (!$utilizador) {
        set_flash('erro', 'O link de recuperação é inválido ou expirou.');
        redirect('/ginasio-pw-final/views/auth/login.php');
    }

    $utilizadorModel->updateSenha($utilizador['id'], password_hash($novaSenha, PASSWORD_DEFAULT));
    $utilizadorModel->clearResetToken($utilizador['id']);
    set_flash('sucesso', 'A palavra-passe foi redefinida com sucesso.');
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$flash = get_flash();
$pageTitle = 'Redefinir Senha';
$showSidebar = false;
$showTopbarActions = false;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="auth-shell">
    <section class="panel auth-card">
        <h2>Redefinir Senha</h2>
        <p class="muted-text">Escolha uma nova palavra-passe para a sua conta.</p>

        <?php if (!$utilizador): ?>
            <div class="alert alert-error">O link de recuperação é inválido ou expirou.</div>
        <?php else: ?>
            <form method="POST" class="form-grid">
                <?= csrf_field() ?>
                <div class="form-field">
                    <label for="nova_senha">Nova palavra-passe</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                </div>

                <div class="form-field">
                    <label for="confirmar_senha">Confirmar palavra-passe</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>

                <div class="form-actions">
                    <button class="button" type="submit">Guardar nova senha</button>
                    <a class="button secondary" href="login.php">Cancelar</a>
                </div>
            </form>
        <?php endif; ?>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>