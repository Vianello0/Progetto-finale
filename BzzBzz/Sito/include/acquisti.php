<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json'); //header per far capire al server che stiamo usando json

// Assicurati che l'utente sia loggato
if (!isset($_SESSION['IDWasper'])) {
    echo json_encode(['success' => false, 'message' => 'Devi effettuare il login per aggiungere ricambi agli acquisti.']);
    exit;
}

require_once 'DBHandler.php';

$action = $_POST['action'] ?? ''; //serve per capire cosa fare (aggiungere, rimuovere...)
$idRicambio = $_POST['idRicambio'] ?? 0; //serve per capire quale ricambio modificare/rimuovere
$idWasper = $_SESSION['IDWasper']; //serve per capire chi sta modificando/rimuovendo

$db = DBHandler::getConnection();

try {
    switch ($action) {
        case 'add':
            // Controlla se il ricambio è già negli acquisti
            $stmt = $db->prepare("SELECT Numero FROM Acquisti WHERE IDWasper = :idWasper AND IDRicambio = :idRicambio");
            $stmt->execute([':idWasper' => $idWasper, ':idRicambio' => $idRicambio]);
            $row = $stmt->fetch();

            if ($row) {
                // Se c'è già, aumenta la quantità
                $newNum = ($row['Numero'] ?? 1) + 1;
                $update = $db->prepare("UPDATE Acquisti SET Numero = :num WHERE IDWasper = :idWasper AND IDRicambio = :idRicambio");
                $update->execute([':num' => $newNum, ':idWasper' => $idWasper, ':idRicambio' => $idRicambio]);
                echo json_encode(['success' => true, 'message' => 'Quantità aggiornata', 'nuovaQuantita' => $newNum]);
            } else {
                // Altrimenti, aggiungilo con quantità 1
                $insert = $db->prepare("INSERT INTO Acquisti (IDWasper, IDRicambio, Numero) VALUES (:idWasper, :idRicambio, 1)");
                $insert->execute([':idWasper' => $idWasper, ':idRicambio' => $idRicambio]);
                echo json_encode(['success' => true, 'message' => 'Aggiunto agli acquisti']);
            }
            break;

        case 'update':
            $newNum = $_POST['numero'] ?? 1;
            if ($newNum > 0) {
                $update = $db->prepare("UPDATE Acquisti SET Numero = :num WHERE IDWasper = :idWasper AND IDRicambio = :idRicambio");
                $update->execute([':num' => $newNum, ':idWasper' => $idWasper, ':idRicambio' => $idRicambio]);
                echo json_encode(['success' => true, 'message' => 'Quantità aggiornata']);
            } else {
                // Se la quantità è 0 o meno, rimuovi
                $delete = $db->prepare("DELETE FROM Acquisti WHERE IDWasper = :idWasper AND IDRicambio = :idRicambio");
                $delete->execute([':idWasper' => $idWasper, ':idRicambio' => $idRicambio]);
                echo json_encode(['success' => true, 'message' => 'Rimosso dagli acquisti']);
            }
            break;

        case 'remove':
            $delete = $db->prepare("DELETE FROM Acquisti WHERE IDWasper = :idWasper AND IDRicambio = :idRicambio");
            $delete->execute([':idWasper' => $idWasper, ':idRicambio' => $idRicambio]);
            echo json_encode(['success' => true, 'message' => 'Rimosso dagli acquisti']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Azione non valida']);
            break;
    }
} catch (PDOException $e) {
    error_log("[Acquisti] Errore DB: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Errore del server']);
}
?>
