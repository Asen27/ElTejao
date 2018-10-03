CREATE TABLE TablaAuxiliar1 (
identificadorMesa INTEGER NOT NULL,
maxPersonasMesa SMALLINT,
PRIMARY KEY (identificadorMesa)
);



CREATE TABLE TablaAuxiliar2 (
codigoDeFactura VARCHAR2(6) NOT NULL,
fechaDeElaboracion DATE NOT NULL,
precioTotal NUMBER NOT NULL,
PRIMARY KEY (codigoDeFactura)
);


CREATE TABLE TablaAuxiliar3 (
nombreDePlato VARCHAR(60) NOT NULL,
racionDePlato VARCHAR2(8) CHECK (racionDePlato IN ('media', 'completa')) NOT NULL,
unidades SMALLINT NOT NULL,
precio NUMBER NOT NULL,
PRIMARY KEY (nombreDePlato, racionDePlato, unidades)
);


CREATE TABLE TablaAuxiliar4 (
identificadorMesa INTEGER NOT NULL,
fechaYHoraReserva TIMESTAMP NOT NULL,
PRIMARY KEY (identificadorMesa, fechaYHoraReserva)
);

CREATE TABLE TablaAuxiliar5 (
nombreDePlatoPedido VARCHAR(60) NOT NULL,
racionDePlatoPedido VARCHAR2(8) CHECK (racionDePlatoPedido IN ('media', 'completa')) NOT NULL,
unidadesPedidas SMALLINT NOT NULL,
PRIMARY KEY (nombreDePlatoPedido, racionDePlatoPedido)
);

CREATE TABLE TablaAuxiliar6 (
nombreDeProducto VARCHAR(30) NOT NULL,
PRIMARY KEY (nombreDeProducto)
);

CREATE TABLE TablaAuxiliar7 (
OID_GRUPO INTEGER NOT NULL,
cantidad NUMBER NOT NULL,
unidad VARCHAR2(10) NOT NULL CHECK (unidad IN (
'gramos', 'kilogramos', 'botes', 'botellas', 'paquetes', 'latas', 'unidades', 'bolsas', 'litros')),
nombreProducto VARCHAR2(30) NOT NULL,
PRIMARY KEY (OID_GRUPO)
);
