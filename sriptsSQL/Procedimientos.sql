SET SERVEROUTPUT ON
CREATE OR REPLACE PROCEDURE encontrarMesa (
w_numPersonas IN PeticionesReserva.numPersonas%TYPE,
w_ubicacionMesa IN PeticionesReserva.ubicacionMesa%TYPE,
w_tipoMesa IN PeticionesReserva.tipoMesa%TYPE,
w_fechaYHoraReserva IN PeticionesReserva.FechaYHoraReserva%TYPE)
IS
CURSOR cur IS SELECT identificador, maxPersonas FROM MesasConcretas NATURAL JOIN Mesas WHERE w_numPersonas <= maxPersonas AND 
(w_ubicacionMesa IS NULL OR w_ubicacionMesa = ubicacion) AND 
(w_tipoMesa IS NULL OR w_tipoMesa = tipo) ORDER BY maxPersonas ASC;
registro cur%ROWTYPE;
BEGIN 
DBMS_OUTPUT.put_line('Lista de las mesas que cumplen los requisitos del cliente (se recomienda acomodar al cliente en la primera mesa disponible que aparezca en la lista):');
OPEN cur;
LOOP
FETCH cur INTO registro;
EXIT WHEN cur%NOTFOUND;

IF sePuedeReservar(registro.identificador, w_fechaYHoraReserva) = 'Y' THEN 
DBMS_OUTPUT.put_line('El cliente puede acomodarse en la mesa ' ||registro.identificador|| ' que tiene capacidad ' ||registro.maxPersonas);
INSERT INTO TablaAuxiliar1 VALUES (registro.identificador, registro.maxPersonas);
ELSE DBMS_OUTPUT.put_line('La mesa ' ||registro.identificador|| ' cumple los requisitos, pero no está disponible para esta fecha y hora.');
DBMS_OUTPUT.put_line('Pida al cliente que cambie la fecha o la hora. Puede consultar las reservas que tiene asignadas la mesa ' ||registro.identificador|| ' ejecutando el comando consultarReservasMesa( ' ||registro.identificador|| ' )' );
INSERT INTO TablaAuxiliar1 VALUES (registro.identificador, NULL);
END IF;

END LOOP;

IF CUR%ROWCOUNT = 0 THEN
DBMS_OUTPUT.put_line('No hay mesas en el restaurante que cumplan los requisitos. El problema no está en que las mesas ya estén reservadas, sino en que no existen tales mesas.');
END IF;

CLOSE cur;
END encontrarMesa;
 
 /
 
 CREATE OR REPLACE PROCEDURE llamadaAEncontrarMesa (
 w_OID_PR IN PeticionesReserva.OID_PR%TYPE)
 IS
 CURSOR cur3 IS SELECT numPersonas, ubicacionMesa, tipoMesa, fechaYHoraReserva FROM PeticionesReserva WHERE OID_PR = w_OID_PR;
 registro cur3%ROWTYPE;
 BEGIN
 OPEN cur3;
 FETCH cur3 INTO registro;
encontrarMesa(registro.numPersonas, registro.ubicacionMesa, registro.tipoMesa, registro.fechaYHoraReserva);
 CLOSE cur3;
 END llamadaAEncontrarMesa;
 
 /
 
 CREATE OR REPLACE PROCEDURE reservarMesa(
 w_codigo IN Reservas.codigo%TYPE,
 w_OID_PR IN INTEGER,
 w_identificador IN MesasConcretas.identificador%TYPE)
 IS
 CURSOR cur4 IS SELECT fechaYHoraReserva, OID_C FROM PeticionesReserva NATURAL JOIN Clientes WHERE OID_PR = w_OID_PR;
 registro cur4%ROWTYPE;
 BEGIN
 OPEN cur4;
 FETCH cur4 INTO registro;
 INSERT INTO Reservas VALUES (w_codigo, registro.FechaYHoraReserva, w_identificador, registro.OID_C);
 DBMS_OUTPUT.put_line('La mesa ' ||w_identificador|| ' se ha reservado para ' ||registro.fechaYHoraReserva|| ' por el cliente ' ||registro.OID_C);
 INSERT INTO Facturas VALUES (w_OID_PR, SYSDATE, DEFAULT, w_codigo);
 DBMS_OUTPUT.put_line('Se ha elaborado una factura para esta reserva.');
 CLOSE cur4;
 END reservarMesa;

 /
 
