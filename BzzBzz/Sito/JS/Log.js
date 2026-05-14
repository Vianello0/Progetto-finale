// Funzione per mostrare/nascondere la password nella pagina di login
function togglePassword() {
    // Trova l'input della password e l'icona dell'occhio nell'HTML
    const input = document.getElementById('passwordUt');
    const icon  = document.getElementById('eye-icon');
    
    // Controlla se la password è attualmente nascosta (tipo 'password')
    const isHidden = input.type === 'password';

    // Se era nascosta, cambiala in 'text' per renderla visibile, altrimenti torna a 'password'
    input.type = isHidden ? 'text' : 'password';
    
    // Cambia anche il disegno dell'icona (occhio sbarrato vs occhio aperto) scambiando il codice SVG
    icon.innerHTML = isHidden
        ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.05 10.05 0 0 1 2.38-3.96M6.53 6.53A9.96 9.96 0 0 1 12 4.5c5 0 9.27 3.11 11 7.5a10.05 10.05 0 0 1-4.15 5.12M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>' // Occhio sbarrato
        : '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>'; // Occhio aperto
}
