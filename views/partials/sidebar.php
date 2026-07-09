<?php
$baseUrl = '/ginasio-pw-final';
$menuItems = [
    ['label' => 'Dashboard', 'href' => $baseUrl . '/views/dashboard/index.php'],
    ['label' => 'Clientes', 'href' => $baseUrl . '/views/clientes/listar.php'],
    ['label' => 'Inscricoes', 'href' => $baseUrl . '/views/inscricoes/listar.php'],
    ['label' => 'Pagamentos', 'href' => $baseUrl . '/views/pagamentos/listar.php'],
    ['label' => 'Planos', 'href' => $baseUrl . '/views/planos/listar.php'],
    ['label' => 'Modalidades', 'href' => $baseUrl . '/views/modalidades/listar.php'],
    ['label' => 'Frequencia', 'href' => $baseUrl . '/views/frequencia/listar.php'],
    ['label' => 'Relatorios', 'href' => $baseUrl . '/views/relatorios/index.php'],
];
?>
<aside class="app-sidebar" id="appSidebar">
    <a class="brand" href="<?= $baseUrl ?>/views/dashboard/index.php">
        <span class="brand-mark">SG</span>
        <span>
            <strong>Ginasio</strong>
            <small>Gestao interna</small>
        </span>
    </a>

    <nav class="nav-list" aria-label="Menu principal">
        <?php foreach ($menuItems as $item): ?>
            <a href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
        <?php endforeach; ?>
    </nav>
</aside>
