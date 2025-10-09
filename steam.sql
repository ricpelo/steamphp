-- Tabla de clientes de la tienda online

DROP TABLE IF EXISTS clientes CASCADE;

CREATE TABLE clientes (
    id        BIGSERIAL     PRIMARY KEY,
    dni       VARCHAR(9)    NOT NULL UNIQUE,
    nombre    VARCHAR(255)  NOT NULL,
    apellidos VARCHAR(255),
    direccion VARCHAR(255),
    codpostal NUMERIC(5)    CHECK (codpostal >= 0),
    telefono  VARCHAR(255)
);

-- Datos de prueba

INSERT INTO clientes (dni, nombre, apellidos, direccion, codpostal, telefono)
VALUES ('11111111A', 'Juan', 'Martínez', 'C/. Su casa', 11540, '666555444'),
       ('22222222B', 'María', 'González', 'C/. Su otra casa', 11550, '555444333');
