CREATE TABLE a_medida_cisma_cotizaciones (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_tercero INT NOT NULL,
  cotizacion_referencia VARCHAR(100) NOT NULL,
  cotizacion_fecha DATE DEFAULT NULL,
  cotizacion_nota VARCHAR(300) DEFAULT NULL,
  cotizacion_tags TEXT DEFAULT NULL,
  cotizacion_tipo_oferta INT NOT NULL DEFAULT 1 COMMENT '1 Normal 2 > Sicop o similar',
  fk_tercero_contacto INT DEFAULT NULL,
  cotizacion_tiempo_entrega INT NOT NULL DEFAULT 30 COMMENT 'dias naturales',
  cotizacion_validez_oferta INT NOT NULL DEFAULT 30 COMMENT 'dias naturales',
  fk_usuario_asignado INT DEFAULT NULL COMMENT 'Apunta a la tabla usuario',
  fk_categoria INT DEFAULT NULL COMMENT 'Apunta a a_medida_cisma_cotizaciones_diccionario_categorias',
  fk_estado_a_medida_cisma_estado_cotizaciones INT NOT NULL,
  fk_moneda INT NOT NULL DEFAULT 1 COMMENT 'Apunta al diccionario de monedas',
  creado_fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'CISMA Cotizaciones';

ALTER TABLE a_medida_cisma_cotizaciones 
  ADD INDEX fk_categoria(fk_categoria);

ALTER TABLE a_medida_cisma_cotizaciones 
  ADD INDEX fk_estado_a_medida_cisma_estado_cotizaciones(fk_estado_a_medida_cisma_estado_cotizaciones);

  CREATE TABLE a_medida_cisma_cotizaciones_MACHOTE_PDF (
  rowid INT NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(200) NOT NULL,
  texto TEXT DEFAULT NULL,
  orden INT NOT NULL,
  activo INT NOT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

CREATE TABLE a_medida_cisma_cotizaciones_PDF (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL,
  fk_machote_pdf INT DEFAULT NULL COMMENT 'Este campo se dejara en null YA que en cotizaciones , Anexos PDF el cliente podra añadir los suyos personalizados sin que este asociado a los templates por defecto',
  titulo VARCHAR(200) NOT NULL,
  texto TEXT DEFAULT NULL,
  orden INT NOT NULL,
  activo INT NOT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

ALTER TABLE a_medida_cisma_cotizaciones_PDF 
  ADD INDEX fk_cotizacion(fk_cotizacion);

ALTER TABLE a_medida_cisma_cotizaciones_PDF 
  ADD INDEX fk_machote_pdf(fk_machote_pdf);

  CREATE TABLE a_medida_cisma_cotizaciones_configuracion (
  rowid INT NOT NULL AUTO_INCREMENT,
  siguiente_borrador INT NOT NULL,
  siguiente_cotizacion INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'Cisma a Medida';

CREATE TABLE a_medida_cisma_cotizaciones_cotizaciones_actividades (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL COMMENT 'apunta a cotizaciones_v20',
  fk_diccionario_actividad INT DEFAULT NULL COMMENT 'apunta a la tabla diccionario_actividades',
  creado_fecha DATETIME NOT NULL,
  vencimiento_fecha DATETIME DEFAULT NULL COMMENT 'Cuando se vence la tarea',
  actividad_se_vencio INT NOT NULL DEFAULT 0 COMMENT 'cantidad de dias en que se ha vencido la tarea',
  creado_usuario INT NOT NULL,
  comentario VARCHAR(600) NOT NULL,
  fk_usuario_asignado INT NOT NULL COMMENT 'puede ser o no el usuario que debe hacer la actividad',
  fk_estado INT NOT NULL,
  comentario_cierre VARCHAR(150) DEFAULT NULL,
  tipo SET('timeline','tarea') NOT NULL DEFAULT 'timeline' COMMENT 'El timeline mustra todos el tarea solo las tareas',
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_cisma_cotizaciones_cotizaciones_servicios (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL COMMENT 'apunta a cotizaciones_v20',
  fk_producto INT DEFAULT NULL COMMENT 'Apunta a fk_producto, puede ser un servicio',
  cantidad INT DEFAULT NULL,
  precio_unitario DECIMAL(10, 2) DEFAULT NULL,
  precio_subtotal DECIMAL(10, 2) NOT NULL,
  precio_tipo_impuesto DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  precio_total DECIMAL(10, 2) DEFAULT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_usuario INT NOT NULL,
  comentario VARCHAR(600) DEFAULT NULL,
  fk_estado INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_cisma_cotizaciones_diccionario_crm_actividades (
  rowid INT NOT NULL AUTO_INCREMENT,
  enidad INT DEFAULT NULL,
  nombre VARCHAR(50) NOT NULL,
  color VARCHAR(30) NOT NULL,
  icono VARCHAR(100) DEFAULT NULL,
  activo INT NOT NULL DEFAULT 1 COMMENT '1=activo, 0=inactivo',
  creado_fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  creado_fk_usuario INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_cisma_cotizaciones_diccionario_crm_actividades_estado (
  rowid INT NOT NULL AUTO_INCREMENT,
  etiqueta VARCHAR(200) NOT NULL,
  color VARCHAR(20) NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_cisma_cotizaciones_estado (
  rowid INT NOT NULL AUTO_INCREMENT,
  etiqueta VARCHAR(30) NOT NULL,
  estilo VARCHAR(30) NOT NULL,
  activo INT NOT NULL DEFAULT 1,
  PRIMARY KEY (rowid)
)
ENGINE = MYISAM,
CHARACTER SET latin1,
CHECKSUM = 0,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_cisma_cotizaciones_recurso_humano (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL,
  fk_usuario INT NOT NULL,
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'REcurso humano (para el PDF y reportes) ';

ALTER TABLE a_medida_cisma_cotizaciones_recurso_humano 
  ADD INDEX fk_cotizacion(fk_cotizacion);

ALTER TABLE a_medida_cisma_cotizaciones_recurso_humano 
  ADD INDEX fk_usuario(fk_usuario);

  CREATE TABLE a_medida_cisma_cotizaciones_recurso_humano_atestados (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_usuario INT NOT NULL,
  descrpcion TEXT DEFAULT NULL,
  firma LONGTEXT DEFAULT NULL,
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

ALTER TABLE a_medida_cisma_cotizaciones_recurso_humano_atestados 
  ADD INDEX fk_usuario(fk_usuario);

  CREATE TABLE a_medida_redhous_cotizaciones_diccionario_crm_actividades_estado (
  rowid INT NOT NULL AUTO_INCREMENT,
  etiqueta VARCHAR(200) NOT NULL,
  color VARCHAR(20) NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_redhouse_cotizaciones (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_tercero INT NOT NULL,
  cotizacion_referencia VARCHAR(100) NOT NULL,
  cotizacion_fecha DATE DEFAULT NULL,
  cotizacion_nota VARCHAR(300) DEFAULT NULL,
  cotizacion_tags TEXT DEFAULT NULL,
  cotizacion_tipo_oferta INT NOT NULL DEFAULT 1 COMMENT '1 Normal 2 > Sicop o similar',
  fk_tercero_contacto INT DEFAULT NULL,
  cotizacion_tiempo_entrega INT NOT NULL DEFAULT 30 COMMENT 'dias naturales',
  cotizacion_validez_oferta INT NOT NULL DEFAULT 30 COMMENT 'dias naturales',
  fk_usuario_asignado INT DEFAULT NULL COMMENT 'Apunta a la tabla usuario',
  fk_categoria INT DEFAULT NULL COMMENT 'Apunta a a_medida_redhouse_cotizaciones_diccionario_categorias',
  fk_estado_a_medida_redhouse_estado_cotizaciones INT NOT NULL,
  fk_moneda INT NOT NULL DEFAULT 1 COMMENT 'Apunta al diccionario de monedas',
  cotizacion_proyecto VARCHAR(200) DEFAULT NULL,
  cotizacion_descripcion_proyecto VARCHAR(255) DEFAULT NULL,
  cotizacion_lugar_proyecto VARCHAR(200) NOT NULL,
  cotizacion_fecha_proyecto DATETIME DEFAULT NULL,
  cotizacion_contacto_proyecto VARCHAR(200) DEFAULT NULL,
  cotizacion_tipo_cambio DECIMAL(10, 2) DEFAULT NULL,
  creado_fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'CISMA Cotizaciones';

ALTER TABLE a_medida_redhouse_cotizaciones 
  ADD CONSTRAINT a_medida_redhouse_cotizaciones_ibfk_1 FOREIGN KEY (fk_estado_a_medida_redhouse_estado_cotizaciones)
    REFERENCES a_medida_redhouse_cotizaciones_estado(rowid);

ALTER TABLE a_medida_redhouse_cotizaciones 
  ADD CONSTRAINT a_medida_redhouse_cotizaciones_ibfk_2 FOREIGN KEY (fk_categoria)
    REFERENCES a_medida_redhouse_cotizaciones_diccionario_categorias(rowid);

CREATE TABLE a_medida_redhouse_cotizaciones_adjuntos (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL,
  label VARCHAR(255) NOT NULL,
  activo INT NOT NULL,
  descripcion VARCHAR(100) NOT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_fk_usuario INT NOT NULL,
  borrado INT NOT NULL,
  borrado_fecha DATETIME NOT NULL,
  borrado_fk_usuario INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

CREATE TABLE a_medida_redhouse_cotizaciones_cotizaciones_actividades (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL COMMENT 'apunta a cotizaciones_v20',
  fk_diccionario_actividad INT DEFAULT NULL COMMENT 'apunta a la tabla diccionario_actividades',
  creado_fecha DATETIME NOT NULL,
  vencimiento_fecha DATETIME DEFAULT NULL COMMENT 'Cuando se vence la tarea',
  actividad_se_vencio INT NOT NULL DEFAULT 0 COMMENT 'cantidad de dias en que se ha vencido la tarea',
  creado_usuario INT NOT NULL,
  comentario VARCHAR(600) NOT NULL,
  fk_usuario_asignado INT NOT NULL COMMENT 'puede ser o no el usuario que debe hacer la actividad',
  fk_estado INT NOT NULL,
  comentario_cierre VARCHAR(150) DEFAULT NULL,
  tipo SET('timeline','tarea') NOT NULL DEFAULT 'timeline' COMMENT 'El timeline mustra todos el tarea solo las tareas',
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_redhouse_cotizaciones_cotizaciones_servicios (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL COMMENT 'apunta a cotizaciones_v20',
  fk_producto INT DEFAULT NULL COMMENT 'Apunta a fk_producto, puede ser un servicio',
  cantidad INT DEFAULT NULL,
  precio_unitario DECIMAL(10, 2) DEFAULT NULL,
  precio_subtotal DECIMAL(10, 2) NOT NULL,
  precio_tipo_impuesto DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  precio_total DECIMAL(10, 2) DEFAULT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_usuario INT NOT NULL,
  comentario VARCHAR(600) DEFAULT NULL,
  cantidad_dias INT DEFAULT NULL,
  tipo_duracion VARCHAR(100) DEFAULT NULL,
  fk_estado INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_redhouse_cotizaciones_diccionario_categorias (
  rowid INT NOT NULL AUTO_INCREMENT,
  etiqueta VARCHAR(200) NOT NULL,
  estilo VARCHAR(200) NOT NULL,
  activo INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'RedHouse Categorias Propias';

CREATE TABLE a_medida_redhouse_cotizaciones_diccionario_crm_actividades (
  rowid INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  color VARCHAR(30) NOT NULL,
  icono VARCHAR(100) DEFAULT NULL,
  activo INT NOT NULL DEFAULT 1 COMMENT '1=activo, 0=inactivo',
  creado_fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  creado_fk_usuario INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_redhouse_cotizaciones_estado (
  rowid INT NOT NULL AUTO_INCREMENT,
  etiqueta VARCHAR(200) NOT NULL,
  estilo VARCHAR(100) NOT NULL,
  activo INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'RedHouse';

CREATE TABLE a_medida_redhouse_cotizaciones_recurso_humano (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL,
  fk_usuario INT NOT NULL,
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT NOT NULL DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
COMMENT = 'REcurso humano (para el PDF y reportes) ';

ALTER TABLE a_medida_redhouse_cotizaciones_recurso_humano 
  ADD INDEX fk_cotizacion(fk_cotizacion);

ALTER TABLE a_medida_redhouse_cotizaciones_recurso_humano 
  ADD INDEX fk_usuario(fk_usuario);

  CREATE TABLE a_medida_redhouse_diccionario_formas_pago (
  rowid INT NOT NULL AUTO_INCREMENT,
  label VARCHAR(100) NOT NULL,
  activo INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb3,
COLLATE utf8mb3_unicode_ci,
COMMENT = 'Guarda tipos de formas de pago';

CREATE TABLE a_medida_redhouse_orden_compra (
  rowid INT NOT NULL AUTO_INCREMENT,
  orden_consecutivo VARCHAR(50) DEFAULT NULL,
  fk_proveedor INT DEFAULT NULL,
  fk_proyecto INT DEFAULT NULL,
  fk_moneda INT DEFAULT NULL COMMENT 'Apuntar a Diccionario Monedas',
  fk_forma_pago INT DEFAULT NULL,
  orden_tipo_cambio DECIMAL(10, 2) DEFAULT NULL,
  orden_notas VARCHAR(255) DEFAULT NULL,
  fecha_creacion DATETIME DEFAULT NULL,
  fecha_vigencia DATETIME DEFAULT NULL,
  orden_estado INT DEFAULT NULL COMMENT '1 - en espera
2 - Procesado
3 - Completado 
4 - Cancelado',
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado TINYINT(1) DEFAULT 0,
  borrado_fecha DATETIME DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

ALTER TABLE a_medida_redhouse_orden_compra 
  ADD CONSTRAINT fk_proveedor FOREIGN KEY (fk_proveedor)
    REFERENCES fi_terceros(rowid) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE a_medida_redhouse_orden_compra 
  ADD CONSTRAINT fk_proyecto FOREIGN KEY (fk_proyecto)
    REFERENCES a_medida_redhouse_proyecto(rowid) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE a_medida_redhouse_orden_compra_servicios (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_orden INT NOT NULL COMMENT 'apunta  a_medida_redhouse_orden_compra',
  fk_producto INT DEFAULT NULL COMMENT 'Apunta a fk_producto, puede ser un servicio',
  cantidad INT DEFAULT NULL,
  precio_unitario DECIMAL(10, 2) DEFAULT NULL,
  precio_subtotal DECIMAL(10, 2) NOT NULL,
  precio_tipo_impuesto DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  precio_total DECIMAL(10, 2) DEFAULT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_usuario INT NOT NULL,
  comentario VARCHAR(600) DEFAULT NULL,
  cantidad_dias INT DEFAULT NULL,
  tipo_duracion VARCHAR(100) DEFAULT NULL,
  fk_estado INT DEFAULT 1,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

CREATE TABLE a_medida_redhouse_proyecto (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_cotizacion INT NOT NULL,
  proyecto_consecutivo VARCHAR(100) DEFAULT NULL,
  proyecto_fecha DATETIME DEFAULT NULL,
  proyecto_descripcion VARCHAR(255) DEFAULT NULL,
  proyecto_lugar VARCHAR(100) DEFAULT NULL,
  proyecto_contacto VARCHAR(200) DEFAULT NULL,
  proyecto_tipo_cambio DECIMAL(10, 2) DEFAULT NULL,
  monto_compras_miselaneas DECIMAL(10, 2) DEFAULT 0.00,
  proyecto_estado INT DEFAULT NULL,
  creado_fecha DATETIME DEFAULT NULL,
  creado_fk_usuario INT DEFAULT NULL,
  borrado INT DEFAULT NULL,
  borrado_fecha INT DEFAULT NULL,
  borrado_fk_usuario INT DEFAULT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci;

CREATE TABLE a_medida_redhouse_proyecto_presupuesto (
  rowid INT NOT NULL AUTO_INCREMENT,
  fk_proyecto INT NOT NULL COMMENT 'apunta  a_medida_redhouse_proyecto',
  fk_producto INT DEFAULT NULL COMMENT 'Apunta a fk_producto, puede ser un servicio',
  cantidad INT DEFAULT NULL,
  precio_unitario DECIMAL(10, 2) DEFAULT NULL,
  precio_subtotal DECIMAL(10, 2) NOT NULL,
  precio_tipo_impuesto DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  precio_total DECIMAL(10, 2) DEFAULT NULL,
  creado_fecha DATETIME NOT NULL,
  creado_usuario INT NOT NULL,
  comentario VARCHAR(600) DEFAULT NULL,
  cantidad_dias INT DEFAULT NULL,
  tipo_duracion VARCHAR(100) DEFAULT NULL,
  fk_estado INT NOT NULL,
  PRIMARY KEY (rowid)
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci;

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

