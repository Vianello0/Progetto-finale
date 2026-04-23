<?php
require_once __DIR__ . '/DBHandler.php';

// Sicurezza nel caso session_start non fosse scattato
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch all Vespas
$db = DBHandler::getConnection();
$stmt = $db->query("SELECT * FROM Vespa ORDER BY Modello ASC");
$vespas = $stmt->fetchAll();

// Fetch user's Vespas if logged in
$userVespas = [];
$isLoggedIn = isset($_SESSION['IDWasper']);

if ($isLoggedIn) {
    $stmtUser = $db->prepare("SELECT Modello FROM vespeWasper WHERE IDWasper = :id");
    $stmtUser->execute(['id' => $_SESSION['IDWasper']]);
    $owned = $stmtUser->fetchAll(PDO::FETCH_COLUMN);
    $userVespas = array_fill_keys($owned, true);
}
