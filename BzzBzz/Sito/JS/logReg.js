
// Logica specifica per la pagina di Registrazione
// Prima cerca se nella pagina attuale esiste un form con id 'regForm'
const regForm = document.getElementById('regForm');

// L'if serve perché questo file è incluso anche nel login, dove 'regForm' non esiste (evita errori in console)
if (regForm) {
    // Se esiste, "ascolta" l'evento di invio (submit) del form
    regForm.addEventListener('submit', async function(e) {
        // Blocca il comportamento di base del browser (che vorrebbe ricaricare la pagina per inviare i dati)
        e.preventDefault(); 
        
        // Raccoglie tutti i dati inseriti nei vari campi del form automaticamente
        const formData = new FormData(this);
        // Trova il div vuoto usato per mostrare gli errori o i messaggi di successo
        const alertContainer = document.getElementById('alert-container');
        
        try {
            // Effettua la chiamata (fetch) al file PHP che gestisce la registrazione (in background)
            const response = await fetch('../include/registrazione.php', {
                method: 'POST', // Usa POST per nascondere i dati sensibili come la password
                body: formData  // Inserisce i dati raccolti dal form
            });
            
            // Attende la risposta del server e la converte in JSON usabile da Javascript
            const result = await response.json();
            
            // Se la richiesta è fallita a livello HTTP (es. errore 400 Bad Request) o il PHP ha restituito success: false
            if (!response.ok || !result.success) {
                // Inserisce dinamicamente nel box un messaggio di errore rosso con l'icona
                alertContainer.innerHTML = `<div class="alert alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;margin-top:1px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    ${result.message || 'Errore durante la registrazione.'}
                </div>`;
                // Fa scorrere automaticamente la pagina verso l'alto per far leggere l'errore all'utente
                window.scrollTo(0, 0);
            } else {
                // Se invece è andato tutto bene (codice 201 Created e success: true)
                // Inserisce un messaggio verde di successo
                alertContainer.innerHTML = `<div class="alert alert-success">${result.message} Ti stiamo reindirizzando al login...</div>`;
                window.scrollTo(0, 0); // Scorre in alto
                
                // Imposta un timer: aspetta 2 secondi (2000 ms) e poi trasporta l'utente alla pagina di login in automatico
                setTimeout(() => {
                    window.location.href = 'loginForm.php';
                }, 2000);
            }
        } catch (err) {
            // Se c'è un errore grave (es. server spento, impossibile fare fetch)
            alertContainer.innerHTML = `<div class="alert alert-error">Errore di connessione. Riprova.</div>`;
            window.scrollTo(0, 0);
        }
    });
}
