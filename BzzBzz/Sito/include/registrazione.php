<?php
// registrazione.php — Gestione registrazione Wasper + Residenza

declare(strict_types=1);

require_once 'dbHandler.php';

header('Content-Type: application/json; charset=utf-8');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Metodo non consentito.']));
}

$campi = ['nome', 'cognome', 'mail', 'dataNascita', 'passwordUt', 'via', 'cap', 'citta', 'provincia'];

$dati = [];
foreach ($campi as $campo) {
    $valore = trim($_POST[$campo] ?? '');
    if ($valore === '') {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => "Il campo «{$campo}» è obbligatorio."]));
    }
    $dati[$campo] = $valore;
}

// Validazione email (max 30 caratteri)
if (!filter_var($dati['mail'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Indirizzo email non valido.']));
}
if (mb_strlen($dati['mail']) > 30) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Email troppo lunga (max 30 caratteri).']));
}

// Validazione lunghezze
$limiti = [
    'nome'      => 15,
    'cognome'   => 20,
    'via'       => 30,
    'provincia' => 20,
];
foreach ($limiti as $campo => $max) {
    if (mb_strlen($dati[$campo]) > $max) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => "Il campo «{$campo}» supera i {$max} caratteri consentiti."]));
    }
}

// Validazione data di nascita
if (!DateTimeImmutable::createFromFormat('Y-m-d', $dati['dataNascita'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Formato data non valido (atteso: YYYY-MM-DD).']));
}

// Validazione CAP (solo numeri, max 5 cifre)
if (!preg_match('/^\d{1,5}$/', $dati['cap'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'CAP non valido (massimo 5 cifre numeriche).']));
}

// Hashing della password con bcrypt
// NOTA: richiede PasswordUt VARCHAR(60) — vedi schema_bzzbzz.sql
$passwordHash = password_hash($dati['passwordUt'], PASSWORD_BCRYPT);


try {
    $pdo = DBHandler::getConnection();
    $pdo->beginTransaction();

    // ── 3a. Controlla se la mail è già presente ──
    $stmtCheck = $pdo->prepare('SELECT IDWasper FROM Wasper WHERE Mail = :mail LIMIT 1');
    $stmtCheck->execute([':mail' => $dati['mail']]);
    if ($stmtCheck->fetch()) {
        $pdo->rollBack();
        http_response_code(409);
        die(json_encode(['success' => false, 'message' => 'Questa email è già registrata.']));
    }

    // ── 3b. Inserimento in Wasper ──
    $sqlWasper = '
        INSERT INTO Wasper (Nome, Cognome, Mail, DataNascita, PasswordUt)
        VALUES (:nome, :cognome, :mail, :dataNascita, :passwordUt)
    ';
    $stmtWasper = $pdo->prepare($sqlWasper);
    $stmtWasper->execute([
        ':nome'        => $dati['nome'],
        ':cognome'     => $dati['cognome'],
        ':mail'        => $dati['mail'],
        ':dataNascita' => $dati['dataNascita'],
        ':passwordUt'  => $passwordHash,
    ]);

    $idWasper = (int) $pdo->lastInsertId(); // IDWasper auto_increment

    // ── 3c. Inserimento in Residenza con l'IDWasper appena creato ──
    $sqlResidenza = '
        INSERT INTO Residenza (IDWasper, Via, CAP, Città, Provincia)
        VALUES (:idWasper, :via, :cap, :citta, :provincia)
    ';
    $stmtResidenza = $pdo->prepare($sqlResidenza);
    $stmtResidenza->execute([
        ':idWasper'  => $idWasper,
        ':via'       => $dati['via'],
        ':cap'       => (int) $dati['cap'],
        ':citta'     => $dati['citta'],
        ':provincia' => $dati['provincia'],
    ]);

    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        'success'  => true,
        'message'  => 'Registrazione completata con successo.',
        'IDWasper' => $idWasper,
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('[registrazione] Errore DB: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione.']);
}