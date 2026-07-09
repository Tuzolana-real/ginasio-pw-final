<?php
session_start();
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../controllers/ModalidadeController.php';

require_perfil([PERFIL_ADMIN]);

$controller = new ModalidadeController();
$controller->eliminar($_GET['id']);
redirect('/ginasio-pw-final/views/modalidades/listar.php');
