<?php

// Sicurezza nel caso session_start non fosse scattato
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../include/DBHandler.php';

$userVespas = [];
$acquisti = [];
$totaleSpeso = 0;

if (isset($_SESSION['IDWasper'])) {
    try {
        $db = DBHandler::getConnection();
        
        // Fetch Vespas
        $stmt = $db->prepare("SELECT Modello FROM vespeWasper WHERE IDWasper = :id");
        $stmt->execute(['id' => $_SESSION['IDWasper']]);
        $userVespas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Fetch Acquisti
        $stmtAcquisti = $db->prepare("
            SELECT pr.IDRicambio, pr.Nome, pr.Prezzo, pr.Tipo, a.Numero 
            FROM Acquisti a
            JOIN PezziRicambio pr ON a.IDRicambio = pr.IDRicambio
            WHERE a.IDWasper = :id
        ");
        $stmtAcquisti->execute(['id' => $_SESSION['IDWasper']]);
        $acquisti = $stmtAcquisti->fetchAll(PDO::FETCH_ASSOC);

        // Calcola totale
        foreach ($acquisti as $item) {
            $totaleSpeso += ($item['Prezzo'] * $item['Numero']);
        }
    } catch (Exception $e) {
        $userVespas = [];
        $acquisti = [];
    }
}
?>
<link rel="stylesheet" href="../CSS/AreaP.css">

<div class="area-container">
    <div class="area-card">
        <div class="brand">
            <h1>Area Personale</h1>
            <p class="subtitle">Gestisci il tuo profilo, i tuoi veicoli e i tuoi acquisti</p>
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
                                <li>
                                    <span style="margin-right: 10px;"></span> <?= htmlspecialchars($modello) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <a href="catalogoVespa.php" class="btn btn-add"><?= empty($userVespas) ? 'Aggiungi Vespa' : 'Gestisci le tue Vespe' ?></a>
            </div>
        </div>

        <hr class="divider">

        <div class="purchases-section">
            <div class="section-label">I Tuoi Acquisti (Ricambi)</div>
            <div class="purchases-content">
                <?php if (empty($acquisti)): ?>
                    <p class="vehicles-empty">Non hai ancora acquistato nessun ricambio.</p>
                    <a href="ShopRicambi.php" class="btn btn-add">Vai allo Shop</a>
                <?php else: ?>
                    <div class="cart-list">
                        <?php foreach ($acquisti as $item): ?>
                            <div class="cart-item" data-id="<?= $item['IDRicambio'] ?>">
                                <div class="cart-item-info">
                                    <span class="item-tipo"><?= htmlspecialchars($item['Tipo']) ?></span>
                                    <span class="item-nome"><?= htmlspecialchars($item['Nome']) ?></span>
                                    <span class="item-prezzo">€ <?= number_format($item['Prezzo'], 2, ',', '.') ?></span>
                                </div>
                                <div class="cart-item-controls">
                                    <button class="btn-qty btn-minus">-</button>
                                    <span class="item-qty"><?= htmlspecialchars($item['Numero']) ?></span>
                                    <button class="btn-qty btn-plus">+</button>
                                </div>
                                <div class="cart-item-total">
                                    € <span class="item-subtot"><?= number_format($item['Prezzo'] * $item['Numero'], 2, ',', '.') ?></span>
                                </div>
                                <button class="btn-remove">X</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <div class="total-label">Totale Speso:</div>
                        <div class="total-amount">€ <span id="grand-total"><?= number_format($totaleSpeso, 2, ',', '.') ?></span></div>
                    </div>
                    
                    <a href="ShopRicambi.php" class="btn btn-add" style="margin-top: 1.5rem;">Continua lo Shopping</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const formatPrice = (price) => price.toLocaleString('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    
    // Ricalcola il totale totale guardando tutti i subtotali in pagina
    const updateGrandTotal = () => {
        let total = 0;
        document.querySelectorAll('.item-subtot').forEach(subEl => {
            // Rimuovi puntini delle migliaia e cambia virgola in punto
            const val = parseFloat(subEl.textContent.replace(/\./g, '').replace(',', '.'));
            if (!isNaN(val)) total += val;
        });
        document.getElementById('grand-total').textContent = formatPrice(total);
        
        // Se non ci sono più elementi, ricarica la pagina per mostrare lo stato vuoto
        if (document.querySelectorAll('.cart-item').length === 0) {
            window.location.reload();
        }
    };

    // Gestione update (plus/minus) o remove
    const sendAjaxRequest = (action, idRicambio, numero, itemElement) => {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('idRicambio', idRicambio);
        if (numero !== null) {
            formData.append('numero', numero);
        }

        fetch('../include/acquisti.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (action === 'remove' || numero <= 0) {
                    itemElement.remove();
                }
                updateGrandTotal();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante l\'aggiornamento.');
        });
    };

    // Aggiungi event listener ai bottoni
    document.querySelectorAll('.cart-item').forEach(item => {
        const idRicambio = item.getAttribute('data-id');
        const qtySpan = item.querySelector('.item-qty');
        const subtotSpan = item.querySelector('.item-subtot');
        
        // Estrai il prezzo unitario pulendo la stringa
        const priceStr = item.querySelector('.item-prezzo').textContent.replace('€', '').trim();
        const priceUnit = parseFloat(priceStr.replace(/\./g, '').replace(',', '.'));

        const updateItemUI = (newQty) => {
            qtySpan.textContent = newQty;
            subtotSpan.textContent = formatPrice(newQty * priceUnit);
        };

        item.querySelector('.btn-plus').addEventListener('click', () => {
            let currentQty = parseInt(qtySpan.textContent);
            currentQty++;
            updateItemUI(currentQty);
            sendAjaxRequest('update', idRicambio, currentQty, item);
        });

        item.querySelector('.btn-minus').addEventListener('click', () => {
            let currentQty = parseInt(qtySpan.textContent);
            currentQty--;
            if (currentQty <= 0) {
                if (confirm('Vuoi rimuovere questo articolo?')) {
                    sendAjaxRequest('remove', idRicambio, null, item);
                    // UI removed upon success
                }
            } else {
                updateItemUI(currentQty);
                sendAjaxRequest('update', idRicambio, currentQty, item);
            }
        });

        item.querySelector('.btn-remove').addEventListener('click', () => {
            if (confirm('Vuoi rimuovere questo articolo dagli acquisti?')) {
                sendAjaxRequest('remove', idRicambio, null, item);
                // UI removed upon success
            }
        });
    });
});
</script>

</body>
</html>