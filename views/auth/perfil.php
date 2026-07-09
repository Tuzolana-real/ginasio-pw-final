<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../models/Utilizador.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA, PERFIL_CLIENTE]);

$utilizadorModel = new Utilizador();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/auth/perfil.php');

    $nome  = sanitize_input($_POST['nome']);
    $email = sanitize_input($_POST['email']);

    if (empty($nome) || empty($email)) {
        set_flash('erro', 'Nome e email são obrigatórios.');
    } else {
        $utilizadorModel->updatePerfil($_SESSION['user_id'], $nome, $email);
        $_SESSION['user_nome'] = $nome; // atualizar também na sessão atual
        set_flash('sucesso', 'Perfil atualizado com sucesso.');
    }

    redirect('/ginasio-pw-final/views/auth/perfil.php');
}

$utilizador = $utilizadorModel->findById($_SESSION['user_id']);
$flash = get_flash();
$pageTitle = 'Meu Perfil';
$showSidebar = true;
$showTopbarActions = true;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="auth-shell">
    <section class="panel auth-card">
        <h2>Meu Perfil</h2>
        <p class="muted-text">Atualize os dados da sua conta.</p>

        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required>
            </div>

            <div class="form-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($utilizador['email']) ?>" required>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">Guardar Alterações</button>
                <a class="button secondary" href="alterar-senha.php">Trocar senha</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>