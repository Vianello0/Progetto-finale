<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Distrugge tutti i dati di sessione
$_SESSION = [];
session_destroy();

// Elimina il cookie di sessione dal browser
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path']);
}

// Reindirizza alla home o al login
header('Location: ../Pagine/hPage.php');
exit;