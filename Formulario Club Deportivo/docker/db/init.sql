CREATE TABLE IF NOT EXISTS usuario
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad INT (2),
    clave VARCHAR(255) NOT NULL,
    rol ENUM('socio', 'administrador') NOT NULL DEFAULT 'socio',
    telefono VARCHAR(20) UNIQUE,
    foto VARCHAR(100), 
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO usuario 
    (nombre, clave, rol, telefono, foto) 
VALUES
    ('Juan Perez', '12345', 'administrador', '600123456', 'uploads/usuarios/juan_perez.jpg'),
    ('Maria Garcia', '12345', 'socio', '600234567', 'uploads/usuarios/maria_garcia.jpg'),
    ('Carlos Lopez', '12345', 'socio', '600345678', 'uploads/usuarios/carlos_lopez.jpg');


-- Tabla de servicios: id, descripcion, duracion (minutos), precio (e.g., euros)
CREATE TABLE IF NOT EXISTS servicio
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    duracion INT NOT NULL COMMENT 'Duración en minutos',
    precio DECIMAL(10,2) NOT NULL COMMENT 'Precio con dos decimales'
);

-- Datos de ejemplo para la tabla servicio (servicios típicos de una biblioteca)
INSERT INTO servicio (descripcion, duracion, precio)
VALUES
    ('Préstamo de libros', 0, 0.00),
    ('Asesoramiento bibliográfico', 30, 15.00),
    ('Reserva sala de estudio', 120, 6.00),
    ('Acceso a ordenador', 60, 1.50),
    ('Club de lectura', 90, 2.00);


-- Tabla de testimonios: id, autor_id (socio), contenido, fecha (timestamp)
CREATE TABLE IF NOT EXISTS testimonio
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_testimonio_autor FOREIGN KEY (autor_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tres testimonios de ejemplo
INSERT INTO testimonio (autor_id, contenido)
VALUES
    (1, 'Gran servicio y colección. Encontré todo lo que necesitaba para mi trabajo académico.'),
    (2, 'Personal muy atento y ambiente tranquilo. Recomiendo reservar la sala de estudio.'),
    (3, 'Las actividades de club de lectura son fantásticas, una excelente manera de conocer a otros lectores.');


-- Tabla de noticias: id, titulo, contenido, imagen (ruta en servidor), fecha_publicacion
CREATE TABLE IF NOT EXISTS noticia
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT NULL COMMENT 'Ruta en servidor',
    fecha_publicacion DATETIME NOT NULL
);

-- Tres noticias de ejemplo (una con fecha futura)
INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion)
VALUES
    ('Nueva sección juvenil en la biblioteca', 'Abrimos una nueva sección dedicada a jóvenes con títulos actualizados y actividades semanales para adolescentes.', 'uploads/noticias/juvenil.jpg', '2025-11-30 10:00:00'),
    ('Horario especial de navidad', 'Durante las fiestas ampliamos el horario de apertura para facilitar el acceso a la comunidad.', 'uploads/noticias/navidad.jpg', '2025-12-20 09:00:00'),
    ('Inauguración del espacio multimedia', 'Contamos ahora con un espacio multimedia equipado con ordenadores y recursos digitales para investigación.', 'uploads/noticias/multimedia.jpg', '2026-01-10 11:00:00');


-- Tabla de citas: id, socio_id, servicio_id, fecha (DATE), hora (TIME)
CREATE TABLE IF NOT EXISTS cita
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    socio_id INT NOT NULL,
    servicio_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    CONSTRAINT fk_cita_socio FOREIGN KEY (socio_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cita_servicio FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tres citas de ejemplo
INSERT INTO cita (socio_id, servicio_id, fecha, hora)
VALUES
    (2, 3, '2025-11-27', '10:30:00'),
    (1, 5, '2025-11-28', '15:00:00'),
    (3, 2, '2025-12-02', '09:00:00');


