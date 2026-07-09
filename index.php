<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';

if (is_logged_in()) {
    redirect(get_home_redirect_path($_SESSION['user_role'] ?? null));
}

redirect('/ginasio-pw-final/views/auth/login.php');
