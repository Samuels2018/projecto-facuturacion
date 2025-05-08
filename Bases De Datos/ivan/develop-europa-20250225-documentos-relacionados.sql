-- Creación de Movimientos y MovimientoDetalles --
CREATE TABLE `fi_europa_documentos_movimientos` (
	`rowid` INT NOT NULL AUTO_INCREMENT,
	`origen_documento` SET('fi_europa_albaranes_compras','fi_europa_compras') NULL DEFAULT NULL,
	`origen_fk_documento` INT NULL DEFAULT NULL,
	`destino_documento` SET('fi_europa_albaranes_compras','fi_europa_compras') NULL DEFAULT NULL,
	`destino_fk_documento` INT NULL DEFAULT NULL,
	`creado_fecha` DATETIME NOT NULL,
	`creado_fk_usuario` INT NULL DEFAULT NULL,
	INDEX `Index 1` (`rowid`),
	INDEX `Index 2` (`origen_documento`),
	INDEX `Index 3` (`origen_fk_documento`),
	INDEX `Index 4` (`destino_documento`),
	INDEX `Index 5` (`destino_fk_documento`)
) COMMENT='Tabla para La Cabecera de los movimientos';

CREATE TABLE `fi_europa_documentos_movimientos_detalles` (
	`rowid` INT NOT NULL AUTO_INCREMENT,
     `fk_documento_movimiento` INT NOT NULL,
	`origen_documento` SET('fi_europa_albaranes_compras','fi_europa_compras','prespuesto','pedido','albaran','factura') NULL DEFAULT NULL,
	`origen_fk_documento` INT NULL DEFAULT NULL,
	`origen_fk_documento_detalle` INT NULL DEFAULT NULL,
	`origen_cantidad` DECIMAL(16,3) NULL DEFAULT NULL,
	`destino_documento` SET('fi_europa_albaranes_compras','fi_europa_compras','prespuesto','pedido','albaran','factura') NULL DEFAULT NULL,
	`destino_fk_documento` INT NULL DEFAULT NULL,
	`destino_fk_documento_detalle` INT NULL DEFAULT NULL,
     `destino_cantidad` DECIMAL(16,3) NULL DEFAULT NULL,
	`creado_fecha` DATETIME NOT NULL,
	`creado_fk_usuario` INT NULL DEFAULT NULL,
	`borrado` INT NOT NULL DEFAULT '0',
	`borrado_fecha` DATETIME NULL DEFAULT NULL,
	`borrado_fk_usuario` INT NULL DEFAULT NULL,
	INDEX `Index 1` (`rowid`)
) COMMENT='Tabla para el detalle de los movimientos';

ALTER TABLE fi_europa_documentos_movimientos_detalles
ADD CONSTRAINT fk_documento_movimiento
FOREIGN KEY (fk_documento_movimiento) REFERENCES fi_europa_documentos_movimientos(rowid);

-- Agrega OrigenDocumento y OrigenDocumentoDetalle en fi_europa_compras_detalle
ALTER TABLE fi_europa_compras_detalle ADD COLUMN origen_documento VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE fi_europa_compras_detalle ADD COLUMN origen_fk_documento_detalle INT NULL DEFAULT NULL;

ALTER TABLE fi_europa_compras_detalle ADD COLUMN fk_estado_detalle INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';

ALTER TABLE fi_europa_albaranes_compras ADD COLUMN estado_movimiento INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';

ALTER TABLE fi_europa_albaranes_compras_detalle ADD COLUMN origen_documento VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_compras_detalle ADD COLUMN origen_fk_documento_detalle INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_compras_detalle ADD COLUMN fk_estado_detalle INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';


-- Para los estados de Albaranes Compras y Ventas
CREATE TABLE `diccionario_albarenes_compra_europa_diccionario` (
	`rowid` INT NULL DEFAULT NULL,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`class` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci'
)
COMMENT='Estados de la facturacion electronica'
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;

INSERT INTO `diccionario_albarenes_compra_europa_diccionario` (`rowid`, `etiqueta`, `class`) VALUES
	(0, 'Borrador', 'primary'),
	(1, 'Aprobado', 'danger'),
	(3, 'Entrega total', 'warning'),
	(4, 'Entrega parcial', 'danger'),
	(5, 'Cancelado', 'danger'),
	(6, 'Anulado', 'danger');

CREATE TABLE `diccionario_albarenes_venta_europa_diccionario` (
	`rowid` INT NULL DEFAULT NULL,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`class` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci'
)
COMMENT='Estados de la facturacion electronica'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;

