<?php
require_once '../../include/DBHandler.php';
require_once '../../include/menuChoice.php';

$pdo = DBHandler::getConnection();

// Ottieni il totale degli utenti
$stmtUsers = $pdo->query("SELECT COUNT(IDWasper) FROM Wasper");
$totalUsers = $stmtUsers->fetchColumn();
$stmtRicambi = $pdo->query("SELECT COUNT(IDRicambio) FROM PezziRicambio");
$totalRicambi = $stmtRicambi->fetchColumn();
$stmtRad = $pdo->query("SELECT COUNT(NomeRaduno) FROM Raduno");
$totalRad = $stmtRad->fetchColumn();

?>
<title>Admin Dashboard - BzzBzz</title>

<div class="admin-container">
    <h1 class="admin-title">Dashboard di Amministrazione</h1>
    <p class="admin-subtitle">Panoramica del sistema e statistiche veloci</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-title">Utenti Registrati (Wasper)</div>
            <div class="card-value"><?= number_format((float)$totalUsers) ?></div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-title">Raduni Attivi</div>
            <div class="card-value"><?= number_format((float)$totalRad) ?></div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-title">Ricambi nel Database</div>
            <div class="card-value"><?= number_format((float)$totalRicambi) ?></div>
        </div>
    </div>
</div>

</body>
</html>
