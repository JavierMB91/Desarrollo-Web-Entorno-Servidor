fetch('nav.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('nav').innerHTML = data;
    })
    .catch(err => console.error('Error cargando nav:', err));
