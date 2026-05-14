// Aspetta che tutta la pagina HTML sia caricata prima di eseguire il codice
document.addEventListener('DOMContentLoaded', () => {
    // Seleziona tutti gli interruttori delle Vespe sparsi per il catalogo
    const checkboxes = document.querySelectorAll('.vespa-toggle');
    
    // Per ogni interruttore trovato nella pagina...
    checkboxes.forEach(cb => {
        // ...esegui questa funzione ogni volta che viene acceso o spento
        cb.addEventListener('change', function() {
            // Recupera il nome del modello associato al bottone (es. "Vespa 50 Special")
            const modello = this.getAttribute('data-modello');
            // isChecked sarà 'true' se l'hai appena acceso, 'false' se l'hai spento
            const isChecked = this.checked;
            // Cerca il testo descrittivo che sta a fianco del bottone ("Aggiungi al garage" / "Nel tuo garage")
            const textSpan = this.closest('.vespa-actions').querySelector('.action-text');
            
            // Avvia una comunicazione (fetch) con il server senza ricaricare la pagina
            fetch('../include/toggleVespa.php', {
                method: 'POST', // Metodo di invio
                headers: {
                    // Specifica che stiamo per inviare un pacchetto formattato in JSON
                    'Content-Type': 'application/json',
                },
                // Converte le variabili javascript in una stringa JSON prima di inviarle al server
                body: JSON.stringify({
                    modello: modello,
                    checked: isChecked
                })
            })
            // Riceve la risposta di PHP e la converte da stringa a vero oggetto JSON
            .then(response => response.json())
            .then(data => {
                // Se la query di inserimento/rimozione nel database è andata a buon fine
                if (data.success) {
                    // Modifica in tempo reale il testo del bottone usando l'operatore ternario
                    textSpan.textContent = isChecked ? 'Nel tuo garage' : 'Aggiungi al garage';
                } else {
                    // Se l'operazione fallisce (es. sessione scaduta, utente non loggato), mostra l'errore
                    alert('Errore: ' + (data.message || 'Si è verificato un errore'));
                    // Annulla il movimento del bottone riportandolo a com'era prima del click
                    this.checked = !isChecked;
                }
            })
            // Gestione dei problemi di connessione (internet down o server offline)
            .catch(error => {
                console.error('Error:', error); // Log nascosto per debug
                alert('Errore di rete. Riprova.'); // Avviso popup per l'utente
                this.checked = !isChecked; // Annulla il click
            });
        });
    });
});
