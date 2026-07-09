<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= $flash['type'] === 'erro' ? 'error' : 'success' ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>
