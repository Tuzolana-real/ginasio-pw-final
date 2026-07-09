<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/InscricaoController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new InscricaoController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/inscricoes/listar.php');