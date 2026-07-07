<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Cliente - Sistema de Ginásio</title>
</head>
<body>
    <h2>Novo Cliente</h2>

    <?php if ($flash): ?>
        <p style="color: red"><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>BI:</label><br>
        <input type="text" name="bi"><br><br>

        <label>Data de nascimento:</label><br>
        <input type="date" name="data_nascimento"><br><br>

        <label>Género:</label><br>
        <select name="genero">
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
            <option value="Outro">Outro</option>
        </select><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br><br>

        <label>Endereço:</label><br>
        <input type="text" name="endereco"><br><br>

        <label>Foto:</label><br>
        <input type="file" name="foto" accept="image/jpeg,image/png"><br><br>

        <button type="submit">Guardar</button>
    </form>

    <p><a href="listar.php">Voltar à lista</a></p>
</body>
</html>