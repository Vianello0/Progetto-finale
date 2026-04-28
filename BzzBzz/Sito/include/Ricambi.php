<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = DBHandler::getConnection();

// Fetch types for filter
$stmtTipi = $db->query("SELECT DISTINCT Tipo FROM PezziRicambio ORDER BY Tipo ASC");
$tipi = $stmtTipi->fetchAll(PDO::FETCH_COLUMN);

// Fetch models for filter
$stmtModelli = $db->query("SELECT Modello FROM Vespa ORDER BY Modello ASC");
$modelli = $stmtModelli->fetchAll(PDO::FETCH_COLUMN);

// Retrieve GET filters
$selectedTipo = $_GET['tipo'] ?? '';
$selectedOrdine = $_GET['ordine'] ?? '';
$selectedModelli = $_GET['modelli'] ?? [];

// Base query for spare parts
$query = "SELECT DISTINCT pr.* FROM PezziRicambio pr ";
$params = [];
$whereClauses = [];

// Join RicambioVespa if models are selected
if (!empty($selectedModelli)) {
    $query .= " JOIN RicambioVespa rv ON pr.IDRicambio = rv.IDRicambio ";
    
    // Create placeholders for the IN clause
    $inPlaceholders = [];
    foreach ($selectedModelli as $index => $modello) {
        $paramName = "modello_" . $index;
        $inPlaceholders[] = ":" . $paramName;
        $params[$paramName] = $modello;
    }
    
    if (!empty($inPlaceholders)) {
        $whereClauses[] = "rv.Modello IN (" . implode(", ", $inPlaceholders) . ")";
    }
}

if (!empty($selectedTipo)) {
    $whereClauses[] = "pr.Tipo = :tipo";
    $params['tipo'] = $selectedTipo;
}

if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

if ($selectedOrdine === 'asc') {
    $query .= " ORDER BY pr.Prezzo ASC";
} elseif ($selectedOrdine === 'desc') {
    $query .= " ORDER BY pr.Prezzo DESC";
} else {
    $query .= " ORDER BY pr.Nome ASC";
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$ricambi = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
