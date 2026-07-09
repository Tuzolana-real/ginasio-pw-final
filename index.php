<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';

if (is_logged_in()) {
    redirect('/ginasio-pw-final/views/dashboard/index.php');
}

redirect('/ginasio-pw-final/views/auth/login.php');
