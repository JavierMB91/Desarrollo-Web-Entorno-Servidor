const CACHE_NAME = 'club-deportivo-cache-v1';
const urlsToCache = [
  '/',
  '/index.php',
  '/css/estilos.css',
  '/js/footer.js',
  '/js/transiciones.js',
  '/nav.php'
  // Puedes añadir aquí más archivos importantes (imágenes, otros scripts, etc.)
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
        // Si el recurso está en la caché, lo devuelve. Si no, lo busca en la red.
        return response || fetch(event.request);
      })
  );
});