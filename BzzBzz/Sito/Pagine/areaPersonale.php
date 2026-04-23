<?php
// Includiamo l'header che fa anche partire la sessione
include_once '../header_footer/HeaderUser.php';

// Sicurezza nel caso session_start non fosse scattato
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../include/DBHandler.php';

$userVespas = [];
if (isset($_SESSION['IDWasper'])) {
    try {
        $db = DBHandler::getConnection();
        $stmt = $db->prepare("SELECT Modello FROM vespeWasper WHERE IDWasper = :id");
        $stmt->execute(['id' => $_SESSION['IDWasper']]);
        $userVespas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {
        $userVespas = [];
    }
}
?>
<link rel="stylesheet" href="../CSS/AreaP.css">

<div class="area-container">
    <div class="area-card">
        <div class="brand">
            <h1>Area Personale</h1>
            <p class="subtitle">Gestisci il tuo profilo e i tuoi veicoli</p>
        </div>

        <div class="profile-section">
            <div class="section-label">Dati Personali</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nome</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['nome'] ?? 'N/D') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Cognome</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['cognome'] ?? 'N/D') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['mail'] ?? 'N/D') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Data di Nascita</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['dataNascita'] ?? 'N/D') ?></span>
                </div>
            </div>
        </div>

        <hr class="divider">

        <div class="vehicles-section">
            <div class="section-label">Le Tue Vespe</div>
            <div class="vehicles-content">
                <?php if (empty($userVespas)): ?>
                    <p class="vehicles-empty">Non hai ancora aggiunto nessuna Vespa.</p>
                <?php else: ?>
                    <div class="user-vespas-list" style="margin-bottom: 20px; text-align: left;">
                        <ul style="list-style-type: none; padding: 0;">
                            <?php foreach ($userVespas as $modello): ?>
                                <li style="background: #f8f9fa; margin-bottom: 10px; padding: 12px 15px; border-radius: 8px; border-left: 4px solid #333; font-weight: 500; font-size: 1.1rem; display: flex; align-items: center;">
                                    <span style="margin-right: 10px;">🛵</span> <?= htmlspecialchars($modello) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <a href="catalogoVespa.php" class="btn btn-add"><?= empty($userVespas) ? 'Aggiungi Vespa' : 'Gestisci le tue Vespe' ?></a>
            </div>
        </div>
    </div>
</div>

</body>
</html>