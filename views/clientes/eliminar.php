<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

if (!is_logged_in()) {
    redirect('/ginasio-pw-final/views/auth/login.php');
}

$controller = new ClienteController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/clientes/listar.php');