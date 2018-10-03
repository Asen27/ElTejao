CREATE OR REPLACE FUNCTION sePuedeReservar (
w_identificador IN MesasConcretas.identificador%TYPE,
w_fechaYHoraReserva IN PeticionesReserva.fechaYHoraReserva%TYPE)
RETURN CHAR
IS w_fechaYHora TIMESTAMP;

CURSOR cur2 IS SELECT fechaYHora FROM Reservas
WHERE identificador = w_identificador AND (fechaYHora BETWEEN w_fechaYHoraReserva - 4/24 AND w_fechaYHoraReserva + 4/24);
BEGIN 
OPEN cur2;
LOOP
FETCH cur2 INTO w_fechaYHora;
EXIT WHEN cur2%NOTFOUND;
END LOOP;
IF cur2%ROWCOUNT > 0 THEN
RETURN 'N';
ELSE
RETURN 'Y';
END IF;
CLOSE cur2;
END sePuedeReservar;

/

CREATE OR REPLACE FUNCTION calcularPrecio (
w_racionPedida IN LineasDePedido.racionPedida%TYPE,
w_unidadesPedidas IN LineasDePedido.unidadesPedidas%TYPE,
w_precioMediaRacion IN Platos.precioMediaRacion%TYPE,
w_precioRacion IN Platos.precioRacion%TYPE)
RETURN NUMBER
IS
BEGIN 
IF w_racionPedida = 'media' THEN
RETURN w_unidadesPedidas*w_precioMediaRacion;
ELSE
RETURN w_unidadesPedidas*w_precioRacion;
END IF;

END calcularPrecio;

/





