<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['IDWasper'])) {
    echo json_encode(['success' => false, 'message' => 'Non autenticato']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metodo non supportato']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['modello']) || !isset($input['checked'])) {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti']);
    exit;
}

require_once 'DBHandler.php';

$idWasper = $_SESSION['IDWasper'];
$modello = $input['modello'];
$checked = $input['checked'];

try {
    $db = DBHandler::getConnection();
    
    if ($checked) {
        $stmt = $db->prepare("INSERT IGNORE INTO vespeWasper (IDWasper, Modello) VALUES (:idWasper, :modello)");
        $stmt->execute(['idWasper' => $idWasper, 'modello' => $modello]);
    } else {
        $stmt = $db->prepare("DELETE FROM vespeWasper WHERE IDWasper = :idWasper AND Modello = :modello");
        $stmt->execute(['idWasper' => $idWasper, 'modello' => $modello]);
    }
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Errore del database']);
}
