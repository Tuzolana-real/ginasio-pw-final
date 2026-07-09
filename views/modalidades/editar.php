<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ModalidadeController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new ModalidadeController();
$modalidade = $controller->obter($_GET['id'] ?? null);

if (!$modalidade) {
    redirect('/ginasio-pw-final/views/modalidades/listar.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome'      => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'categoria' => $_POST['categoria'],
        'instrutor' => $_POST['instrutor'],
        'vagas'     => $_POST['vagas'],
    ];

    $controller->atualizar($modalidade['id'], $dados);
    redirect('/ginasio-pw-final/views/modalidades/listar.php');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Modalidade - Sistema de Ginasio</title>
</head>
<body>
    <h2>Editar Modalidade</h2>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($modalidade['nome']) ?>" required><br><br>

        <label>Categoria:</label><br>
        <input type="text" name="categoria" value="<?= htmlspecialchars($modalidade['categoria'] ?? '') ?>"><br><br>

        <label>Instrutor:</label><br>
        <input type="text" name="instrutor" value="<?= htmlspecialchars($modalidade['instrutor'] ?? '') ?>"><br><br>

        <label>Vagas:</label><br>
        <input type="number" name="vagas" min="0" value="<?= htmlspecialchars($modalidade['vagas'] ?? '0') ?>"><br><br>

        <label>Descricao:</label><br>
        <textarea name="descricao" rows="4" cols="40"><?= htmlspecialchars($modalidade['descricao'] ?? '') ?></textarea><br><br>

        <button type="submit">Guardar Alteracoes</button>
    </form>

    <p><a href="listar.php">Voltar a lista</a></p>
</body>
</html>
