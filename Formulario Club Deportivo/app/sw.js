const CACHE_NAME = 'club-deportivo-cache-v1';
const urlsToCache = [
  './',
  'index.php',
  'socios.php',
  'servicios.php',
  'citas.php',
  'css/estilos.css',
  'js/footer.js',
  'js/transiciones.js',
  'nav.php'
];

// Instala el Service Worker y cachea los archivos principales
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Cache abierta');
        return cache.addAll(urlsToCache);
      })
  );
});

// Intercepta las peticiones y sirve desde la caché si es posible
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});