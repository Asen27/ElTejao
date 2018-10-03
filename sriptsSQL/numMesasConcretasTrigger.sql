CREATE OR REPLACE TRIGGER numeroMesasConcretas
AFTER INSERT ON MesasConcretas
FOR EACH ROW
BEGIN
UPDATE Mesas SET numMesasConcretas = numMesasConcretas + 1 WHERE OID_M = :NEW.OID_M;
END;
/
CREATE OR REPLACE TRIGGER numeroMesasConcretas2
AFTER DELETE ON MesasConcretas
FOR EACH ROW
BEGIN
UPDATE Mesas SET numMesasConcretas = numMesasConcretas - 1 WHERE OID_M = :OLD.OID_M;
END;
/
CREATE OR REPLACE TRIGGER numeroMesasConcretas3
AFTER UPDATE OF OID_M ON MesasConcretas
FOR EACH ROW
BEGIN
UPDATE Mesas SET numMesasConcretas = numMesasConcretas + 1 WHERE OID_M = :NEW.OID_M;
UPDATE Mesas SET numMesasConcretas = numMesasConcretas - 1 WHERE OID_M = :OLD.OID_M;
END;