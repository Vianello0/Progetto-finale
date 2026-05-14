<?php
require_once '../include/menuChoice.php';

$message = "";

try {
    $db = DBHandler::getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iscriviti'])) {
        $nomeRaduno = $_POST['nomeRaduno'];
        $privacy = isset($_POST['privacy']) ? 1 : 0;
        $idWasper = $_SESSION['IDWasper'];

        // check if already registered
        $stmt = $db->prepare("SELECT * FROM Partecipanti WHERE NomeRaduno = :nome AND IDWasper = :id");
        $stmt->execute(['nome' => $nomeRaduno, 'id' => $idWasper]);
        if ($stmt->fetch()) {
            $message = "<div class='alert error'>Sei già iscritto a questo raduno!</div>";
        } else {
            $stmt = $db->prepare("INSERT INTO Partecipanti (NomeRaduno, IDWasper, Privacy) VALUES (:nome, :id, :privacy)");
            $stmt->execute(['nome' => $nomeRaduno, 'id' => $idWasper, 'privacy' => $privacy]);
            $message = "<div class='alert success'>Iscrizione confermata con successo!</div>";
        }
    }

    // fetch all raduni
    $stmt = $db->query("SELECT * FROM Raduno ORDER BY DataRaduno ASC");
    $raduni = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $message = "<div class='alert error'>Errore nel caricamento dei dati.</div>";
    $raduni = [];
}
?>
<title>Iscrizione Raduni</title>
<link rel="stylesheet" href="../CSS/Raduni.css">

<div class="raduni-container">
    <div class="raduni-main-card">
        <div class="brand">
            <h1>Iscrizione Raduni</h1>
            <p class="subtitle">Partecipa ai nostri eventi e unisciti alla community</p>
        </div>
        
        <?= $message ?>
        
        <?php if (empty($raduni)): ?>
            <p class="empty-message">Al momento non ci sono raduni in programma.</p>
        <?php else: ?>
            <div class="raduni-list">
                <?php foreach ($raduni as $raduno): ?>
                    <div class="raduno-card">
                        <h2><?= htmlspecialchars($raduno['NomeRaduno']) ?></h2>
                        <div class="raduno-info">
                            <p><strong>Data:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($raduno['DataRaduno']))) ?></p>
                            <p><strong>Luogo:</strong> <?= htmlspecialchars($raduno['Luogo']) ?></p>
                        </div>
                        <p class="raduno-desc"><?= nl2br(htmlspecialchars($raduno['Descrizione'])) ?></p>
                        
                        <form method="POST" action="" class="raduno-form">
                            <input type="hidden" name="nomeRaduno" value="<?= htmlspecialchars($raduno['NomeRaduno']) ?>">
                            <label class="privacy-label">
                                <input type="checkbox" name="privacy" required>
                                <span class="privacy-text">Accetto la privacy policy e acconsento al trattamento dei miei dati personali per la partecipazione a questo raduno.</span>
                            </label>
                            <button type="submit" name="iscriviti" class="btn">Conferma Iscrizione</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
