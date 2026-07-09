<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ClienteController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new ClienteController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/clientes/listar.php');