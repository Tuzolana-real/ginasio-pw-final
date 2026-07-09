<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/RelatorioController.php';
require_once __DIR__ . '/../../includes/PdfGenerator.php';

require_perfil([PERFIL_ADMIN]);

$controller = new RelatorioController();
$mes = trim($_GET['mes'] ?? '');
$dataInicio = trim($_GET['data_inicio'] ?? '');
$dataFim = trim($_GET['data_fim'] ?? '');
$clienteId = (int) ($_GET['cliente_id'] ?? 0);

if ($mes !== '') {
    $dataInicio = $mes . '-01';
    $dataFim = date('Y-m-t', strtotime($mes . '-01'));
}

if ($dataInicio !== '' && $dataFim === '') {
    $dataFim = date('Y-m-d');
}

if ($dataFim !== '' && $dataInicio === '') {
    $dataInicio = date('Y-m-01', strtotime($dataFim));
}

$clientes = $controller->listarClientes();
$clientesPorEstado = $controller->clientesPorEstado();
$receitaMensal = $controller->receitaMensal($dataInicio, $dataFim, $clienteId);
$frequenciaPorModalidade = $controller->frequenciaPorModalidade($dataInicio, $dataFim, $clienteId);

if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    $pdf = new PdfGenerator('Relatório de Ginásio');
    $pdf->addHeader('Relatório de Ginásio', 'Gerado a partir do painel administrativo');
    $pdf->addText('Data/hora de geração: ' . date('d/m/Y H:i:s'));
    $pdf->addLineBreak(2);

    $filtrosAplicados = [];
    if ($mes !== '') {
        $filtrosAplicados[] = 'Mês: ' . $mes;
    }
    if ($dataInicio !== '') {
        $filtrosAplicados[] = 'Início: ' . $dataInicio;
    }
    if ($dataFim !== '') {
        $filtrosAplicados[] = 'Fim: ' . $dataFim;
    }
    if ($clienteId > 0) {
        $clienteSelecionado = current(array_filter($clientes, fn($cliente) => (int) $cliente['id'] === $clienteId));
        $filtrosAplicados[] = 'Cliente: ' . ($clienteSelecionado['nome'] ?? 'Selecionado');
    }

    if (!empty($filtrosAplicados)) {
        $pdf->addText('Filtros aplicados: ' . implode(', ', $filtrosAplicados));
        $pdf->addLineBreak(3);
    }

    $totalReceita = array_sum(array_map(fn($linha) => (float) $linha['total'], $receitaMensal));

    $pdf->addSection('Resumo');
    $pdf->addText('Receita total no período: ' . number_format($totalReceita, 2, '.', '') . ' Kz');
    $pdf->addText('Número de registos de frequência: ' . array_sum(array_map(fn($linha) => (int) $linha['total'], $frequenciaPorModalidade)));
    $pdf->addLineBreak(3);
    $pdf->addText('Clientes por estado');
    $pdf->addTable(['Estado', 'Total'], array_map(function ($linha) {
        return [htmlspecialchars($linha['estado']), (string) $linha['total']];
    }, $clientesPorEstado));

    $pdf->addText('Receita mensal');
    $pdf->addTable(['Mês', 'Total (Kz)'], array_map(function ($linha) {
        return [htmlspecialchars($linha['mes']), number_format((float) $linha['total'], 2, '.', '')];
    }, $receitaMensal));

    $pdf->addText('Frequência por modalidade');
    $pdf->addTable(['Modalidade', 'Total'], array_map(function ($linha) {
        return [htmlspecialchars($linha['modalidade']), (string) $linha['total']];
    }, $frequenciaPorModalidade));

    $pdf->addLineBreak(3);
    $pdf->addText('Total geral: ' . number_format($totalReceita, 2, '.', '') . ' Kz', 12, true);

    $pdf->output('relatorio-ginasio.pdf');
    exit;
}

