<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/FrequenciaController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new FrequenciaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registrar_entrada'])) {
        $controller->registrarEntrada([
            'cliente_id' => $_POST['cliente_id'],
            'modalidade_id' => $_POST['modalidade_id'] ?? null,
        ]);
    }

    if (isset($_POST['registrar_saida'])) {
        $controller->registrarSaida($_POST['frequencia_id']);
    }

    redirect('/ginasio-pw-final/views/frequencia/listar.php');
}

$clientes = $controller->listarClientes();
$modalidades = $controller->listarModalidades();
$frequencias = $controller->listar();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Frequencia - Sistema de Ginasio</title>
</head>
<body>
    <h2>Frequencia</h2>

    <?php if ($flash): ?>
        <p style="color: <?= $flash['type'] === 'erro' ? 'red' : 'green' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </p>
    <?php endif; ?>

    <h3>Registar entrada</h3>
    <form method="POST">
        <label>Cliente:</label><br>
        <select name="cliente_id" required>
            <option value="">-- Selecionar --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Modalidade:</label><br>
        <select name="modalidade_id">
            <option value="">Sem modalidade</option>
            <?php foreach ($modalidades as $modalidade): ?>
                <option value="<?= $modalidade['id'] ?>"><?= htmlspecialchars($modalidade['nome']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit" name="registrar_entrada">Registar entrada</button>
    </form>

    <h3>Historico</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Cliente</th>
            <th>Modalidade</th>
            <th>Entrada</th>
            <th>Saida</th>
            <th>Acao</th>
        </tr>
        <?php foreach ($frequencias as $frequencia): ?>
        <tr>
            <td><?= htmlspecialchars($frequencia['cliente_nome']) ?></td>
            <td><?= htmlspecialchars($frequencia['modalidade_nome'] ?? 'Sem modalidade') ?></td>
            <td><?= htmlspecialchars($frequencia['data_hora_entrada']) ?></td>
            <td><?= htmlspecialchars($frequencia['data_hora_saida'] ?? 'Aberta') ?></td>
            <td>
                <?php if (empty($frequencia['data_hora_saida'])): ?>
                    <form method="POST">
                        <input type="hidden" name="frequencia_id" value="<?= $frequencia['id'] ?>">
                        <button type="submit" name="registrar_saida">Registar saida</button>
                    </form>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
