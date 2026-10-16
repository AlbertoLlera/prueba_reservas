-- Creación base de datos para reservas
CREATE DATABASE IF NOT EXISTS reservas_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE reservas_db;

CREATE TABLE IF NOT EXISTS reserva (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_huesped VARCHAR(120) NOT NULL,
    email_huesped VARCHAR(150) NOT NULL,
    nombre_alojamiento VARCHAR(150) NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') NOT NULL DEFAULT 'pendiente',
    importe DECIMAL(10, 2) NOT NULL,
    notas TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Evitar que la fecha de entrada sea mayor que la fecha de salida
    CONSTRAINT chk_fechas CHECK (fecha_entrada < fecha_salida)
);

CREATE TABLE IF NOT EXISTS reserva_evento(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT UNSIGNED NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    descripcion VARCHAR(300) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reserva_id) REFERENCES reserva(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Inserts para las dos tablas de la DB

INSERT INTO reserva
    (nombre_huesped, email_huesped, nombre_alojamiento,
     fecha_entrada, fecha_salida, estado, importe, notas)
VALUES
    -- Reservas pendientes
    ('María García López', 'maria.garcia@example.com', 'Hotel Playa Sol',
     '2025-11-15', '2025-11-20', 'pendiente', 450.00,
     'Llegada tarde, avisar en recepción'),

    ('José Martínez Ruiz', 'jose.martinez@example.com', 'Apartamento Centro Histórico',
     '2025-12-01', '2025-12-05', 'pendiente', 320.50,
     'Necesita cuna para bebé'),

    ('Laura Sánchez Pérez', 'laura.sanchez@example.com', 'Casa Rural El Roble',
     '2026-01-10', '2026-01-15', 'pendiente', 580.00,
     NULL),

    ('Carlos Rodríguez Vega', 'carlos.rodriguez@example.com', 'Hostal Goya',
     '2026-02-20', '2026-02-23', 'pendiente', 210.00,
     'Cliente habitual'),

    -- Reservas confirmadas
    ('Ana García Fernández', 'ana.garcia@example.com', 'Hotel Boutique Marbella',
     '2025-08-10', '2025-08-17', 'confirmada', 780.00,
     'Reserva para luna de miel'),

    ('Lucía López Moreno', 'lucia.lopez@example.com', 'Apartamento Sierra Nevada',
     '2025-10-05', '2025-10-08', 'confirmada', 340.00,
     NULL),

    ('Pedro Jiménez Castro', 'pedro.jimenez@example.com', 'Hotel Rural Los Olivos',
     '2025-10-20', '2025-10-25', 'confirmada', 495.75,
     'Alergia a los frutos secos'),

    ('Marta López Díaz', 'marta.lopez@example.com', 'Villa Costa Brava',
     '2026-07-01', '2026-07-14', 'confirmada', 1250.00,
     'Grupo familiar de 6 personas'),

    -- Reservas canceladas 
    ('Javier Fernández Ortiz', 'javier.fernandez@example.com', 'Hotel Gran Vía',
     '2025-09-12', '2025-09-14', 'cancelada', 180.00,
     'Cancelada por el cliente sin penalización'),

    ('Elena Ruiz Navarro', 'elena.ruiz@example.com', 'Apartamento Costa del Sol',
     '2025-12-24', '2025-12-31', 'cancelada', 620.00,
     'Cancelada por cambio de planes');


INSERT INTO reserva_evento (reserva_id, tipo, descripcion)
VALUES
    (1, 'creada', 'Reserva creada por el cliente desde la web'),
    (2, 'creada', 'Reserva creada por el cliente desde la web'),
    (3, 'creada', 'Reserva creada por el cliente desde la web'),
    (4, 'creada', 'Reserva creada por el cliente desde la web'),
    (5, 'creada', 'Reserva creada por el cliente desde la web'),
    (5, 'confirmada', 'Pago recibido, reserva confirmada'),
    (6, 'creada', 'Reserva creada por el cliente desde la web'),
    (6, 'confirmada', 'Pago recibido, reserva confirmada'),
    (7, 'creada', 'Reserva creada por el cliente desde la web'),
    (7, 'confirmada', 'Pago recibido, reserva confirmada'),
    (8, 'creada', 'Reserva creada por el cliente desde la web'),
    (8, 'confirmada', 'Pago recibido, reserva confirmada'),
    (9, 'creada', 'Reserva creada por el cliente desde la web'),
    (9, 'cancelada', 'Cancelada por el cliente sin penalización'),
    (10, 'creada', 'Reserva creada por el cliente desde la web'),
    (10, 'cancelada', 'Cancelada por cambio de planes');