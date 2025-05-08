-- Se agrega la columna orden en la tabla fi_europa_documento_plantilla
ALTER TABLE fi_europa_documento_plantilla ADD COLUMN orden INT NULL;
ALTER TABLE fi_europa_documento_plantilla ADD COLUMN titulo VARCHAR(200) NULL DEFAULT '';
ALTER TABLE fi_europa_documento_plantilla ADD COLUMN defecto INT NULL;

-- Se agrega la columna Plantilla en la tabla fi_europa_facturas_configuracion
ALTER TABLE fi_europa_facturas_configuracion ADD COLUMN plantilla_fk INT NULL;
ALTER TABLE fi_europa_facturas_configuracion
ADD CONSTRAINT fk_plantilla
FOREIGN KEY (plantilla_fk) REFERENCES fi_europa_documento_plantilla(rowid);


ALTER TABLE fi_europa_facturas ADD COLUMN fk_plantilla INT NULL;
ALTER TABLE fi_europa_facturas
ADD CONSTRAINT fk_factura_plantilla
FOREIGN KEY (fk_plantilla) REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_albaranes_compras ADD COLUMN fk_plantilla INT NULL;
ALTER TABLE fi_europa_albaranes_compras
ADD CONSTRAINT fk_albarancompra_plantilla
FOREIGN KEY (fk_plantilla) REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_albaranes_ventas ADD COLUMN fk_plantilla INT NULL;
ALTER TABLE fi_europa_albaranes_ventas
ADD CONSTRAINT fk_albaranventa_plantilla
FOREIGN KEY (fk_plantilla) REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_compras ADD COLUMN fk_plantilla INT NULL;
ALTER TABLE fi_europa_compras
ADD CONSTRAINT fk_compra_plantilla
FOREIGN KEY (fk_plantilla) REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_pedidos ADD COLUMN fk_plantilla INT NULL;
ALTER TABLE fi_europa_pedidos
ADD CONSTRAINT fk_pedido_plantilla
FOREIGN KEY (fk_plantilla) REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_presupuestos ADD COLUMN fk_plantilla INT NULL;
ALTER TABLE fi_europa_presupuestos
ADD CONSTRAINT fk_presupuesto_plantilla
FOREIGN KEY (fk_plantilla) REFERENCES fi_europa_documento_plantilla(rowid);