CREATE OR REPLACE PROCEDURE consultarReservasMesa(
w_identificador IN MesasConcretas.identificador%TYPE)
IS
CURSOR cur5 IS SELECT fechaYHora FROM Reservas WHERE identificador = w_identificador;
registro cur5%ROWTYPE;
BEGIN 
DBMS_OUTPUT.put_line('Lista de todas las reservas que la mesa ' ||w_identificador|| ' tiene asignadas:');
OPEN cur5;
LOOP
FETCH cur5 INTO registro;
EXIT WHEN cur5%NOTFOUND;

DBMS_OUTPUT.put_line('Tiene asignada una reserva el ' ||registro.fechaYHora);
END LOOP;

IF CUR5%ROWCOUNT = 0 THEN
DBMS_OUTPUT.put_line('La mesa ' ||w_identificador|| ' de momento no tiene asignada ninguna reserva.');
END IF;

CLOSE cur5;
END consultarReservasMesa;
 
 /
 
   
CREATE OR REPLACE PROCEDURE reservasAConfirmar
IS
CURSOR cur6 IS SELECT DISTINCT fechaYHora, nombre, apellidos, telefono FROM Reservas NATURAL JOIN Clientes WHERE fechaYHora BETWEEN CURRENT_TIMESTAMP AND CURRENT_TIMESTAMP + INTERVAL '1' DAY;
w_telefono  Clientes.telefono%TYPE;
w_nombre Clientes.nombre%TYPE;
w_apellidos Clientes.apellidos%TYPE;
w_fechaYHora Reservas.fechaYHora%TYPE;
BEGIN 
DBMS_OUTPUT.put_line('Tiene que llamar a los siguientes clientes:');
OPEN cur6;
LOOP
FETCH cur6 INTO w_fechaYHora, w_nombre, w_apellidos, w_telefono ;
EXIT WHEN cur6%NOTFOUND;

DBMS_OUTPUT.put_line(w_telefono|| ' ( ' ||w_nombre|| ' ' ||w_apellidos|| ' ) ha reservado una mesa para ' ||w_fechaYHora);
INSERT INTO TABLAAUXILIAR8 VALUES (w_nombre, w_apellidos, w_telefono, w_fechaYHora);
END LOOP;

IF CUR6%ROWCOUNT = 0 THEN
DBMS_OUTPUT.put_line('No hay reservas pendientes a confirmar.');
END IF;

CLOSE cur6;
END reservasAConfirmar;
 
 /
 
CREATE OR REPLACE PROCEDURE imprimirPedido(
w_codigoPedido IN Pedidos.codigoPedido%TYPE)
IS
CURSOR cur8 IS SELECT nombre, racionPedida, unidadesPedidas, identificador, fechaYHora FROM LineasDePedido NATURAL JOIN Pedidos NATURAL JOIN Facturas NATURAL JOIN Reservas WHERE codigoPedido = w_codigoPedido;
registro cur8%ROWTYPE;
BEGIN 
OPEN cur8;
LOOP
FETCH cur8 INTO registro;
EXIT WHEN cur8%NOTFOUND;

IF CUR8%ROWCOUNT = 1 THEN
DBMS_OUTPUT.put_line('Se tienen que servir los siguientes platos en la mesa  ' ||registro.identificador|| ' el día ' ||registro.fechaYHora);
INSERT INTO TablaAuxiliar4 VALUES (registro.identificador, registro.fechaYHora);
END IF;

DBMS_OUTPUT.put_line(registro.nombre|| ' (' ||registro.racionPedida|| ') x ' ||registro.unidadesPedidas);
INSERT INTO TablaAuxiliar5 VALUES (registro.nombre, registro.racionPedida, registro.unidadesPedidas);
END LOOP;


CLOSE cur8;
END imprimirPedido;

 /
 
  CREATE OR REPLACE PROCEDURE imprimirFactura(
w_codigoFactura IN Facturas.codigoFactura%TYPE)
IS
CURSOR cur11 IS SELECT codigoPedido
FROM Pedidos NATURAL JOIN Facturas WHERE codigoFactura = w_codigoFactura;
w_codigoPedido Pedidos.codigoPedido%TYPE;

BEGIN 

