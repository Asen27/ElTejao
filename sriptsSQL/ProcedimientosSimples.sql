CREATE OR REPLACE PROCEDURE nuevoCliente(
w_OID_C IN Clientes.OID_C%TYPE,
w_nombre IN Clientes.nombre%TYPE,
w_apellidos IN Clientes.apellidos%TYPE,
w_telefono IN Clientes.telefono%TYPE)
IS
BEGIN
INSERT INTO Clientes VALUES (w_OID_C, w_nombre, w_apellidos, w_telefono);
DBMS_OUTPUT.PUT_LINE('Cliente ' ||w_OID_C|| ' del restaurante. Sus datos son: ' ||w_nombre|| ' ' ||w_apellidos|| ' (' ||w_telefono|| ') . ');
END nuevoCliente;

/

CREATE OR REPLACE PROCEDURE requisitosMesa(
w_OID_PR IN PeticionesReserva.OID_PR%TYPE,
w_fechaYHoraReserva IN PeticionesReserva.fechaYHoraReserva%TYPE,
w_numPersonas IN PeticionesReserva.numPersonas%TYPE,
w_ubicacionMesa IN PeticionesReserva.ubicacionMesa%TYPE,
w_tipoMesa IN PeticionesReserva.tipoMesa%TYPE,
w_OID_C IN Clientes.OID_C%TYPE)
IS
BEGIN
INSERT INTO PeticionesReserva VALUES (w_OID_PR, w_fechaYHoraReserva, w_numPersonas, w_ubicacionMesa, w_tipoMesa, w_OID_C);
DBMS_OUTPUT.PUT_LINE('Cliente ' ||w_OID_C|| ' del restaurante quiere reservar una mesa para ' ||w_fechaYHoraReserva|| ' con las siguientes caracterisitcas: ');
DBMS_OUTPUT.PUT_LINE('Número de personas: ' ||w_numPersonas);
DBMS_OUTPUT.PUT_LINE('Interior/exterior: ' ||w_ubicacionMesa);
DBMS_OUTPUT.PUT_LINE('Fumadores/no fumadores: ' ||w_tipoMesa);
DBMS_OUTPUT.PUT_LINE('Esta petición de reserva llevará el número identificador: ' ||w_OID_PR);
END requisitosMesa;

/

CREATE OR REPLACE PROCEDURE introducirNuevoTipoMesa(
w_OID_M IN Mesas.OID_M%TYPE,
w_maxPersonas IN Mesas.maxPersonas%TYPE,
w_ubicacion IN Mesas.ubicacion%TYPE,
w_tipo IN Mesas.tipo%TYPE)
IS
BEGIN
INSERT INTO Mesas VALUES (w_OID_M, w_maxPersonas, w_ubicacion, w_tipo, DEFAULT);
DBMS_OUTPUT.PUT_LINE('Se ha introducido un nuevo tipo de mesa (tipo ' ||w_OID_M|| ') - para ' ||w_maxPersonas|| ' personas como mucho, para ' ||w_tipo|| ' situada en el ' ||w_ubicacion|| ' del restaurante.');
END introducirNuevoTipoMesa;

/

CREATE OR REPLACE PROCEDURE nuevaMesa(
w_identificador IN MesasConcretas.identificador%TYPE,
w_OID_M IN Mesas.OID_M%TYPE)
IS
BEGIN
INSERT INTO MesasConcretas VALUES (w_identificador, w_OID_M);
DBMS_OUTPUT.PUT_LINE('Colocamos una mesa de tipo ' ||w_OID_M|| ' . El número identificador de esta mesa es: ' ||w_identificador);
END nuevaMesa;

/

CREATE OR REPLACE PROCEDURE incluirPedido(
w_codigoPedido IN Pedidos.codigoPedido%TYPE,
w_OID_PR IN PeticionesReserva.OID_PR%TYPE)
IS
BEGIN
INSERT INTO Pedidos VALUES (w_codigoPedido, w_OID_PR, w_OID_PR);
DBMS_OUTPUT.PUT_LINE('Se ha creado un nuevo pedido que corresponde a la petición de reserva ' ||w_OID_PR|| '. El código del pedido es: ' ||w_codigoPedido);
END incluirPedido;

/

CREATE OR REPLACE PROCEDURE pedirPlato(
w_codigoPedido IN Pedidos.codigoPedido%TYPE,
w_nombre IN Platos.nombre%TYPE,
w_racionPedida IN LineasDePedido.racionPedida%TYPE,
w_unidadesPedidas IN LineasDePedido.unidadesPedidas%TYPE)
IS
BEGIN
INSERT INTO LineasDePedido VALUES (w_codigoPedido, w_nombre, w_racionPedida, w_unidadesPedidas);
DBMS_OUTPUT.PUT_LINE('El plato se ha añadido correctamente al pedido del cliente.');
END pedirPlato;