$pageTitle = 'Relatorios';
$flash = get_flash();

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="panel-grid">
        <article class="panel chart-box">
            <h2>Clientes ativos vs inativos</h2>
            <canvas id="clientesEstadoChart"></canvas>
        </article>

        <article class="panel chart-box">
            <h2>Receita mensal</h2>
            <canvas id="receitaMensalChart"></canvas>
        </article>
    </section>

    <section class="panel chart-box">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <h2>Frequencia por modalidade</h2>
            <a class="button" href="index.php?export=pdf<?= '&' . http_build_query(['mes' => $mes, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim, 'cliente_id' => $clienteId]) ?>">Exportar PDF</a>
        </div>
        <canvas id="frequenciaModalidadeChart"></canvas>
    </section>

    <section class="panel">
        <h3>Filtros</h3>
        <form method="GET" class="filter-form">
            <div class="form-field">
                <label for="mes">Mês</label>
                <input type="month" id="mes" name="mes" value="<?= htmlspecialchars($mes) ?>">
            </div>
            <div class="form-field">
                <label for="data_inicio">Data início</label>
                <input type="date" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($dataInicio) ?>">
            </div>
            <div class="form-field">
                <label for="data_fim">Data fim</label>
                <input type="date" id="data_fim" name="data_fim" value="<?= htmlspecialchars($dataFim) ?>">
            </div>
            <div class="form-field">
                <label for="cliente_id">Cliente</label>
                <select id="cliente_id" name="cliente_id">
                    <option value="0">Todos</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= (int) $cliente['id'] ?>" <?= $clienteId === (int) $cliente['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cliente['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="button" type="submit">Aplicar filtros</button>
            <a class="button secondary" href="index.php">Limpar</a>
        </form>
    </section>

    <section class="panel-grid">
        <article class="panel">
            <h3>Clientes por estado</h3>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr><th>Estado</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientesPorEstado as $linha): ?>
                            <tr>
                                <td><?= htmlspecialchars($linha['estado']) ?></td>
                                <td><?= htmlspecialchars($linha['total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <h3>Receita mensal</h3>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr><th>Mes</th><th>Total (Kz)</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receitaMensal as $linha): ?>
                            <tr>
                                <td><?= htmlspecialchars($linha['mes']) ?></td>
                                <td><?= number_format((float) $linha['total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <section class="panel">
        <div class="actions-row" style="justify-content: space-between; align-items: center;">
            <h3>Frequencia por modalidade</h3>
            <a class="button" href="index.php?export=pdf<?= '&' . http_build_query(['mes' => $mes, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim, 'cliente_id' => $clienteId]) ?>">Exportar PDF</a>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>Modalidade</th><th>Total</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($frequenciaPorModalidade as $linha): ?>
                        <tr>
                            <td><?= htmlspecialchars($linha['modalidade']) ?></td>
                            <td><?= htmlspecialchars($linha['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.relatorioData = {
    clientesEstado: {
        labels: <?= json_encode(array_column($clientesPorEstado, 'estado')) ?>,
        values: <?= json_encode(array_map('intval', array_column($clientesPorEstado, 'total'))) ?>
    },
    receitaMensal: {
        labels: <?= json_encode(array_reverse(array_column($receitaMensal, 'mes'))) ?>,
        values: <?= json_encode(array_reverse(array_map('floatval', array_column($receitaMensal, 'total')))) ?>
    },
    frequenciaModalidade: {
        labels: <?= json_encode(array_column($frequenciaPorModalidade, 'modalidade')) ?>,
        values: <?= json_encode(array_map('intval', array_column($frequenciaPorModalidade, 'total'))) ?>
    }
};

if (window.Chart) {
    new Chart(document.getElementById('clientesEstadoChart'), {
        type: 'doughnut',
        data: {
            labels: window.relatorioData.clientesEstado.labels,
            datasets: [{ data: window.relatorioData.clientesEstado.values, backgroundColor: ['#0f766e', '#b42318', '#64748b'] }]
        }
    });

    new Chart(document.getElementById('receitaMensalChart'), {
        type: 'bar',
        data: {
            labels: window.relatorioData.receitaMensal.labels,
            datasets: [{ label: 'Receita (Kz)', data: window.relatorioData.receitaMensal.values, backgroundColor: '#0f766e' }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('frequenciaModalidadeChart'), {
        type: 'bar',
        data: {
            labels: window.relatorioData.frequenciaModalidade.labels,
            datasets: [{ label: 'Entradas', data: window.relatorioData.frequenciaModalidade.values, backgroundColor: '#2563eb' }]
        },
        options: { indexAxis: 'y', scales: { x: { beginAtZero: true } } }
    });
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
