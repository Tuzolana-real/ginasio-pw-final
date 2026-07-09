<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/InscricaoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new InscricaoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token('/ginasio-pw-final/views/inscricoes/criar.php');

    $dados = [
        'cliente_id'    => $_POST['cliente_id'],
        'plano_id'      => $_POST['plano_id'],
        'modalidade_id' => $_POST['modalidade_id'] ?? null,
        'data_inicio'   => $_POST['data_inicio'],
        'estado'        => 'ativa',
    ];

    $controller->criar($dados);
    redirect('/ginasio-pw-final/views/inscricoes/listar.php');
}

$clientes = $controller->listarClientes();
$planos = $controller->listarPlanos();
$modalidades = $controller->listarModalidades();
$flash = get_flash();
$pageTitle = 'Nova Inscrição';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <div>
                <h2>Nova Inscrição</h2>
                <p class="muted-text">Crie uma nova inscrição para um cliente.</p>
            </div>
            <a class="button secondary" href="listar.php">Voltar à lista</a>
        </div>

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
                    <label for="plano_id">Plano</label>
                    <select id="plano_id" name="plano_id" required>
                        <option value="">-- Selecionar --</option>
                        <?php foreach ($planos as $plano): ?>
                            <option value="<?= $plano['id'] ?>">
                                <?= htmlspecialchars($plano['nome']) ?> (<?= $plano['duracao_meses'] ?> mes(es) - <?= number_format($plano['preco'], 2) ?> Kz)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="modalidade_id">Modalidade</label>
                    <select id="modalidade_id" name="modalidade_id">
                        <option value="">Sem modalidade</option>
                        <?php foreach ($modalidades as $modalidade): ?>
                            <option value="<?= $modalidade['id'] ?>"><?= htmlspecialchars($modalidade['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="data_inicio">Data de início</label>
                    <input type="date" id="data_inicio" name="data_inicio" required>
                </div>
            </div>

            <p class="muted-text"><em>A data de fim é calculada automaticamente com base na duração do plano.</em></p>

            <div class="form-actions">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="listar.php">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
