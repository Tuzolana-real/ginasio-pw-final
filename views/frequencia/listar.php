<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/FrequenciaController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new FrequenciaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/frequencia/listar.php');

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
$pageTitle = 'Frequência';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <h2>Registar entrada</h2>
        <form method="POST" class="form-grid">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">-- Selecionar --</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="modalidade_id">Modalidade</label>
                    <select id="modalidade_id" name="modalidade_id">
                        <option value="">Sem modalidade</option>
                        <?php foreach ($modalidades as $modalidade): ?>
                            <option value="<?= $modalidade['id'] ?>"><?= htmlspecialchars($modalidade['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button class="button" type="submit" name="registrar_entrada">Registar entrada</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Histórico</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Modalidade</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($frequencias as $frequencia): ?>
                    <tr>
                        <td><?= htmlspecialchars($frequencia['cliente_nome']) ?></td>
                        <td><?= htmlspecialchars($frequencia['modalidade_nome'] ?? 'Sem modalidade') ?></td>
                        <td><?= htmlspecialchars($frequencia['data_hora_entrada']) ?></td>
                        <td><?= htmlspecialchars($frequencia['data_hora_saida'] ?? 'Aberta') ?></td>
                        <td>
                            <?php if (empty($frequencia['data_hora_saida'])): ?>
                                <form method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="frequencia_id" value="<?= $frequencia['id'] ?>">
                                    <button class="button secondary" type="submit" name="registrar_saida">Registar saída</button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
