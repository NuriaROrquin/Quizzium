// Obtener todos los elementos del acordeón
const accordionItems = document.querySelectorAll('.accordion-item');

// Agregar el evento click a cada encabezado del acordeón
accordionItems.forEach(item => {
    const header = item.querySelector('.accordion-header');
    header.addEventListener('click', () => {

        item.classList.toggle('active');
    });
});
