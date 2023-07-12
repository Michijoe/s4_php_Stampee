
const button = document.querySelector('#grid-list');
const grid = document.querySelector('.grid');
const stampcards = document.querySelectorAll('.stampcard');
button.addEventListener('click', function () {
    grid.classList.toggle('stack');
    grid.classList.toggle('grid');
    stampcards.forEach(stampcard => {
        stampcard.classList.toggle('stampcard-list');
    });
});