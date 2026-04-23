<?php
// include/login.php — Logica di autenticazione, da includere in loginForm.php

declare(strict_types=1);

require_once __DIR__ . '/../include/dbHandler.php';

// Avvio sessione (senza 'secure' per localhost HTTP)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Variabili lette dal loginForm.php tramite include
$error   = '';
$success = '';

/* ─────────────────────────────────────────────
   Elaborazione solo se il form è stato inviato
───────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // GET: pagina aperta normalmente, non fare nulla
    return;
}

// ── Raccolta input (nomi campo in minuscolo come nel form) ──
$mail       = trim($_POST['mail']       ?? '');
$passwordUt = trim($_POST['passwordUt'] ?? '');

// ── Validazione base ──
if ($mail === '' || $passwordUt === '') {
    $error = 'Email e password sono obbligatorie.';
    return;
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $error = 'Formato email non valido.';
    return;
}

// ── Verifica credenziali ──
try {
    $pdo  = DBHandler::getConnection();
    $stmt = $pdo->prepare(
        'SELECT IDWasper, Nome, Cognome, Mail, PasswordUt, DataNascita
         FROM Wasper
         WHERE Mail = :mail
         LIMIT 1'
    );
    $stmt->execute([':mail' => $mail]);
    $wasper = $stmt->fetch();

    if (!$wasper || !password_verify($passwordUt, $wasper['PasswordUt'])) {
        $error = 'Email o password non corretti.';
        return;
    }

    // ── Login riuscito: crea sessione e reindirizza ──
    session_regenerate_id(true);

    $_SESSION['IDWasper']  = $wasper['IDWasper'];
    $_SESSION['nome']      = $wasper['Nome'];
    $_SESSION['cognome']   = $wasper['Cognome'];
    $_SESSION['mail']      = $wasper['Mail'];
    $_SESSION['dataNascita']      = $wasper['DataNascita'];
    $_SESSION['loginTime'] = time();

    header('Location: ../Pagine/hPage.php');
    exit;

} catch (PDOException $e) {
    error_log('[login] Errore DB: ' . $e->getMessage());
    $error = 'Errore interno del server. Riprova più tardi.';
}