<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Non autorizzato']);
    exit;
}//in caso l'utente non sia admin acceda lo stesso a questo file

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida']);
    exit;
}

$idWasper = $_POST['idWasper'] ?? null;
$isAdmin = isset($_POST['admin']) && $_POST['admin'] == '1' ? 1 : 0;
//se risulta 1 viene promosso ad admin, se 0 viene declassato a utente

if (!$idWasper) {
    echo json_encode(['success' => false, 'message' => 'ID Utente mancante']);
    exit;
}

if ($idWasper == $_SESSION['IDWasper']) {
    echo json_encode(['success' => false, 'message' => 'Non puoi modificare i tuoi stessi privilegi']);
    exit;
}

require_once 'DBHandler.php';

try {
    $pdo = DBHandler::getConnection();
    $stmt = $pdo->prepare("UPDATE Wasper SET admin = :admin WHERE IDWasper = :id");
    $stmt->execute([':admin' => $isAdmin, ':id' => $idWasper]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore interno del server']);
}
