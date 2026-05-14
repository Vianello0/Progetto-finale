<?php
require_once '../../include/menuChoice.php';

try {
    $db = DBHandler::getConnection();

    // Query per ottenere le iscrizioni ai raduni con i dettagli degli utenti
    $stmt = $db->query("
        SELECT p.NomeRaduno, p.Privacy, w.IDWasper, w.Nome, w.Cognome, w.Mail
        FROM Partecipanti p
        JOIN Wasper w ON p.IDWasper = w.IDWasper
        ORDER BY p.NomeRaduno ASC, w.Cognome ASC
    ");
    $iscrizioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group them by Raduno for better visualization
    $raduniGroups = [];
    foreach ($iscrizioni as $row) {
        $raduniGroups[$row['NomeRaduno']][] = $row;
    }

} catch (Exception $e) {
    $raduniGroups = [];
    $error = "Errore nel caricamento dei dati delle iscrizioni.";
}
?>
<title>Admin - Iscrizioni Raduni</title>

<div class="admin-container">
    <h1 class="admin-title">Iscrizioni ai Raduni</h1>
    <p class="admin-subtitle">Visualizza gli utenti iscritti ai vari raduni organizzati.</p>

    <?php if (isset($error)): ?>
        <div class="admin-alert error"><?= htmlspecialchars($error) ?></div>
    <?php elseif (empty($raduniGroups)): ?>
        <p style="text-align: center; margin-top: 30px; color: #888;">Al momento non ci sono iscritti ai raduni.</p>
    <?php else: ?>
        <?php foreach ($raduniGroups as $nomeRaduno => $partecipanti): ?>
            <div class="raduno-group">
                <h2 class="raduno-group-title">
                    <span>Raduno: <?= htmlspecialchars($nomeRaduno) ?></span>
                    <span class="raduno-badge">
                        Totale: <?= count($partecipanti) ?> iscritti
                    </span>
                </h2>
                
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID Utente</th>
                                <th>Nome</th>
                                <th>Cognome</th>
                                <th>Email</th>
                                <th style="text-align: center;">Privacy Accettata</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($partecipanti as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['IDWasper']) ?></td>
                                    <td><?= htmlspecialchars($p['Nome']) ?></td>
                                    <td><?= htmlspecialchars($p['Cognome']) ?></td>
                                    <td><?= htmlspecialchars($p['Mail']) ?></td>
                                    <td style="text-align: center;">
                                        <?= $p['Privacy'] ? '<span style="color: #2ecc71; font-weight: bold;">Sì</span>' : '<span style="color: #e74c3c; font-weight: bold;">No</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
