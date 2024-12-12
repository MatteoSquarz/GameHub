const menuToggle = document.getElementById('mobile-menu');
const content = document.getElementById('content');

function toggleMenu() {
    content.classList.toggle('hidden');
}


menuToggle.addEventListener('click', toggleMenu);
