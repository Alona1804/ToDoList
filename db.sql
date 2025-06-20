CREATE DATABASE tareas;
USE tareas;
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_limite DATE,
    completada BOOLEAN DEFAULT 0
);

ALTER TABLE tareas ADD COLUMN code_de_tarea INT;

CREATE TABLE tipo_tarea (
    code_de_tarea INT PRIMARY KEY,
    tipo_de_tarea VARCHAR(50) NOT NULL
);

INSERT INTO tipo_tarea (code_de_tarea, tipo_de_tarea) VALUES
(1, 'WEB'),
(2, 'PHP'),
(3, 'SQL');

ALTER TABLE tareas ADD FOREIGN KEY (code_de_tarea) REFERENCES tipo_tarea(code_de_tarea);


INSERT INTO tareas (titulo, descripcion, fecha_limite, completada, code_de_tarea) VALUES
('Crear landing page', 'Diseñar y desarrollar una página web atractiva.', '2025-06-20', 0, 1),
('Optimizar imágenes', 'Reducir el tamaño sin perder calidad para mejorar rendimiento.', '2025-06-22', 0, 1),
('Implementar API REST', 'Crear endpoints para una aplicación web.', '2025-06-25', 0, 1),
('Validación de formularios', 'Añadir validaciones en el frontend y backend.', '2025-06-18', 0, 1),
('Mejorar SEO', 'Optimizar etiquetas y contenido para posicionamiento.', '2025-06-30', 0, 1),

('Desarrollar CRUD en PHP', 'Crear, leer, actualizar y eliminar registros con PHP.', '2025-06-21', 0, 2),
('Autenticación de usuarios', 'Implementar login y registro seguros.', '2025-06-23', 0, 2),
('Generación de PDFs', 'Exportar información de la base de datos a archivos PDF.', '2025-06-26', 0, 2),
('Subida de archivos', 'Permitir a los usuarios cargar archivos en el sistema.', '2025-06-19', 0, 2),
('Integración con PayPal', 'Añadir pasarela de pagos para compras en línea.', '2025-07-01', 0, 2),

('Normalización de base de datos', 'Organizar datos para reducir redundancia.', '2025-06-15', 0, 3),
('Optimización de consultas', 'Mejorar rendimiento de SELECT y JOIN.', '2025-06-24', 0, 3),
('Backups automáticos', 'Configurar copias de seguridad regulares.', '2025-06-29', 0, 3),
('Triggers en MySQL', 'Definir eventos que se ejecutan automáticamente.', '2025-06-27', 0, 3),
('Seguridad en la base de datos', 'Evitar inyecciones SQL y accesos no autorizados.', '2025-07-02', 0, 3),

('Implementar diseño responsive', 'Adaptar el sitio web a móviles.', '2025-06-16', 0, 1),
('Agregar cookies y sesiones', 'Manejo de sesiones para recordar usuarios.', '2025-06-17', 0, 2),
('Automatizar reportes', 'Generar informes programados en SQL.', '2025-06-28', 0, 3),
('Validar correos en PHP', 'Comprobar formato y existencia de emails.', '2025-06-14', 0, 2),
('Optimizar CSS', 'Reducir tamaño y mejorar organización.', '2025-06-13', 0, 1),

('Crear función recursiva', 'Aplicar recursividad en PHP.', '2025-06-11', 0, 2),
('Configurar índices en SQL', 'Mejorar velocidad de consultas.', '2025-06-12', 0, 3),
('Desplegar aplicación web', 'Publicar el proyecto en un servidor.', '2025-07-03', 0, 1),
('Generar códigos únicos', 'Crear identificadores sin repetir.', '2025-06-10', 0, 3),
('Optimización de JSON en PHP', 'Procesar datos de manera eficiente.', '2025-07-04', 0, 2),

('Actualizar dependencias', 'Mantener paquetes al día.', '2025-06-09', 0, 1),
('Crear base de datos relacional', 'Definir tablas y relaciones.', '2025-06-08', 0, 3),
('Mejorar accesibilidad web', 'Adaptar para usuarios con discapacidades.', '2025-06-07', 0, 1),
('Depurar código PHP', 'Eliminar errores y optimizar rendimiento.', '2025-06-06', 0, 2),
('Testear funciones SQL', 'Validar integridad de consultas.', '2025-06-05', 0, 3),

('Optimizar carga de imágenes', 'Usar lazy loading.', '2025-06-04', 0, 1),
('Crear sistema de notificaciones', 'Enviar alertas a usuarios.', '2025-06-03', 0, 2),
('Configurar permisos SQL', 'Definir accesos a la base de datos.', '2025-06-02', 0, 3),
('Implementar cache en PHP', 'Reducir consultas repetitivas.', '2025-06-01', 0, 2),
('Usar AJAX para mejorar UX', 'Actualizar contenido sin recargar página.', '2025-07-05', 0, 1);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario'
);

INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Admin', 'admin@example.com', 'admin', 'admin'),
('Usuario 1', 'user1@example.com', 'user1', 'usuario'),
('Usuario 2', 'user2@example.com', 'user2', 'usuario');