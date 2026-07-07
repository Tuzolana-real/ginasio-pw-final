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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - Sistema de Ginásio</title>
</head>
<body>
    <h2>Editar Cliente</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required><br><br>

        <label>BI:</label><br>
        <input type="text" name="bi" value="<?= htmlspecialchars($cliente['bi'] ?? '') ?>"><br><br>

        <label>Data de nascimento:</label><br>
        <input type="date" name="data_nascimento" value="<?= htmlspecialchars($cliente['data_nascimento'] ?? '') ?>"><br><br>

        <label>Género:</label><br>
        <select name="genero">
            <option value="M" <?= $cliente['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
            <option value="F" <?= $cliente['genero'] === 'F' ? 'selected' : '' ?>>Feminino</option>
            <option value="Outro" <?= $cliente['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
        </select><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>"><br><br>

        <label>Endereço:</label><br>
        <input type="text" name="endereco" value="<?= htmlspecialchars($cliente['endereco'] ?? '') ?>"><br><br>

        <label>Estado:</label><br>
        <select name="estado">
            <option value="ativo" <?= $cliente['estado'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
            <option value="inativo" <?= $cliente['estado'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
        </select><br><br>

        <label>Foto atual:</label><br>
        <?php if (!empty($cliente['foto'])): ?>
            <img src="/ginasio-pw-final/assets/uploads/<?= htmlspecialchars($cliente['foto']) ?>" width="80"><br>
        <?php endif; ?>
        <label>Trocar foto (opcional):</label><br>
        <input type="file" name="foto" accept="image/jpeg,image/png"><br><br>

        <button type="submit">Guardar Alterações</button>
    </form>

    <p><a href="listar.php">Voltar à lista</a></p>
</body>
</html>