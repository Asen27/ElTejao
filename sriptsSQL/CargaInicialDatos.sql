EXECUTE introducirNuevoTipoMesa(1, 8, 'interior', 'noFumadores');
EXECUTE nuevaMesa(1, 1);

EXECUTE introducirNuevoTipoMesa(2, 4, 'interior', 'fumadores');
EXECUTE nuevaMesa(2, 2);
EXECUTE nuevaMesa(3, 2);

EXECUTE introducirNuevoTipoMesa(3, 12, 'interior', 'noFumadores');
EXECUTE nuevaMesa(4, 3);

EXECUTE introducirNuevoTipoMesa(4, 2, 'interior', 'noFumadores');
EXECUTE nuevaMesa(5, 4);
EXECUTE nuevaMesa(6, 4);
EXECUTE nuevaMesa(7, 4);

EXECUTE introducirNuevoTipoMesa(5, 10, 'exterior', 'fumadores');
EXECUTE nuevaMesa(8, 5);

EXECUTE introducirNuevoTipoMesa(6, 4, 'exterior', 'noFumadores');
EXECUTE nuevaMesa(9, 6);

EXECUTE introducirNuevoTipoMesa(7, 4, 'exterior', 'fumadores');
EXECUTE nuevaMesa(10, 7);
EXECUTE nuevaMesa(11, 7);


EXECUTE introducirNuevoPlato('Ensaladilla rusa', 3.00, 6.00);
EXECUTE introducirNuevoPlato('Ensalada mixta', NULL, 6.50);
EXECUTE introducirNuevoPlato('Ensalada El Tejao', NULL, 5.00);
EXECUTE introducirNuevoPlato('Frito de la casa', 6.00, 10.00);
EXECUTE introducirNuevoPlato('Camarones fritos con aliño de pimientos', 6.00 ,10.00);
EXECUTE introducirNuevoPlato('Ensaladilla rusa con sal', 3.50, 6.00);
EXECUTE introducirNuevoPlato('Arroz caldoso con marisco', NULL, 9.00);
EXECUTE introducirNuevoPlato('Paella', NULL, 5.00);
EXECUTE introducirNuevoPlato('Mero a la planca', 6.50, 10.00);
EXECUTE introducirNuevoPlato('Espinacas con garbanzos', 4.50, 9.50);
EXECUTE introducirNuevoPlato('Crujiente de arroz en salsa Doñana', NULL, 12.00);

EXECUTE nuevoProducto('leche', 'botes', 10);
EXECUTE nuevoProducto('huevos', 'unidades', 30);
EXECUTE nuevoProducto('sal', 'paquetes', 2);
EXECUTE nuevoProducto('pechuga de pollo', 'kilogramos', 6);
EXECUTE nuevoProducto('queso', 'paquetes', 5);


INSERT INTO USUARIOS (NOMBRE, APELLIDOS, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO, PROVINCIA, DIRECCION, NICK, PASS, PUESTO, OID_USUARIO)
  VALUES ('Joaquina', 'Márquez Castro', '63579428E', to_date('6/1/1964'), 'joaquina5@yahoo.es', '634128956', 'Sevilla', null, 'joaquina6', '123456aA','GERENTE', 1);
  
  INSERT INTO USUARIOS (NOMBRE, APELLIDOS, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO, PROVINCIA, DIRECCION, NICK, PASS, PUESTO, OID_USUARIO)
  VALUES ('Alfonso', 'Fernández Navarro', '34836969L', null, 'alfonso90@yahoo.es', '634778090', 'Jaén', null, 'alfonso', '123456aB','ALMACEN', 2);