OPEN cur11;
FETCH cur11 INTO w_codigoPedido;
IF cur11%NOTFOUND THEN
imprimirFacturaSinPedido(w_codigoFactura);
ELSE
imprimirFacturaConPedido(w_codigoFactura);
END IF;
CLOSE cur11;
END imprimirFactura;

 
 /
 
 CREATE OR REPLACE PROCEDURE imprimirFacturaConPedido(
w_codigoFactura IN Facturas.codigoFactura%TYPE)
IS
CURSOR cur9 IS SELECT fechaElaboracion, cuantiaFija, nombre, racionPedida, unidadesPedidas, precioMediaRacion, precioRacion,  calcularPrecio(racionPedida, unidadesPedidas, precioMediaRacion, precioRacion) 
FROM Facturas NATURAL JOIN Pedidos NATURAL JOIN LineasDePedido NATURAL JOIN Platos WHERE codigoFactura = w_codigoFactura;
w_fechaElaboracion Facturas.fechaElaboracion%TYPE;
w_cuantiaFija Facturas.cuantiaFija%TYPE;
w_nombre Platos.nombre%TYPE;
w_racionPedida LineasDePedido.racionPedida%TYPE;
w_unidadesPedidas LineasDePedido.unidadesPedidas%TYPE;
w_precioMediaRacion Platos.precioMediaRacion%TYPE;
w_precioRacion Platos.precioRacion%TYPE;
w_precio NUMBER;

acumulador NUMBER := 0;
cuantia NUMBER :=0;
precioTotal NUMBER := 0;
BEGIN 

OPEN cur9;
LOOP
FETCH cur9 INTO w_fechaElaboracion, w_cuantiaFija, w_nombre, w_racionPedida, w_unidadesPedidas, w_precioMediaRacion, w_precioRacion, w_precio;

EXIT WHEN cur9%NOTFOUND;

IF CUR9%ROWCOUNT = 1 THEN
DBMS_OUTPUT.put_line('                     FACTURA                   ');
DBMS_OUTPUT.put_line('           Restaurante El Tejao           ');
DBMS_OUTPUT.put_line('codigo: ' ||w_codigoFactura|| '              fecha: ' ||w_fechaElaboracion);
END IF;


DBMS_OUTPUT.put_line(w_nombre|| ' (' ||w_racionPedida|| ') x ' ||w_unidadesPedidas|| ' = ' ||w_precio|| '€');
INSERT INTO TablaAuxiliar3 VALUES (w_nombre, w_racionPedida, w_unidadesPedidas, w_precio);
acumulador := acumulador + w_precio;
cuantia := w_cuantiaFija;
END LOOP;
precioTotal := (acumulador*30)/100 + cuantia;
DBMS_OUTPUT.put_line('Coste total: ' ||precioTotal|| '€');
INSERT INTO TablaAuxiliar2 VALUES (w_codigoFactura, w_fechaElaboracion, precioTotal);
CLOSE cur9;
END imprimirFacturaConPedido;
 
 /
 
 CREATE OR REPLACE PROCEDURE imprimirFacturaSinPedido(
w_codigoFactura IN Facturas.codigoFactura%TYPE)
IS
CURSOR cur10 IS SELECT fechaElaboracion, cuantiaFija
FROM Facturas WHERE codigoFactura = w_codigoFactura;
w_fechaElaboracion Facturas.fechaElaboracion%TYPE;
w_cuantiaFija Facturas.cuantiaFija%TYPE;

BEGIN 

OPEN cur10;
FETCH cur10 INTO w_fechaElaboracion, w_cuantiaFija;
DBMS_OUTPUT.put_line('                     FACTURA                   ');
DBMS_OUTPUT.put_line('           Restaurante El Tejao           ');
DBMS_OUTPUT.put_line('codigo: ' ||w_codigoFactura|| '              fecha: ' ||w_fechaElaboracion);
DBMS_OUTPUT.put_line('Coste total: ' ||w_cuantiaFija|| '€');
INSERT INTO TablaAuxiliar2 VALUES (w_codigoFactura, w_fechaElaboracion, w_cuantiaFija);
CLOSE cur10;
END imprimirFacturaSinPedido;

/

 CREATE OR REPLACE PROCEDURE borrarDatosObsoletos
IS

BEGIN 

DELETE FROM PeticionesReserva WHERE fechaYHoraReserva < CURRENT_TIMESTAMP;
DELETE FROM Reservas WHERE fechaYHora < CURRENT_TIMESTAMP;


END borrarDatosObsoletos;





/

