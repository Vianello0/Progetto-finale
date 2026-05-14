<?php
require_once '../../include/menuChoice.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crea'])) {
    try {
        $db = DBHandler::getConnection();
        $nome = trim($_POST['nomeRaduno']);
        $descrizione = trim($_POST['descrizione']);
        $data = $_POST['dataRaduno'];
        $luogo = trim($_POST['luogo']);

        $stmt = $db->prepare("INSERT INTO Raduno (NomeRaduno, Descrizione, DataRaduno, Luogo) VALUES (:nome, :descrizione, :data, :luogo)");
        $stmt->execute([
            'nome' => $nome,
            'descrizione' => $descrizione,
            'data' => $data,
            'luogo' => $luogo
        ]);
        $message = "<div class='admin-alert success'>Raduno '" . htmlspecialchars($nome) . "' creato con successo!</div>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "<div class='admin-alert error'>Errore: Un raduno con questo nome esiste già.</div>";
        } else {
            $message = "<div class='admin-alert error'>Errore durante la creazione del raduno.</div>";
            error_log($e->getMessage());
        }
    }
}
?>
<title>Admin - Crea Raduni</title>

<div class="admin-container">
    <h1 class="admin-title">Crea Nuovo Raduno</h1>
    <p class="admin-subtitle">Compila il form per aggiungere un nuovo raduno al sistema.</p>
    
    <?= $message ?>
    
    <div class="admin-form-container">
        <form method="POST" action="">
            <div class="admin-form-group">
                <label for="nomeRaduno">Nome Raduno</label>
                <input type="text" id="nomeRaduno" name="nomeRaduno" class="admin-input" required>
            </div>
            
            <div class="admin-form-group">
                <label for="dataRaduno">Data</label>
                <input type="date" id="dataRaduno" name="dataRaduno" class="admin-input" required>
            </div>
            
            <div class="admin-form-group">
                <label for="luogo">Luogo</label>
                <input type="text" id="luogo" name="luogo" class="admin-input" required>
            </div>

            <div class="admin-form-group">
                <label for="descrizione">Descrizione</label>
                <textarea id="descrizione" name="descrizione" rows="4" class="admin-input" required></textarea>
            </div>
            
            <button type="submit" name="crea" class="admin-btn">Crea Raduno</button>
        </form>
    </div>
</div>
</body>
</html>