/

CREATE OR REPLACE PROCEDURE introducirNuevoPlato(
w_nombre IN Platos.nombre%TYPE,
w_precioMediaRacion IN Platos.precioMediaRacion%TYPE,
w_precioRacion IN Platos.precioRacion%TYPE
)
IS
BEGIN
INSERT INTO Platos VALUES (w_nombre, w_precioMediaRacion, w_precioRacion);
DBMS_OUTPUT.PUT_LINE('Nuevo plato en la carta del restaurante: ' ||w_nombre|| '. Precio para media ración: ' ||w_precioMediaRacion|| ' €. Precio para una ración completa: ' ||w_precioRacion|| ' €.');
END introducirNuevoPlato;

/

CREATE OR REPLACE PROCEDURE nuevoProducto(
w_nombre IN Productos.nombre%TYPE,
w_unidadMedida IN Productos.unidadMedida%TYPE,
w_umbralExistencias IN Productos.umbralExistencias%TYPE
)
IS
BEGIN
INSERT INTO Productos VALUES (w_nombre, w_unidadMedida, w_umbralExistencias);
DBMS_OUTPUT.PUT_LINE('Nuevo producto: ' ||w_nombre);
DBMS_OUTPUT.PUT_LINE('La cantidad de este producto se mide en : ' ||w_unidadMedida);
DBMS_OUTPUT.PUT_LINE('El encargado de almacén tiene que informarle inmediatamente a la responsable de compras si en el almacén quedan menos de ' ||w_umbralExistencias|| ' ' ||w_unidadMedida);
END nuevoProducto;

/




CREATE OR REPLACE PROCEDURE nuevoGrupoDeProductos (
w_OID_GP IN GruposDeProductos.OID_GP%TYPE,
w_nombre IN GruposDeProductos.nombre%TYPE,
w_fechaCaducidad IN GruposDeProductos.fechaCaducidad%TYPE,
w_cantidadExistencia IN GruposDeProductos.cantidadExistencia%TYPE)
IS
CURSOR cur13 IS SELECT unidadMedida FROM Productos WHERE  nombre = w_nombre;
w_unidadMedida Productos.unidadMedida%TYPE;
BEGIN
OPEN cur13;
FETCH cur13 INTO w_unidadMedida;
INSERT INTO GruposDeProductos VALUES (w_OID_GP, w_nombre, w_fechaCaducidad, w_cantidadExistencia);
DBMS_OUTPUT.PUT_LINE('El almacén se ha abastecido de ' ||w_cantidadExistencia|| ' ' ||w_unidadMedida|| ' de ' ||w_nombre|| ' que van a caducar el ' ||w_fechaCaducidad);
DBMS_OUTPUT.PUT_LINE('El número identificador de este grupo de productos es: ' ||w_OID_GP);

CLOSE cur13;
END nuevoGrupoDeProductos;

/

CREATE OR REPLACE PROCEDURE tirarProductos (
w_OID_GP IN GruposDeProductos.OID_GP%TYPE)
IS
BEGIN
DELETE  FROM GruposDeProductos WHERE OID_GP = w_OID_GP;
END tirarProductos;

/

CREATE OR REPLACE PROCEDURE crearNuevaNota(
w_codigoNota IN Notas.codigoNota%TYPE
)
IS
BEGIN
INSERT INTO Notas VALUES (w_codigoNota, SYSDATE, NULL);
DBMS_OUTPUT.PUT_LINE('Se ha creado nueva nota.');
DBMS_OUTPUT.PUT_LINE('Código de la nota: ' ||w_codigoNota);
DBMS_OUTPUT.PUT_LINE('Fecha de elaboración ' ||SYSDATE);
DBMS_OUTPUT.PUT_LINE('La nota todavía no forma parte de ninguna lista de compras.');
END crearNuevaNota;

/

CREATE OR REPLACE PROCEDURE escribirEnLaNota(
w_codigoNota IN LineasDeNota.codigoNota%TYPE,
w_nombre IN LineasDeNota.nombre%TYPE,
w_cantidad IN LineasDeNota.cantidad%TYPE
)
IS
BEGIN
INSERT INTO LineasDeNota VALUES (w_nombre, w_cantidad, w_codigoNota);
DBMS_OUTPUT.PUT_LINE('Se ha añadido una línea en la nota.');
END escribirEnLaNota;

/



