<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/RelatorioController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new RelatorioController();
$clientesPorEstado = $controller->clientesPorEstado();
$receitaMensal = $controller->receitaMensal();
$frequenciaPorModalidade = $controller->frequenciaPorModalidade();

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
        <h2>Frequencia por modalidade</h2>
        <canvas id="frequenciaModalidadeChart"></canvas>
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
        <h3>Frequencia por modalidade</h3>
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
