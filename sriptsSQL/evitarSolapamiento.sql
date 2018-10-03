CREATE OR REPLACE TRIGGER evitarSolaparReservas
BEFORE INSERT ON Reservas
FOR EACH ROW
BEGIN
IF sePuedeReservar(:NEW.identificador, :NEW.fechaYHora) = 'N' THEN
raise_application_error
(-20600,:NEW.codigo||' La mesa no está disponible para esta fecha y hora.');
END IF;
END;
/
CREATE OR REPLACE TRIGGER evitarSolaparReservas2
BEFORE UPDATE OF fechaYHora ON Reservas
FOR EACH ROW
BEGIN
IF sePuedeReservar(:OLD.identificador, :NEW.fechaYHora) = 'N' THEN
raise_application_error
(-20600,:NEW.codigo||' La mesa no está disponible para esta fecha y hora.');
END IF;
END;