-- ============================================================
-- CORRECCIÓN DE TRIGGERS - bdsiialmacen
-- Fecha: 2026-04-14
-- Descripción: Corrige doble deducción de stock, falta de
--              actualización en eliminaciones y condiciones
--              de transición de estado en todos los triggers.
-- ============================================================

USE `bdsiialmacen`;

-- ------------------------------------------------------------
-- 1. AgregarStock
--    Problema: disparaba con new.status = "finalizado" en
--    CUALQUIER UPDATE, incluso si ya era finalizado.
--    Fix: agregar condición old.status != "finalizado"
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS `AgregarStock`;

DELIMITER //
CREATE TRIGGER `AgregarStock`
AFTER UPDATE ON `entrada_productos`
FOR EACH ROW
IF (new.status = 'finalizado' AND old.status != 'finalizado')
THEN
    UPDATE productos
    SET stock = stock + new.cantidad
    WHERE id = new.id_producto;

    INSERT INTO `kardexes`
        (`tipomovimiento`, `id_movimiento`, `id_producto`, `cantidad`, `motivo`, `id_usuario`, `created_at`, `updated_at`)
    VALUES
        ('entrada', new.id_entrada, new.id_producto, new.cantidad, 'entrada de mercancia', new.id_usuario, current_timestamp(), current_timestamp());
END IF //
DELIMITER ;


-- ------------------------------------------------------------
-- 2. DescontarStock
--    Problema: al finalizar, el trigger volvía a descontar
--    stock que el PHP (SalidaproductoController::store) ya
--    había descontado al momento de captura → doble deducción.
--    Fix: en la rama "finalizado" solo insertar kardex (sin
--    tocar stock). En la rama "cancelado" solo insertar kardex
--    (el stock ya es restaurado por PHP en cancelarsalida).
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS `DescontarStock`;

DELIMITER //
CREATE TRIGGER `DescontarStock`
AFTER UPDATE ON `salidaproductos`
FOR EACH ROW
IF (new.status = 'finalizado' AND old.status != 'finalizado')
THEN
    -- Stock descontado por PHP en SalidaproductoController::store.
    -- Solo registrar en kardex.
    INSERT INTO `kardexes`
        (`tipomovimiento`, `id_movimiento`, `id_producto`, `cantidad`, `motivo`, `id_usuario`, `created_at`, `updated_at`)
    VALUES
        ('salida', new.id_salida, new.id_producto, new.cantidad, 'salida de mercancia', new.id_usuario, current_timestamp(), current_timestamp());

ELSEIF (new.status = 'cancelado' AND old.status != 'cancelado')
THEN
    -- Stock restaurado por PHP en SalidaController::cancelarsalida.
    -- Solo registrar en kardex.
    INSERT INTO `kardexes`
        (`tipomovimiento`, `id_movimiento`, `id_producto`, `cantidad`, `motivo`, `id_usuario`, `created_at`, `updated_at`)
    VALUES
        ('cancelacion', old.id_salida, old.id_producto, old.cantidad, 'cancelacion de mercancia', new.id_usuario, current_timestamp(), current_timestamp());
END IF //
DELIMITER ;


-- ------------------------------------------------------------
-- 3. kardexeliminaEnt
--    Problema: al eliminar una entrada_producto finalizada
--    solo insertaba en kardex pero NO descontaba el stock
--    que se había sumado al finalizarla.
--    Fix: agregar UPDATE para decrementar stock.
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS `kardexeliminaEnt`;

DELIMITER //
CREATE TRIGGER `kardexeliminaEnt`
AFTER DELETE ON `entrada_productos`
FOR EACH ROW
IF (old.status = 'finalizado')
THEN
    UPDATE productos
    SET stock = stock - old.cantidad
    WHERE id = old.id_producto;

    INSERT INTO `kardexes`
        (`tipomovimiento`, `id_movimiento`, `id_producto`, `cantidad`, `motivo`, `id_usuario`, `created_at`, `updated_at`)
    VALUES
        ('entrada', old.id_entrada, old.id_producto, old.cantidad, 'elimina entrada producto', old.id_usuario, current_timestamp(), current_timestamp());
END IF //
DELIMITER ;


-- ------------------------------------------------------------
-- 4. kardexeliminar
--    Problema: al eliminar una salidaproducto finalizada
--    solo insertaba en kardex pero NO restauraba el stock
--    que se había descontado.
--    Fix: agregar UPDATE para incrementar stock.
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS `kardexeliminar`;

DELIMITER //
CREATE TRIGGER `kardexeliminar`
AFTER DELETE ON `salidaproductos`
FOR EACH ROW
IF (old.status = 'finalizado')
THEN
    UPDATE productos
    SET stock = stock + old.cantidad
    WHERE id = old.id_producto;

    INSERT INTO `kardexes`
        (`tipomovimiento`, `id_movimiento`, `id_producto`, `cantidad`, `motivo`, `id_usuario`, `created_at`, `updated_at`)
    VALUES
        ('salida', old.id_salida, old.id_producto, old.cantidad, 'elimina salidaproducto', old.id_usuario, current_timestamp(), current_timestamp());
END IF //
DELIMITER ;


-- ------------------------------------------------------------
-- Triggers sin cambios (se recrean para dejar el script
-- completo y autocontenido)
-- ------------------------------------------------------------

DROP TRIGGER IF EXISTS `updateStatus`;

DELIMITER //
CREATE TRIGGER `updateStatus`
AFTER UPDATE ON `entradas`
FOR EACH ROW
IF (new.status = 'finalizado')
THEN
    UPDATE entrada_productos
    SET status = 'finalizado'
    WHERE id_entrada = new.id;
END IF //
DELIMITER ;


DROP TRIGGER IF EXISTS `updateStatusSalida`;

DELIMITER //
CREATE TRIGGER `updateStatusSalida`
AFTER UPDATE ON `salidas`
FOR EACH ROW
IF (new.status = 'finalizado')
THEN
    UPDATE salidaproductos
    SET status = 'finalizado'
    WHERE id_salida = new.id;

ELSEIF (new.status = 'cancelado')
THEN
    UPDATE salidaproductos
    SET status = 'cancelado'
    WHERE id_salida = new.id;
END IF //
DELIMITER ;

-- ============================================================
-- FIN DEL SCRIPT
-- ============================================================
