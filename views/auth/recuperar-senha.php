<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/auth/recuperar-senha.php');

    $email = sanitize_input($_POST['email']);
    $utilizadorModel = new Utilizador();
    $utilizador = $utilizadorModel->findByEmail($email);

    if ($utilizador) {
        $token = bin2hex(random_bytes(32));
        $expiraEm = date('Y-m-d H:i:s', strtotime('+1 hour'));

        if ($utilizadorModel->setResetToken($utilizador['id'], $token, $expiraEm)) {
            send_password_reset_email($utilizador['email'], $utilizador['nome'], $token);
        }
    }

    set_flash('sucesso', 'Se o email existir no sistema, foi enviado um link de recuperação.');
    redirect('/ginasio-pw-final/views/auth/recuperar-senha.php');
}

$flash = get_flash();
$pageTitle = 'Recuperar Senha';
$showSidebar = false;
$showTopbarActions = false;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="auth-shell">
    <section class="panel auth-card">
        <h2>Recuperar Senha</h2>
        <p class="muted-text">Indique o email associado à conta.</p>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="email">Email da tua conta</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Enviar instruções</button>
                <a class="button secondary" href="login.php">Voltar ao login</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>