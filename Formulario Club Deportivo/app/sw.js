const CACHE_NAME = 'club-deportivo-cache-v2';
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
  self.skipWaiting(); // Fuerza al SW a activarse de inmediato
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Cache abierta');
        return cache.addAll(urlsToCache);
      })
  );
});

// Activa el SW y limpia cachés antiguas
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim(); // Toma el control de las páginas inmediatamente
});

// Intercepta las peticiones y sirve desde la caché si es posible
self.addEventListener('fetch', event => {
  // Estrategia Network First para navegación (HTML) para ver cambios de sesión
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match(event.request))
    );
  } else {
    // Estrategia Cache First para recursos estáticos (CSS, JS, Imágenes)
    event.respondWith(
      caches.match(event.request).then(response => response || fetch(event.request))
    );
  }
});