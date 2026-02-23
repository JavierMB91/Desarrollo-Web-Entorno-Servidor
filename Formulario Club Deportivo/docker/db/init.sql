CREATE TABLE IF NOT EXISTS usuario (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        edad TINYINT UNSIGNED NULL,
        password VARCHAR(255) NOT NULL,
        rol ENUM('socio', 'administrador') NOT NULL DEFAULT 'socio',
        telefono VARCHAR(20) UNIQUE,
        foto VARCHAR(255),
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Compatibilidad retroactiva (si existen instalaciones antiguas)
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS edad TINYINT UNSIGNED NULL;
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS password VARCHAR(255) NULL;
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS clave VARCHAR(255) NULL;
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS rol ENUM('socio', 'administrador') NOT NULL DEFAULT 'socio';
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS telefono VARCHAR(20) NULL;
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS foto VARCHAR(255) NULL;
ALTER TABLE usuario ADD COLUMN IF NOT EXISTS fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Si había columna legacy 'clave', migrar a 'password' sin borrar datos
UPDATE usuario
SET password = clave
WHERE (password IS NULL OR password = '')
    AND clave IS NOT NULL
    AND clave <> '';

-- Usuarios iniciales (clave: 12345), inserción segura para no duplicar
INSERT INTO usuario (nombre, edad, password, rol, telefono, foto)
SELECT 'Juan Perez', 34, '$2y$10$BUKmOM.4i4kKgHNe8gZq7O3RSMKZQHSBq3qjVo2s.Gas7XU7BTNfW', 'administrador', '600123456', 'juan_perez.jpg'
WHERE NOT EXISTS (SELECT 1 FROM usuario WHERE telefono = '600123456');

INSERT INTO usuario (nombre, edad, password, rol, telefono, foto)
SELECT 'Maria Garcia', 27, '$2y$10$BUKmOM.4i4kKgHNe8gZq7O3RSMKZQHSBq3qjVo2s.Gas7XU7BTNfW', 'socio', '600234567', 'maria_garcia.jpg'
WHERE NOT EXISTS (SELECT 1 FROM usuario WHERE telefono = '600234567');

INSERT INTO usuario (nombre, edad, password, rol, telefono, foto)
SELECT 'Carlos Lopez', 31, '$2y$10$BUKmOM.4i4kKgHNe8gZq7O3RSMKZQHSBq3qjVo2s.Gas7XU7BTNfW', 'socio', '600345678', 'carlos_lopez.jpg'
WHERE NOT EXISTS (SELECT 1 FROM usuario WHERE telefono = '600345678');


CREATE TABLE IF NOT EXISTS servicio (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(150) NOT NULL,
        descripcion VARCHAR(255) NOT NULL,
        duracion INT NOT NULL COMMENT 'Duración en minutos',
        precio DECIMAL(10,2) NOT NULL COMMENT 'Precio con dos decimales',
        hora TIME NOT NULL
);

-- Compatibilidad retroactiva para columnas usadas por PHP
ALTER TABLE servicio ADD COLUMN IF NOT EXISTS nombre VARCHAR(150) NULL;
ALTER TABLE servicio ADD COLUMN IF NOT EXISTS descripcion VARCHAR(255) NULL;
ALTER TABLE servicio ADD COLUMN IF NOT EXISTS duracion INT NULL;
ALTER TABLE servicio ADD COLUMN IF NOT EXISTS precio DECIMAL(10,2) NULL;
ALTER TABLE servicio ADD COLUMN IF NOT EXISTS hora TIME NULL;

UPDATE servicio
SET nombre = LEFT(descripcion, 150)
WHERE (nombre IS NULL OR nombre = '')
    AND descripcion IS NOT NULL
    AND descripcion <> '';

UPDATE servicio
SET hora = '09:00:00'
WHERE hora IS NULL;

-- Datos iniciales sin duplicados por nombre
INSERT INTO servicio (nombre, descripcion, duracion, precio, hora)
SELECT 'Entrenamiento Funcional', 'Sesión guiada de entrenamiento para mejorar resistencia y movilidad.', 60, 0.00, '09:00:00'
WHERE NOT EXISTS (SELECT 1 FROM servicio WHERE nombre = 'Entrenamiento Funcional');

INSERT INTO servicio (nombre, descripcion, duracion, precio, hora)
SELECT 'Asesoría Deportiva', 'Orientación personalizada para plan de actividad física.', 30, 15.00, '11:00:00'
WHERE NOT EXISTS (SELECT 1 FROM servicio WHERE nombre = 'Asesoría Deportiva');

INSERT INTO servicio (nombre, descripcion, duracion, precio, hora)
SELECT 'Reserva Sala', 'Reserva de sala para actividades grupales del club.', 120, 6.00, '17:00:00'
WHERE NOT EXISTS (SELECT 1 FROM servicio WHERE nombre = 'Reserva Sala');

INSERT INTO servicio (nombre, descripcion, duracion, precio, hora)
SELECT 'Uso de Gimnasio', 'Acceso al gimnasio del club con equipamiento básico.', 60, 1.50, '18:00:00'
WHERE NOT EXISTS (SELECT 1 FROM servicio WHERE nombre = 'Uso de Gimnasio');

INSERT INTO servicio (nombre, descripcion, duracion, precio, hora)
SELECT 'Actividad de Grupo', 'Actividad dirigida para socios en grupo reducido.', 90, 2.00, '19:00:00'
WHERE NOT EXISTS (SELECT 1 FROM servicio WHERE nombre = 'Actividad de Grupo');


CREATE TABLE IF NOT EXISTS testimonio (
        id INT AUTO_INCREMENT PRIMARY KEY,
        autor_id INT NOT NULL,
        contenido TEXT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_testimonio_autor FOREIGN KEY (autor_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO testimonio (autor_id, contenido)
SELECT 1, 'Gran ambiente y buen trato. El club me ayudó a mantener una rutina constante.'
WHERE EXISTS (SELECT 1 FROM usuario WHERE id = 1)
    AND NOT EXISTS (SELECT 1 FROM testimonio WHERE autor_id = 1 AND contenido = 'Gran ambiente y buen trato. El club me ayudó a mantener una rutina constante.');

INSERT INTO testimonio (autor_id, contenido)
SELECT 2, 'Muy buenas actividades y horarios flexibles para compaginar con el trabajo.'
WHERE EXISTS (SELECT 1 FROM usuario WHERE id = 2)
    AND NOT EXISTS (SELECT 1 FROM testimonio WHERE autor_id = 2 AND contenido = 'Muy buenas actividades y horarios flexibles para compaginar con el trabajo.');

INSERT INTO testimonio (autor_id, contenido)
SELECT 3, 'Instalaciones cuidadas y actividades variadas para todos los niveles.'
WHERE EXISTS (SELECT 1 FROM usuario WHERE id = 3)
    AND NOT EXISTS (SELECT 1 FROM testimonio WHERE autor_id = 3 AND contenido = 'Instalaciones cuidadas y actividades variadas para todos los niveles.');


CREATE TABLE IF NOT EXISTS noticia (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        contenido TEXT NOT NULL,
        imagen VARCHAR(255) DEFAULT NULL COMMENT 'Ruta en servidor',
        fecha_publicacion DATETIME NOT NULL
);

INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion)
SELECT 'Nuevas clases de temporada', 'Abrimos nuevas actividades para socios con diferentes niveles de intensidad.', 'uploads/noticias/temporada.jpg', '2026-02-10 10:00:00'
WHERE NOT EXISTS (SELECT 1 FROM noticia WHERE titulo = 'Nuevas clases de temporada');

INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion)
SELECT 'Horario especial de festivos', 'Durante festivos se aplicará horario reducido en instalaciones.', 'uploads/noticias/festivos.jpg', '2026-02-15 09:00:00'
WHERE NOT EXISTS (SELECT 1 FROM noticia WHERE titulo = 'Horario especial de festivos');

INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion)
SELECT 'Renovación de equipamiento', 'Incorporamos nuevo material para entrenamiento funcional y fuerza.', 'uploads/noticias/equipamiento.jpg', '2026-02-20 11:00:00'
WHERE NOT EXISTS (SELECT 1 FROM noticia WHERE titulo = 'Renovación de equipamiento');


