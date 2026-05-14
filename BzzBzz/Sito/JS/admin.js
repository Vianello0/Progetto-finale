// Aspetta che tutta la pagina HTML sia caricata prima di eseguire il codice
document.addEventListener('DOMContentLoaded', () => {
    // Seleziona tutti gli interruttori (checkbox) per la gestione privilegi admin
    const checkboxes = document.querySelectorAll('.admin-checkbox');
    // Trova il box "toast" (il fumetto delle notifiche) nascosto nell'HTML
    const toast = document.getElementById('toast');

    // Funzione per mostrare le notifiche (toast) a schermo
    function showToast(message, isSuccess = true) {
        toast.textContent = message; // Inserisce il testo del messaggio nel fumetto
        // Aggiunge le classi CSS per far apparire il toast e colorarlo di verde (success) o rosso (error)
        toast.className = 'toast show ' + (isSuccess ? 'success' : 'error');
        // Imposta un timer per rimuovere la classe 'show' (e quindi nasconderlo) dopo 3 secondi
        setTimeout(() => { toast.className = toast.className.replace('show', ''); }, 3000);
    }

    // Esegue questo codice per ogni interruttore presente in tabella
    checkboxes.forEach(cb => {
        // "Ascolta" quando lo stato dell'interruttore cambia (viene cliccato)
        cb.addEventListener('change', function() {
            // Recupera l'ID dell'utente dal tag HTML (es. data-id="5")
            const id = this.getAttribute('data-id');
            // Se l'interruttore è acceso imposta il valore a 1, altrimenti a 0
            const isAdmin = this.checked ? 1 : 0;
            // Si salva la posizione originale per poter tornare indietro se qualcosa va storto
            const originalState = !this.checked;

            // Disabilita il click per qualche millisecondo per evitare che l'utente faccia danni premendo mille volte
            this.disabled = true;

            // Crea un pacchetto di dati "fantasma" simulando l'invio di un form
            const formData = new FormData();
            formData.append('idWasper', id);
            formData.append('admin', isAdmin);

            // Effettua la richiesta (AJAX) dietro le quinte al server PHP
            fetch('../../include/toggleAdmin.php', {
                method: 'POST',
                body: formData // Inserisce il pacchetto di dati creato poco fa
            })
            // Trasforma la risposta del server in un oggetto JSON leggibile
            .then(res => res.json())
            .then(data => {
                // Se la modifica nel database è andata a buon fine
                if(data.success) {
                    // Riabilita l'interruttore per click futuri (nessun toast di successo mostrato)
                    this.disabled = false;
                } else {
                    // Se PHP segnala un errore (es. "Non puoi declassare te stesso")
                    showToast(data.message, false); // Mostra il toast rosso con l'errore
                    this.checked = originalState; // Fa scattare indietro l'interruttore
                    this.disabled = false; // Riabilita il click
                }
            })
            // Cattura errori di connessione gravi (es. cade internet o il server esplode)
            .catch(err => {
                console.error(err); // Stampa i dettagli tecnici nella console per te sviluppatore
                showToast('Errore di connessione', false); // Mostra un toast di errore
                this.checked = originalState; // Fa scattare indietro l'interruttore
                this.disabled = false; // Riabilita il click
            });
        });
    });
});
