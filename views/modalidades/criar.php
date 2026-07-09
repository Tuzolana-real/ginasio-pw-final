<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ModalidadeController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new ModalidadeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome'      => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'categoria' => $_POST['categoria'],
        'instrutor' => $_POST['instrutor'],
        'vagas'     => $_POST['vagas'],
    ];

    $controller->criar($dados);
    redirect('/ginasio-pw-final/views/modalidades/listar.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Modalidade - Sistema de Ginasio</title>
</head>
<body>
    <h2>Nova Modalidade</h2>

    <?php if ($flash): ?>
        <p style="color: red"><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Categoria:</label><br>
        <input type="text" name="categoria"><br><br>

        <label>Instrutor:</label><br>
        <input type="text" name="instrutor"><br><br>

        <label>Vagas:</label><br>
        <input type="number" name="vagas" min="0" value="0"><br><br>

        <label>Descricao:</label><br>
        <textarea name="descricao" rows="4" cols="40"></textarea><br><br>

        <button type="submit">Guardar</button>
    </form>

    <p><a href="listar.php">Voltar a lista</a></p>
</body>
</html>