CREATE TABLE IF NOT EXISTS cita (
        id INT AUTO_INCREMENT PRIMARY KEY,
        socio_id INT NOT NULL,
        servicio_id INT NOT NULL,
        fecha DATE NOT NULL,
        hora TIME NOT NULL,
        CONSTRAINT fk_cita_socio FOREIGN KEY (socio_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_cita_servicio FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO cita (socio_id, servicio_id, fecha, hora)
SELECT 2, 3, '2026-02-27', '10:30:00'
WHERE EXISTS (SELECT 1 FROM usuario WHERE id = 2)
    AND EXISTS (SELECT 1 FROM servicio WHERE id = 3)
    AND NOT EXISTS (SELECT 1 FROM cita WHERE socio_id = 2 AND servicio_id = 3 AND fecha = '2026-02-27' AND hora = '10:30:00');

INSERT INTO cita (socio_id, servicio_id, fecha, hora)
SELECT 1, 5, '2026-02-28', '15:00:00'
WHERE EXISTS (SELECT 1 FROM usuario WHERE id = 1)
    AND EXISTS (SELECT 1 FROM servicio WHERE id = 5)
    AND NOT EXISTS (SELECT 1 FROM cita WHERE socio_id = 1 AND servicio_id = 5 AND fecha = '2026-02-28' AND hora = '15:00:00');

INSERT INTO cita (socio_id, servicio_id, fecha, hora)
SELECT 3, 2, '2026-03-02', '09:00:00'
WHERE EXISTS (SELECT 1 FROM usuario WHERE id = 3)
    AND EXISTS (SELECT 1 FROM servicio WHERE id = 2)
    AND NOT EXISTS (SELECT 1 FROM cita WHERE socio_id = 3 AND servicio_id = 2 AND fecha = '2026-03-02' AND hora = '09:00:00');


