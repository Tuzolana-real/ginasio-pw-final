<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PagamentoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PagamentoController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/pagamentos/listar.php');