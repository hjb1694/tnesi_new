const menuBtn = document.querySelector('.menu-btn');
const headerPart2 = document.querySelector('.header__part--2');

const toggleMenu = () => {
    menuBtn.classList.toggle('open');
    headerPart2.classList.toggle('open');
}

menuBtn.addEventListener('click', toggleMenu);