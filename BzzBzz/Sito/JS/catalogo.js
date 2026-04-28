document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.vespa-toggle');
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const modello = this.getAttribute('data-modello');
            const isChecked = this.checked;
            const textSpan = this.closest('.vespa-actions').querySelector('.action-text');
            
            fetch('../include/toggleVespa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    modello: modello,
                    checked: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    textSpan.textContent = isChecked ? 'Nel tuo garage' : 'Aggiungi al garage';
                } else {
                    alert('Errore: ' + (data.message || 'Si è verificato un errore'));
                    this.checked = !isChecked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore di rete. Riprova.');
                this.checked = !isChecked;
            });
        });
    });
});
