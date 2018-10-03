CREATE TABLE Productos (
nombre VARCHAR2(30) NOT NULL,
unidadMedida VARCHAR2(10) NOT NULL CHECK (unidadMedida IN (
'gramos', 'kilogramos', 'botes', 'botellas', 'paquetes', 'latas', 'unidades', 'bolsas', 'litros')),
umbralExistencias NUMBER NOT NULL,
PRIMARY KEY (nombre)
);

CREATE TABLE GruposDeProductos (
OID_GP INTEGER NOT NULL,
nombre VARCHAR2(30) NOT NULL,
fechaCaducidad DATE NOT NULL,
cantidadExistencia NUMBER NOT NULL,
PRIMARY KEY (OID_GP),
UNIQUE (nombre, fechaCaducidad),
FOREIGN KEY (nombre) REFERENCES Productos
);

CREATE TABLE ListasDeCompras(
codigoLista VARCHAR2(8) NOT NULL,
fechaCreacion DATE NOT NULL UNIQUE,
PRIMARY KEY (codigoLista)
);

CREATE TABLE Notas (
codigoNota VARCHAR2(8) NOT NULL,
fechaElaboracion DATE NOT NULL,
codigoLista VARCHAR2(8),
PRIMARY KEY (codigoNota),
FOREIGN KEY (codigoLista) REFERENCES ListasDeCompras ON DELETE CASCADE
);

CREATE TABLE LineasDeNota (
nombre VARCHAR2(30) NOT NULL,
cantidad NUMBER NOT NULL,
codigoNota VARCHAR2(8) NOT NULL,
PRIMARY KEY (nombre, codigoNota),
FOREIGN KEY (codigoNota) REFERENCES Notas ON DELETE CASCADE
);

