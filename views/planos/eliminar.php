<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/PlanoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new PlanoController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/planos/listar.php');