CREATE OR REPLACE PROCEDURE checkExistenciasProducto(
w_nombre  IN GruposDeProductos.nombre%TYPE)
IS
CURSOR cur14 IS SELECT umbralExistencias, cantidadExistencia FROM GruposDeProductos NATURAL JOIN Productos WHERE nombre = w_nombre;
registro cur14%ROWTYPE;
w_acumulador NUMBER := 0;
w_umbral NUMBER := 0;
BEGIN
OPEN cur14;
LOOP
FETCH cur14 INTO registro;
EXIT WHEN cur14%NOTFOUND;
w_acumulador := w_acumulador + registro.cantidadExistencia;
w_umbral := registro.umbralExistencias;
END LOOP;

IF w_acumulador < w_umbral OR cur14%ROWCOUNT = 0 THEN
DBMS_OUTPUT.put_line('Hay que comprar ' ||w_nombre);
INSERT INTO TablaAuxiliar6 VALUES (w_nombre);
END IF;
CLOSE cur14;
END checkExistenciasProducto;

/


CREATE OR REPLACE PROCEDURE comprobarExistencias
IS
CURSOR cur15 IS SELECT DISTINCT nombre FROM Productos;
w_nombre Productos.nombre%TYPE;
BEGIN
OPEN cur15;
LOOP
FETCH cur15 INTO w_nombre;
EXIT WHEN cur15%NOTFOUND;
checkExistenciasProducto(w_nombre);
END LOOP;
CLOSE cur15;
END comprobarExistencias;

/

CREATE OR REPLACE PROCEDURE comprobarFechaCaducidad
IS
CURSOR cur16 IS SELECT fechaCaducidad, OID_GP, cantidadExistencia, unidadMedida, nombre FROM GruposDeProductos NATURAL JOIN Productos;
registro cur16%ROWTYPE;
BEGIN
OPEN cur16;
LOOP
FETCH cur16 INTO registro;
EXIT WHEN cur16%NOTFOUND;
IF registro.fechaCaducidad < SYSDATE THEN
DBMS_OUTPUT.put_line('Los/Las ' ||registro.cantidadExistencia|| ' ' ||registro.unidadMedida|| ' de ' ||registro.nombre|| ' ya han caducado. Tiene que tirar los productos del grupo ' ||registro.OID_GP|| ' a la basura') ;
INSERT INTO TablaAuxiliar7 VALUES (registro.OID_GP, registro.cantidadExistencia, registro.unidadMedida, registro.nombre);
END IF;
END LOOP;
CLOSE cur16;
END comprobarFechaCaducidad;

/

CREATE OR REPLACE PROCEDURE consultarNota (
w_codigoNota IN Notas.codigoNota%TYPE
)
IS
CURSOR cur17 IS SELECT nombre, cantidad, unidadMedida FROM Notas NATURAL JOIN LineasDeNota NATURAL JOIN Productos unidadMedida WHERE codigoNota = w_codigoNota;
registro cur17%ROWTYPE;
BEGIN
OPEN cur17;
LOOP
FETCH cur17 INTO registro;
EXIT WHEN cur17%NOTFOUND;
DBMS_OUTPUT.put_line('El jefe de cocina necesita ' || registro.cantidad|| ' '||registro.unidadMedida|| ' de ' ||registro.nombre);
encontrarGrupo(registro.nombre, registro.cantidad, registro.unidadMedida);
END LOOP;
CLOSE cur17;
END consultarNota;

/

CREATE OR REPLACE PROCEDURE encontrarGrupo (
w_nombre IN LineasDeNota.nombre%TYPE,
w_cantidad IN LineasDeNota.cantidad%TYPE,
w_unidadMedida IN Productos.unidadMedida%TYPE
)
IS
CURSOR cur18 IS SELECT cantidadExistencia, fechaCaducidad, OID_GP FROM GruposDeProductos WHERE nombre = w_nombre ORDER BY fechaCaducidad ASC;
registro cur18%ROWTYPE;
BEGIN
DBMS_OUTPUT.put_line('************** Los/las ' || w_cantidad|| ' '||w_unidadMedida|| ' de ' ||w_nombre|| ' se pueden obtener de los siguientes grupos (es recomendable coger primero los productos del primer grupo que aparezca en la lista): ');
OPEN cur18;
LOOP
FETCH cur18 INTO registro;
EXIT WHEN cur18%NOTFOUND;
DBMS_OUTPUT.put_line('                                   El grupo ' ||registro.OID_GP|| ', donde hay ' ||registro.cantidadExistencia|| ' ' ||w_unidadMedida|| ' de ' ||w_nombre|| ' con fecha de caducidad: ' ||registro.fechaCaducidad);
END LOOP;
CLOSE cur18;
END encontrarGrupo;

