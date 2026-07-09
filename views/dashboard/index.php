<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/RelatorioController.php';

require_perfil([PERFIL_ADMIN, PERFIL_RECEPCIONISTA]);

$controller = new RelatorioController();
$resumo = $controller->resumoDashboard();
$inscricoesRecentes = $controller->inscricoesRecentes();
$pagamentosRecentes = $controller->pagamentosRecentes();
$flash = get_flash();
$pageTitle = 'Dashboard';

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/alerts.php';
?>

<div class="page-grid">
    <section class="stats-grid" aria-label="Resumo do sistema">
        <article class="stat-card">
            <span>Clientes registados</span>
            <strong><?= (int) $resumo['clientes'] ?></strong>
        </article>
        <article class="stat-card">
            <span>Clientes ativos</span>
            <strong><?= (int) $resumo['clientes_ativos'] ?></strong>
        </article>
        <article class="stat-card">
            <span>Inscricoes ativas</span>
            <strong><?= (int) $resumo['inscricoes_ativas'] ?></strong>
        </article>
        <article class="stat-card">
            <span>Entradas hoje</span>
            <strong><?= (int) $resumo['entradas_hoje'] ?></strong>
        </article>
    </section>

    <section class="panel-grid">
        <article class="panel">
            <h2>Financeiro</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <span>Receita do mes</span>
                    <strong><?= number_format((float) $resumo['receita_mes'], 2) ?> Kz</strong>
                </div>
                <div class="stat-card">
                    <span>Pagamentos pendentes</span>
                    <strong><?= (int) $resumo['pagamentos_pendentes'] ?></strong>
                </div>
            </div>
        </article>

        <article class="panel">
            <h2>Acoes rapidas</h2>
            <div class="actions-row">
                <a class="button" href="/ginasio-pw-final/views/clientes/criar.php">Novo cliente</a>
                <a class="button" href="/ginasio-pw-final/views/inscricoes/criar.php">Nova inscricao</a>
                <a class="button" href="/ginasio-pw-final/views/pagamentos/criar.php">Novo pagamento</a>
                <a class="button" href="/ginasio-pw-final/views/frequencia/listar.php">Frequencia</a>
            </div>
        </article>
    </section>

    <section class="panel-grid">
        <article class="panel">
            <h2>Inscricoes recentes</h2>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Plano</th>
                            <th>Periodo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscricoesRecentes as $inscricao): ?>
                            <tr>
                                <td><?= htmlspecialchars($inscricao['cliente_nome']) ?></td>
                                <td><?= htmlspecialchars($inscricao['plano_nome']) ?></td>
                                <td><?= htmlspecialchars($inscricao['data_inicio']) ?> a <?= htmlspecialchars($inscricao['data_fim']) ?></td>
                                <td><span class="badge"><?= htmlspecialchars($inscricao['estado']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <h2>Pagamentos recentes</h2>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Plano</th>
                            <th>Valor</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagamentosRecentes as $pagamento): ?>
                            <tr>
                                <td><?= htmlspecialchars($pagamento['cliente_nome']) ?></td>
                                <td><?= htmlspecialchars($pagamento['plano_nome']) ?></td>
                                <td><?= number_format((float) $pagamento['valor'], 2) ?> Kz</td>
                                <td><span class="badge"><?= htmlspecialchars($pagamento['estado']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
