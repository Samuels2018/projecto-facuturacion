
CREATE TABLE diccionario_tiempo_entrega (
  rowid INT NOT NULL AUTO_INCREMENT,
  entidad INT DEFAULT NULL,
  label VARCHAR(100) NOT NULL,
  activo INT NOT NULL,
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha_usuario DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

CREATE TABLE diccionario_validez_oferta (
  rowid INT NOT NULL AUTO_INCREMENT,
  entidad INT DEFAULT NULL,
  label VARCHAR(100) NOT NULL,
  activo INT NOT NULL,
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha_usuario DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;



CREATE TABLE `prod_factuguay_utilidades.diccionario_albarenes_venta_europa_diccionario` (
	`rowid` INT NULL DEFAULT NULL,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`class` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci'
)
COMMENT='Estados de la facturacion electronica'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `prod_factuguay_utilidades.diccionario_compra_europa_diccionario` (
	`rowid` INT NULL DEFAULT NULL,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`class` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci'
)
COMMENT='Estados de la facturacion electronica'
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;

CREATE TABLE `prod_factuguay_utilidades.diccionario_pedidos_europa_diccionario` (
	`rowid` INT NULL DEFAULT NULL,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`class` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci'
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `prod_factuguay_utilidades.diccionario_presupuesto_europa_diccionario` (
	`rowid` INT NULL DEFAULT NULL,
	`etiqueta` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`class` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci'
)
COMMENT='Estados de la facturacion electronica'
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;
