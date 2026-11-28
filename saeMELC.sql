SET NAMES utf8mb4;

DROP DATABASE IF EXISTS saemelc;

CREATE DATABASE saemelc
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE saemelc;

DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS puesto;
DROP TABLE IF EXISTS calificaciones;
DROP TABLE IF EXISTS asignatura;
DROP TABLE IF EXISTS grupo_alumno;
DROP TABLE IF EXISTS unidad;
DROP TABLE IF EXISTS materias;
DROP TABLE IF EXISTS profesores;
DROP TABLE IF EXISTS grupos;
DROP TABLE IF EXISTS alumnos;

-- =====================================================================
--  CATÁLOGOS
-- =====================================================================

CREATE TABLE alumnos (
    ID_ALUMNOS        INT AUTO_INCREMENT PRIMARY KEY,
    MATRICULA         VARCHAR(15) NOT NULL,
    NOMBRE            VARCHAR(30) NOT NULL,
    APELLIDO_PATERNO  VARCHAR(20) NOT NULL,
    APELLIDO_MATERNO  VARCHAR(20) NOT NULL,
    DOMICILIO         VARCHAR(80) NOT NULL DEFAULT 'Conocido',
    CORREO            VARCHAR(60) NOT NULL,
    TELEFONO          VARCHAR(20) NOT NULL DEFAULT '5500000000',
    ESTATUS           VARCHAR(4)  NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE profesores (
    ID_PROFESORES     INT AUTO_INCREMENT PRIMARY KEY,
    CLAVE             VARCHAR(15) NOT NULL,
    NOMBRE            VARCHAR(30) NOT NULL,
    APELLIDO_PATERNO  VARCHAR(20) NOT NULL,
    APELLIDO_MATERNO  VARCHAR(20) NOT NULL,
    CORREO            VARCHAR(60) NOT NULL,
    TELEFONO          VARCHAR(20) NOT NULL DEFAULT '5500000000',
    ESTATUS           VARCHAR(4)  NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE grupos (
    ID_GRUPOS     INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_GRUPO  VARCHAR(60)  NOT NULL,
    TURNO         VARCHAR(15)  NOT NULL DEFAULT 'Matutino',
    SEMESTRE      VARCHAR(10)  NOT NULL DEFAULT '4',
    DESCRIPCION   VARCHAR(255) NOT NULL DEFAULT '',
    ESTATUS       VARCHAR(4)   NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE materias (
    ID_MATERIAS     INT AUTO_INCREMENT PRIMARY KEY,
    CLAVE_MATERIA   VARCHAR(15)  NOT NULL,
    NOMBRE_MATERIA  VARCHAR(60)  NOT NULL,
    CREDITOS        VARCHAR(5)   NOT NULL,
    DESCRIPCION     VARCHAR(255) NOT NULL DEFAULT '',
    ESTATUS         VARCHAR(4)   NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE unidad (
    ID_UNIDAD    INT AUTO_INCREMENT PRIMARY KEY,
    DESCRIPCION  VARCHAR(255) NOT NULL,
    ESTATUS      VARCHAR(4)   NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- =====================================================================
--  PROCESOS
-- =====================================================================

CREATE TABLE grupo_alumno (
    ID_GRUPOS   INT NOT NULL,
    ID_ALUMNOS  INT NOT NULL,
    ESTATUS     VARCHAR(4) NOT NULL DEFAULT 'ALTA',
    PRIMARY KEY (ID_GRUPOS, ID_ALUMNOS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE asignatura (
    ID_ASIGNATURA  INT AUTO_INCREMENT PRIMARY KEY,
    ID_MATERIAS    INT NOT NULL,
    ID_ALUMNOS     INT NOT NULL,
    ID_PROFESORES  INT NOT NULL,
    UNIQUE KEY uq_materia_alumno (ID_MATERIAS, ID_ALUMNOS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE calificaciones (
    ID_CALIFICACION  INT AUTO_INCREMENT PRIMARY KEY,
    ID_ALUMNOS       INT NOT NULL,
    ID_MATERIAS      INT NOT NULL,
    CALIFICACION     DECIMAL(4,1) NOT NULL,
    ID_UNIDAD        INT NOT NULL,
    UNIQUE KEY uq_alumno_materia_unidad (ID_ALUMNOS, ID_MATERIAS, ID_UNIDAD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- =====================================================================
--  USUARIOS Y PUESTOS
-- =====================================================================

CREATE TABLE puesto (
    ID_PUESTO           INT AUTO_INCREMENT PRIMARY KEY,
    DESCRIPCION_PUESTO  VARCHAR(50) NOT NULL,
    STATUS_PUESTO       VARCHAR(4)  NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE usuarios (
    ID_USER        INT AUTO_INCREMENT PRIMARY KEY,
    NOMBREUSER     VARCHAR(60) NOT NULL,
    PWUSER         VARCHAR(40) NOT NULL,
    PUESTO         INT NOT NULL,
    ID_PROFESORES  INT NULL DEFAULT NULL,
    ID_ALUMNOS     INT NULL DEFAULT NULL,
    STATUS_USER    VARCHAR(4)  NOT NULL DEFAULT 'ALTA',
    UNIQUE KEY uq_nombreuser (NOMBREUSER),
    CONSTRAINT fk_usuarios_puesto FOREIGN KEY (PUESTO) REFERENCES puesto (ID_PUESTO),
    CONSTRAINT fk_usuarios_profesor FOREIGN KEY (ID_PROFESORES) REFERENCES profesores (ID_PROFESORES) ON DELETE SET NULL,
    CONSTRAINT fk_usuarios_alumno FOREIGN KEY (ID_ALUMNOS) REFERENCES alumnos (ID_ALUMNOS) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- =====================================================================
--  DATOS DE EMMANUEL
-- =====================================================================

INSERT INTO alumnos (ID_ALUMNOS, MATRICULA, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, DOMICILIO, CORREO, TELEFONO, ESTATUS) VALUES
(1, '6666', 'Ashley',  'Graves',  'G',        'Calle Principal 101', 'hermanoslocos@gmail.com', '5510006666', 'ALTA'),
(2, '6667', 'Andrew',  'Graves',  'G',        'Calle Principal 101', 'hermanoslocos@gmail.com', '5510006667', 'ALTA'),
(3, '1234', 'Lilith',  'Fuentes', 'Perez',    'Av. Reforma 202',     'algunalumna@gmail.com',   '5510001234', 'ALTA'),
(4, '1247', 'Lynette', 'Scavo',   'Mendez',   'Wisteria Lane 4355',  'Scavo@gmail.com',         '5510001247', 'ALTA'),
(5, '2026', 'Martin',  'Lopez',   'Cornejo',  'Av. Tecnológico 505', 'algunalumno@gmail.com',   '5510002026', 'ALTA'),
(6, '9238', 'Rocio',   'Cornejo', 'Esquivel', 'Av. Tecnológico 505', 'Rox@gmail.com',           '5510009238', 'ALTA');

INSERT INTO profesores (ID_PROFESORES, CLAVE, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, CORREO, TELEFONO, ESTATUS) VALUES
(1, 'PROF001', 'Eva Sara',  'García',  'Hernandez', 'DocenteSara@gmail.com',    '5590000001', 'ALTA'),
(2, 'PROF002', 'Chris',     'Prince',  'NEO',       'algunprofeNEO@gmail.com',  '5590000002', 'ALTA'),
(3, 'PROF003', 'Carlos',    'Mendoza', 'Ramirez',   'algunprofe@gmail.com',     '5590000003', 'ALTA'),
(4, 'PROF004', 'Anri',      'Teieri',  'Soto',      'Teieri@gmail.com',         '5590000004', 'ALTA'),
(5, 'PROF005', 'Leonardo',  'Luna',    'NEO',       'algunprofeNEO2@gmail.com', '5590000005', 'ALTA'),
(6, 'PROF006', 'Gabrielle', 'Solis',   'Marquez',   'Solis@gmail.com',          '5590000006', 'ALTA');

INSERT INTO grupos (ID_GRUPOS, NOMBRE_GRUPO, TURNO, SEMESTRE, DESCRIPCION, ESTATUS) VALUES
(1, '406 - Tecnologías de la Información', 'Matutino',   '4', 'Cuarto semestre grupo A', 'ALTA'),
(2, '101 - Tecnologías de la Información', 'Matutino',   '1', 'Primer semestre grupo A', 'ALTA'),
(3, '202 - Tecnologías de la Información', 'Vespertino', '2', 'Segundo semestre grupo B', 'ALTA'),
(4, '501 - Tecnologías de la Información', 'Matutino',   '5', 'Quinto semestre grupo A', 'ALTA'),
(5, '601 - Tecnologías de la Información', 'Matutino',   '6', 'Sexto semestre grupo A',  'ALTA');

INSERT INTO materias (ID_MATERIAS, CLAVE_MATERIA, NOMBRE_MATERIA, CREDITOS, DESCRIPCION, ESTATUS) VALUES
(1, 'MAT-01',  'Matemáticas Discretas',                  '4', 'Lógica, grafos y árboles',       'ALTA'),
(2, 'PROG-02', 'Programación II',                        '5', 'Desarrollo web con PHP y MySQL', 'ALTA'),
(3, 'FILO-02', 'Filosofía II',                           '2', 'Ética profesional',              'ALTA'),
(4, 'DB-01',   'Administración de Bases de Datos',       '5', 'SQL, transacciones y respaldos', 'ALTA'),
(5, 'MAT-07',  'Matemáticas para la Toma de Decisiones', '4', 'Modelos de optimización',        'ALTA');

INSERT INTO unidad (ID_UNIDAD, DESCRIPCION, ESTATUS) VALUES
(1, 'Unidad 1 - Parcial 1',      'ALTA'),
(2, 'Unidad 2 - Parcial 2',      'ALTA'),
(3, 'Unidad 3 - Parcial 3',      'ALTA'),
(4, 'Unidad Especial - Recurse', 'ALTA');

INSERT INTO grupo_alumno (ID_GRUPOS, ID_ALUMNOS, ESTATUS) VALUES
(1, 1, 'ALTA'),
(1, 2, 'ALTA'),
(1, 5, 'ALTA'),
(1, 6, 'ALTA'),
(2, 3, 'ALTA'),
(2, 4, 'ALTA');

INSERT INTO asignatura (ID_ASIGNATURA, ID_MATERIAS, ID_ALUMNOS, ID_PROFESORES) VALUES
(1, 2, 1, 1),
(2, 1, 1, 2),
(3, 2, 2, 1),
(4, 4, 5, 3),
(5, 3, 6, 5);

INSERT INTO calificaciones (ID_ALUMNOS, ID_MATERIAS, CALIFICACION, ID_UNIDAD) VALUES
(1, 2, 9.5, 1),
(1, 2, 9.0, 2),
(1, 1, 8.5, 1),
(2, 2, 9.0, 1),
(2, 2, 8.5, 2),
(5, 4, 9.5, 1),
(6, 3, 10.0, 1);

INSERT INTO puesto (ID_PUESTO, DESCRIPCION_PUESTO, STATUS_PUESTO) VALUES
(1, 'Administrador', 'ALTA'),
(2, 'Maestro',       'ALTA'),
(3, 'Alumno',        'ALTA');

INSERT INTO usuarios (ID_USER, NOMBREUSER, PWUSER, PUESTO, ID_PROFESORES, ID_ALUMNOS, STATUS_USER) VALUES
(1, 'Fazt.admin', SHA1('12345'), 1, NULL, NULL, 'ALTA'),
(2, 'docente',    SHA1('12345'), 2, 1,    NULL, 'ALTA'),
(3, 'alumno',     SHA1('12345'), 3, NULL, 1,    'ALTA'),
(4, 'alumna',     SHA1('12345'), 3, NULL, 2,    'ALTA');

-- =====================================================================
--  USUARIOS Y CONTRASEÑAS DE PRUEBA (SHA1)
-- =====================================================================
--  Fazt.admin | 12345 | Administrador (Acceso total)
--  docente    | 12345 | Docente (Eva Sara García Hernandez)
--  alumno     | 12345 | Alumno (Ashley Graves G)
--  alumna     | 12345 | Alumno (Andrew Graves G)
-- =====================================================================
