CREATE OR REPLACE TRIGGER evitarPedirMediaRacion
BEFORE INSERT ON LineasDePedido
FOR EACH ROW
DECLARE
CURSOR cur7 IS SELECT precioMediaRacion FROM Platos WHERE nombre = :NEW.nombre;
w_precioMediaRacion NUMBER(2);
BEGIN
OPEN cur7;
FETCH cur7 INTO w_precioMediaRacion;

IF w_precioMediaRacion IS NULL AND :NEW.racionPedida = 'media' THEN
raise_application_error
(-20600,:NEW.nombre||' No se puede pedir media ración de este plato.');
END IF;
CLOSE cur7;
END;