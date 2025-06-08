document.addEventListener('DOMContentLoaded', function() {
  // Verificar si hay una preferencia de tema guardada
  const savedTheme = localStorage.getItem('theme') || 'light';
  
  // Aplicar el tema guardado
  applyTheme(savedTheme);
  
  // Opcional si tienes un selector de tema
  const themeToggle = document.getElementById('theme-toggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', function() {
      const currentTheme = document.body.classList.contains('dark-theme') ? 'light' : 'dark';
      applyTheme(currentTheme);
      localStorage.setItem('theme', currentTheme);
    });
  }
});

function applyTheme(theme) {
  if (theme === 'dark') {
    document.body.classList.add('dark-theme');
  } else {
    document.body.classList.remove('dark-theme');
    // Asegurarse que el tema claro está activo
    const themeLink = document.getElementById('theme-style');
    if (!themeLink) {
      const link = document.createElement('link');
      link.id = 'theme-style';
      link.rel = 'stylesheet';
      link.href = 'css/tema_clar.css';
      document.head.appendChild(link);
    }
  }
}