<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new PagamentoController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/pagamentos/listar.php');