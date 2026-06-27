const btn = document.getElementById('darkModeBtn');
const dbTime = document.getElementById('dbTime');

const now = new Date();
const hours = now.getHours();
const minutes = now.getMinutes();
let minutesFormatted;

if (minutes < 10) {
    minutesFormatted = '0' + minutes;
} else {
    minutesFormatted = minutes;
}

dbTime.textContent = 'Database geladen om: ' + hours + ':' + minutesFormatted;
const body = document.body;

// Default to dark mode on first visit
if (localStorage.getItem('darkMode') === null) {
    localStorage.setItem('darkMode', 'enabled');
}

if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    btn.textContent = 'Light Mode';
}

btn.addEventListener('click', function () {
    body.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
        btn.textContent = 'Light Mode';
    } else {
        localStorage.setItem('darkMode', 'disabled');
        btn.textContent = 'Dark Mode';
    }
});