/

CREATE OR REPLACE PROCEDURE entregarProductos (
w_OID_GP IN GruposDeProductos.OID_GP%TYPE,
w_cantidad IN LineasDeNota.cantidad%TYPE
)
IS

BEGIN
UPDATE GruposDeProductos SET cantidadExistencia = cantidadExistencia - w_cantidad WHERE OID_GP = w_OID_GP;
DELETE FROM GruposDeProductos WHERE cantidadExistencia = 0;
DBMS_OUTPUT.put_line('Se han cogido ' ||w_cantidad|| ' unidades del grupo ' ||w_OID_GP);
END entregarProductos;

/

CREATE OR REPLACE PROCEDURE elaborarListaDeCompras (
w_codigoLista IN ListasDeCompras.codigoLista%TYPE)
IS
CURSOR cur19 IS SELECT DISTINCT nombre FROM LineasDeNota NATURAL JOIN Notas;
w_nombre LineasDeNota.nombre%TYPE;
BEGIN
DELETE FROM ListasDeCompras WHERE fechaCreacion < SYSDATE;
DBMS_OUTPUT.put_line('Se han borrado todas las listas de compras anteriores junto con sus correspondientes notas');
INSERT INTO ListasDeCompras VALUES (w_codigoLista, SYSDATE);
DBMS_OUTPUT.put_line('Se ha creado nueva lista de compras. Su código es: ' ||w_codigoLista);
UPDATE Notas SET codigoLista = w_codigoLista;
DBMS_OUTPUT.put_line('Todas las notas existentes ya corresponden a la nueva lista de compras');
DBMS_OUTPUT.put_line('++++   Lista de compras:   ++++');
OPEN cur19;
LOOP
FETCH cur19 INTO w_nombre;
EXIT WHEN cur19%NOTFOUND;
procedimientoAuxiliar(w_nombre);
END LOOP;
CLOSE cur19;
END elaborarListaDeCompras;

/

CREATE OR REPLACE PROCEDURE procedimientoAuxiliar (
w_nombre IN LineasDeNota.nombre%TYPE)
IS
CURSOR cur20 IS SELECT cantidad, unidadMedida FROM LineasDeNota NATURAL JOIN Notas NATURAL JOIN Productos WHERE nombre = w_nombre;
w_cantidad LineasDeNota.cantidad%TYPE;
w_unidadMedida Productos.unidadMedida%TYPE;
acumulador NUMBER := 0;
BEGIN
OPEN cur20;
LOOP
FETCH cur20 INTO w_cantidad, w_unidadMedida;
EXIT WHEN cur20%NOTFOUND;
acumulador := acumulador + w_cantidad;
END LOOP;
DBMS_OUTPUT.put_line(acumulador|| ' ' ||w_unidadMedida|| ' de ' ||w_nombre);
CLOSE cur20;
END procedimientoAuxiliar;

/
 
 CREATE OR REPLACE PROCEDURE copiaPruebas (
w_codigoLista IN ListasDeCompras.codigoLista%TYPE,
w_fechaCreacion IN ListasDeCompras.fechaCreacion%TYPE)
IS
CURSOR cur19 IS SELECT DISTINCT nombre FROM LineasDeNota NATURAL JOIN Notas;
w_nombre LineasDeNota.nombre%TYPE;
BEGIN
DELETE FROM ListasDeCompras WHERE fechaCreacion < w_fechaCreacion;
DBMS_OUTPUT.put_line('Se han borrado todas las listas de compras anteriores junto con sus correspondientes notas');
INSERT INTO ListasDeCompras VALUES (w_codigoLista, w_fechaCreacion);
DBMS_OUTPUT.put_line('Se ha creado nueva lista de compras. Su código es: ' ||w_codigoLista);
UPDATE Notas SET codigoLista = w_codigoLista;
DBMS_OUTPUT.put_line('Todas las notas existentes ya corresponden a la nueva lista de compras');
DBMS_OUTPUT.put_line('++++   Lista de compras:   ++++');
OPEN cur19;
LOOP
FETCH cur19 INTO w_nombre;
EXIT WHEN cur19%NOTFOUND;
procedimientoAuxiliar(w_nombre);
END LOOP;
CLOSE cur19;
END copiaPruebas;

/
 