CREATE TABLE `diccionario_transacciones_documentos` (
	`rowid` BIGINT NOT NULL AUTO_INCREMENT,
	`tabla` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`descripcion` VARCHAR(75) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`estilo` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`activo` TINYINT(1) NULL DEFAULT NULL,
	PRIMARY KEY (`rowid`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=7
AVG_ROW_LENGTH=2730
;

INSERT INTO `diccionario_transacciones_documentos` (`rowid`, `tabla`, `descripcion`, `estilo`, `activo`) VALUES
	(1, 'fi_europa_albaranes_compras', 'Albaranes de Compra', 'info', 1),
	(2, 'fi_europa_compras', 'Compras', 'info', 1),
	(3, 'fi_europa_presupuestos', 'Presupuestos Venta', 'info', 1),
	(4, 'fi_europa_pedidos', 'Pedido', 'success', 1),
	(5, 'fi_europa_albaranes_ventas', 'Albaran de Venta', 'success', 1),
	(6, 'fi_europa_facturas', 'Factura Electronica', 'success', 1);

-- Se borra para usar la columna estado --
ALTER TABLE fi_europa_albaranes_compras DROP COLUMN estado_movimiento;

-- Se agrega una columna de borrado para fi_europa_documentos_movimientos --
ALTER TABLE fi_europa_documentos_movimientos ADD COLUMN borrado INT NULL DEFAULT 0;
ALTER TABLE fi_europa_documentos_movimientos ADD COLUMN borrado_fecha DATETIME NULL DEFAULT NULL;
ALTER TABLE fi_europa_documentos_movimientos ADD COLUMN borrado_fk_usuario INT NULL DEFAULT NULL;




-- Agrega los valores a los diccionarios de estados
INSERT INTO `diccionario_pedidos_europa_diccionario` (`rowid`, `etiqueta`, `class`) VALUES
	(0, 'Borrador', 'info'),
	(1, 'Aprobado', 'success'),
	(3, 'Anulado', 'warning');
INSERT INTO `diccionario_presupuesto_europa_diccionario` (`rowid`, `etiqueta`, `class`) VALUES
	(0, 'Borrador', 'info'),
	(1, 'Aprobado', 'success'),
	(3, 'Anulado', 'warning');
INSERT INTO `diccionario_compra_europa_diccionario` (`rowid`, `etiqueta`, `class`) VALUES
	(0, 'Borrador', 'info'),
	(1, 'Aprobado', 'success'),
	(3, 'Anulado', 'warning');
INSERT INTO `diccionario_albarenes_venta_europa_diccionario` (`rowid`, `etiqueta`, `class`) VALUES
	(0, 'Borrador', 'info'),
	(1, 'Aprobado', 'success'),
	(3, 'Anulado', 'warning');

-- Agregar las columnas de MOVIMIENTO a FACTURA
ALTER TABLE fi_europa_facturas ADD COLUMN estado_movimiento INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';
ALTER TABLE fi_europa_facturas_detalle ADD COLUMN origen_documento VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE fi_europa_facturas_detalle ADD COLUMN origen_fk_documento_detalle INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_facturas_detalle ADD COLUMN fk_estado_detalle INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';


-- Agregar las columnas de MOVIMIENTO a PRESUPUESTO
ALTER TABLE fi_europa_presupuestos ADD COLUMN estado_movimiento INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';
ALTER TABLE fi_europa_presupuestos_detalle ADD COLUMN origen_documento VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE fi_europa_presupuestos_detalle ADD COLUMN origen_fk_documento_detalle INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_presupuestos_detalle ADD COLUMN fk_estado_detalle INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';

-- Agregar las columnas de MOVIMIENTO a PEDIDO
ALTER TABLE fi_europa_pedidos ADD COLUMN estado_movimiento INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';
ALTER TABLE fi_europa_pedidos_detalle ADD COLUMN origen_documento VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE fi_europa_pedidos_detalle ADD COLUMN origen_fk_documento_detalle INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_pedidos_detalle ADD COLUMN fk_estado_detalle INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';

-- Agregar las columnas de MOVIMIENTO a ALBARANES_VENTA
ALTER TABLE fi_europa_albaranes_ventas ADD COLUMN estado_movimiento INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';
ALTER TABLE fi_europa_albaranes_ventas_detalle ADD COLUMN origen_documento VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_ventas_detalle ADD COLUMN origen_fk_documento_detalle INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_ventas_detalle ADD COLUMN fk_estado_detalle INT NULL DEFAULT NULL COMMENT 'NULL: No relacionado, 0: Parcial, 1: total';

-- Agregar la columna FK_SERIE en todos los documentos
ALTER TABLE fi_europa_albaranes_compras ADD COLUMN fk_serie_configuracion INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_compras
ADD CONSTRAINT fk_albaran_compras_serie
FOREIGN KEY (fk_serie_configuracion) REFERENCES fi_europa_facturas_configuracion(rowid);

ALTER TABLE fi_europa_albaranes_ventas ADD COLUMN fk_serie_configuracion INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_ventas
ADD CONSTRAINT fk_albaran_ventas_serie
FOREIGN KEY (fk_serie_configuracion) REFERENCES fi_europa_facturas_configuracion(rowid);

ALTER TABLE fi_europa_pedidos ADD COLUMN fk_serie_configuracion INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_pedidos
ADD CONSTRAINT fk_pedidos_serie
FOREIGN KEY (fk_serie_configuracion) REFERENCES fi_europa_facturas_configuracion(rowid);

ALTER TABLE fi_europa_presupuestos ADD COLUMN fk_serie_configuracion INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_presupuestos
ADD CONSTRAINT fk_presupuestos_serie
FOREIGN KEY (fk_serie_configuracion) REFERENCES fi_europa_facturas_configuracion(rowid);

ALTER TABLE fi_europa_facturas ADD COLUMN fk_serie_configuracion INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_facturas
ADD CONSTRAINT fk_facturas_serie
FOREIGN KEY (fk_serie_configuracion) REFERENCES fi_europa_facturas_configuracion(rowid);

ALTER TABLE fi_europa_compras ADD COLUMN fk_serie_configuracion INT NULL DEFAULT NULL;
ALTER TABLE fi_europa_compras
ADD CONSTRAINT fk_compras_serie
FOREIGN KEY (fk_serie_configuracion) REFERENCES fi_europa_facturas_configuracion(rowid);