document.addEventListener('DOMContentLoaded', () => {
  const banner     = document.getElementById('cookie-banner');
  const acceptBtn  = document.getElementById('accept-all');
  const rejectBtn  = document.getElementById('reject-all');
  const privacyBtn = document.getElementById('privacy-center');

  // 1. Mostrar siempre, salvo que YA se haya aceptado
  if (localStorage.getItem('cookieConsent') !== 'all') {
    banner.classList.add('show');
  }

  // 2. Aceptar: guardamos y ocultamos para siempre
  acceptBtn.addEventListener('click', () => {
    localStorage.setItem('cookieConsent', 'all');
    banner.classList.remove('show');
    // Aquí arrancarías analytics o píxeles...
  });

  // 3. Rechazar: *solo* ocultamos en esta sesión, no guardamos nada
  rejectBtn.addEventListener('click', () => {
    banner.classList.remove('show');
    // No hacemos localStorage.setItem -> volverá a mostrarse la próxima carga
  });

  // 4. Centro de privacidad
  privacyBtn.addEventListener('click', () => {
    window.location.href = '<?= BASE_URL ?>cookies.php';
  });
});
// Botón para resetear elección
const resetBtn = document.getElementById('reset-cookies');
if (resetBtn) {
  resetBtn.addEventListener('click', e => {
    e.preventDefault();
    localStorage.removeItem('cookieConsent');
    // Forzamos recarga para que vuelva a mostrarse
    window.location.reload();
  });
}

