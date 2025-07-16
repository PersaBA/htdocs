document.addEventListener('DOMContentLoaded', () => {
  const toggles = document.querySelectorAll('.admin-menu .toggle');

  toggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
      const submenu = toggle.nextElementSibling;

      // Cierra otros submenús abiertos
      document.querySelectorAll('.admin-menu .submenu.open').forEach(openMenu => {
        if (openMenu !== submenu) {
          openMenu.classList.remove('open');
        }
      });

      submenu.classList.toggle('open');
    });
  });
});
