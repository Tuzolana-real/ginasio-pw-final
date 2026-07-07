<?php
session_start();
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../includes/helpers.php';

$auth = new AuthController();
$auth->logout();

set_flash('sucesso', 'Sessão terminada com sucesso.');
redirect('/ginasio-pw-final/views/auth/login.php');