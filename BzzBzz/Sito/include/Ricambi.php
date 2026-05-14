<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../include/DBHandler.php');

$db = DBHandler::getConnection();

// Fetch types per filtro (distinct per non ripetere i tipi)
$stmtTipi = $db->query("SELECT DISTINCT Tipo FROM PezziRicambio ORDER BY Tipo ASC");
$tipi = $stmtTipi->fetchAll(PDO::FETCH_COLUMN);

// Fetch models per filtro
$stmtModelli = $db->query("SELECT Modello FROM Vespa ORDER BY Modello ASC");
$modelli = $stmtModelli->fetchAll(PDO::FETCH_COLUMN);

//prendi i filtri
$selectedTipo = $_GET['tipo'] ?? '';
$selectedOrdine = $_GET['ordine'] ?? '';
$selectedModelli = $_GET['modelli'] ?? [];

//mostra un solo risultato, selezionando tutte le colonne (pr è un alias di PezziRicambio.NomeColonna)
$query = "SELECT DISTINCT pr.* FROM PezziRicambio pr ";
$params = [];
$whereClauses = [];

// Join RicambioVespa se i modelli sono selezionati
if (!empty($selectedModelli)) {
    $query .= " JOIN RicambioVespa rv ON pr.IDRicambio = rv.IDRicambio ";
    
    // placeholder per la clausola IN
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
    $query .= " WHERE " . implode(" AND ", $whereClauses); //.= unisce a una stringa
    // implode unisce gli elementi di un array in una stringa aggiungendoci AND
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
