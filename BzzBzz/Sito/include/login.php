<?php


require_once '../include/dbHandler.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,//chiude e distrugge il coockie appena viene chiuso il browser
        'path'     => '/', //coockie valido in tutte le pagine interne al sito e non solo questa
        'httponly' => true,
        'samesite' => 'Strict', //mantiene il coockie solo nel sito corrente
    ]);
    session_start();
}

// Variabili lette dal loginForm.php tramite include
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

//Raccolta input (nomi campo in minuscolo come nel form)
$mail       = trim($_POST['mail']       ?? ''); //trim toglie gli spazi inutili
$passwordUt = trim($_POST['passwordUt'] ?? '');

if ($mail === '' || $passwordUt === '') {
    $error = 'Email e password sono obbligatorie.';
    return;
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $error = 'Formato email non valido.';
    return;
}

// Verifica credenziali
try {
    $pdo  = DBHandler::getConnection();
    $stmt = $pdo->prepare(
        'SELECT IDWasper, Nome, Cognome, Mail, PasswordUt, DataNascita, admin
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

    // Login riuscito: crea sessione e reindirizza 
    session_regenerate_id(true);

    $_SESSION['IDWasper']  = $wasper['IDWasper'];
    $_SESSION['nome']      = $wasper['Nome'];
    $_SESSION['cognome']   = $wasper['Cognome'];
    $_SESSION['mail']      = $wasper['Mail'];
    $_SESSION['dataNascita']      = $wasper['DataNascita'];
    $_SESSION['admin']     = $wasper['admin'] ? true : false;
    $_SESSION['loginTime'] = time();

    if ($_SESSION['admin']) {
        header('Location: ../Pagine/admin pages/adminHPage.php');
    } else {
        header('Location: ../Pagine/hPage.php');
    }
    exit;

} catch (PDOException $e) {
    error_log('[login] Errore DB: ' . $e->getMessage());
    $error = 'Errore interno del server. Riprova più tardi.';
}