// Attendiamo che tutto il contenuto HTML (il DOM) sia caricato prima di eseguire lo script
document.addEventListener('DOMContentLoaded', () => {
    // Selezioniamo tutti i bottoni che hanno la classe '.btn-buy' e l'attributo 'data-id'
    const buyButtons = document.querySelectorAll('.btn-buy[data-id]');
    
    buyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Recuperiamo l'ID del bottone cliccato
            const idRicambio = this.getAttribute('data-id');
            // Salviamo il testo originale per poterlo ripristinare dopo
            const originalText = this.textContent;
            
            // Disabilitiamo il bottone per evitare click multipli involontari
            this.disabled = true;
            
            // Prepariamo i dati da inviare al server (come se fosse un form HTML)
            const formData = new FormData();
            formData.append('action', 'add'); // Azione che vogliamo eseguire
            formData.append('idRicambio', idRicambio); // L'ID del ricambio da aggiungere
            
            // Effettuiamo una richiesta asincrona (AJAX) al file PHP che gestisce gli acquisti
            fetch('../include/acquisti.php', {
                method: 'POST', // Usiamo il metodo POST
                body: formData  // Includiamo i dati preparati precedentemente
            })
            // Quando il server risponde, convertiamo la risposta in formato JSON
            .then(response => response.json())
            // Gestiamo i dati JSON ricevuti dal server
            .then(data => {
                // Se il server ci conferma che l'operazione ha avuto successo
                if (data.success) {
                    // Cambiamo il testo per mostrare il successo dell'operazione
                    this.textContent = 'Aggiunto 👍';
                    // Cambiamo lo stile per evidenziare il successo (usando i colori del CSS)
                    this.style.backgroundColor = 'var(--neon)';
                    this.style.color = '#0d0d0f';
                    
                    // Impostiamo un timer per ripristinare il bottone allo stato originale dopo 2 secondi (2000 ms)
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.style.backgroundColor = 'transparent';
                        this.style.color = 'var(--neon)';
                        this.disabled = false;
                    }, 2000);
                } else {
                    // Se c'è stato un errore logico (es. prodotto non disponibile), mostriamo un avviso
                    alert(data.message);
                    // Ripristiniamo il bottone
                    this.textContent = originalText;
                    this.disabled = false;
                }
            })
            // Gestiamo eventuali errori di rete o di connessione
            .catch(error => {
                console.error('Error:', error); // Log dell'errore nella console per il debug
                alert('Errore di connessione.'); // Avvisiamo l'utente
                // Ripristiniamo il bottone anche in caso di errore
                this.textContent = originalText;
                this.disabled = false;
            });
        });
    });
});
