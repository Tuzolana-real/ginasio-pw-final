<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA, PERFIL_CLIENTE]);

$utilizadorModel = new Utilizador();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/auth/alterar-senha.php');

    $senhaAtual = $_POST['senha_atual'];
    $novaSenha  = $_POST['nova_senha'];
    $confirmar  = $_POST['confirmar_senha'];

    $utilizador = $utilizadorModel->findById($_SESSION['user_id']);

    if (!password_verify($senhaAtual, $utilizador['senha'])) {
        set_flash('erro', 'A senha atual está incorreta.');
    } elseif ($novaSenha !== $confirmar) {
        set_flash('erro', 'A nova senha e a confirmação não coincidem.');
    } elseif (strlen($novaSenha) < 6) {
        set_flash('erro', 'A nova senha deve ter pelo menos 6 caracteres.');
    } else {
        $utilizadorModel->updateSenha($_SESSION['user_id'], password_hash($novaSenha, PASSWORD_DEFAULT));
        set_flash('sucesso', 'Senha alterada com sucesso.');
    }

    redirect('/ginasio-pw-final/views/auth/alterar-senha.php');
}

$flash = get_flash();
$pageTitle = 'Alterar Senha';
$showSidebar = true;
$showTopbarActions = true;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="auth-shell">
    <section class="panel auth-card">
        <h2>Alterar Senha</h2>
        <p class="muted-text">Atualize a senha da sua conta.</p>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="senha_atual">Senha atual</label>
                <input type="password" id="senha_atual" name="senha_atual" required>
            </div>

            <div class="form-field">
                <label for="nova_senha">Nova senha</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>

            <div class="form-field">
                <label for="confirmar_senha">Confirmar nova senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Alterar Senha</button>
                <a class="button secondary" href="perfil.php">Voltar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>