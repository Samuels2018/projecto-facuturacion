DROP INDEX fk_formulario ON campos_extra_formularios;
ALTER TABLE campos_extra_formularios 
  ADD INDEX fk_formulario(fk_formulario);

DROP INDEX fk_formulario_2 ON campos_extra_formularios;
ALTER TABLE campos_extra_formularios 
  ADD INDEX fk_formulario_2(fk_formulario);

ALTER TABLE fi_europa_albaranes_compras
  ADD COLUMN agente_txt VARCHAR(400) DEFAULT '';
ALTER TABLE fi_europa_albaranes_compras
  ADD COLUMN fk_plantilla INT DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_compras 
  ADD CONSTRAINT fk_albarancompra_plantilla FOREIGN KEY (fk_plantilla)
    REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_albaranes_ventas
  ADD COLUMN agente_txt VARCHAR(400) DEFAULT '';
ALTER TABLE fi_europa_albaranes_ventas
  ADD COLUMN fk_plantilla INT DEFAULT NULL;
ALTER TABLE fi_europa_albaranes_ventas 
  ADD CONSTRAINT fk_albaranventa_plantilla FOREIGN KEY (fk_plantilla)
    REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_compras
  ADD COLUMN agente_txt VARCHAR(400) DEFAULT '';
ALTER TABLE fi_europa_compras
  ADD COLUMN fk_plantilla INT DEFAULT NULL;
ALTER TABLE fi_europa_compras 
  ADD CONSTRAINT fk_compra_plantilla FOREIGN KEY (fk_plantilla)
    REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_documento_plantilla
  ADD COLUMN orden INT DEFAULT NULL;
ALTER TABLE fi_europa_documento_plantilla
  ADD COLUMN titulo VARCHAR(200) DEFAULT '';
ALTER TABLE fi_europa_documento_plantilla
  ADD COLUMN defecto INT DEFAULT NULL;


ALTER TABLE fi_europa_facturas
  ADD COLUMN agente_txt VARCHAR(400) DEFAULT '';
ALTER TABLE fi_europa_facturas
  ADD COLUMN fk_plantilla INT DEFAULT NULL;
ALTER TABLE fi_europa_facturas
  ADD COLUMN proyecto_txt VARCHAR(255) DEFAULT '';
ALTER TABLE fi_europa_facturas 
  ADD CONSTRAINT fk_factura_plantilla FOREIGN KEY (fk_plantilla)
    REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_facturas_configuracion
  ADD COLUMN plantilla_fk INT DEFAULT NULL;
ALTER TABLE fi_europa_facturas_configuracion 
  ADD CONSTRAINT fk_plantilla FOREIGN KEY (plantilla_fk)
  REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_pedidos
  ADD COLUMN agente_txt VARCHAR(400) DEFAULT '';
ALTER TABLE fi_europa_pedidos
  ADD COLUMN fk_plantilla INT DEFAULT NULL;
ALTER TABLE fi_europa_pedidos 
  ADD CONSTRAINT fk_pedido_plantilla FOREIGN KEY (fk_plantilla)
    REFERENCES fi_europa_documento_plantilla(rowid);

ALTER TABLE fi_europa_presupuestos
  ADD COLUMN agente_txt VARCHAR(400) DEFAULT '';
ALTER TABLE fi_europa_presupuestos
  ADD COLUMN fk_plantilla INT DEFAULT NULL;
ALTER TABLE fi_europa_presupuestos 
  ADD CONSTRAINT fk_presupuestos_plantilla FOREIGN KEY (fk_plantilla)
    REFERENCES fi_europa_documento_plantilla(rowid);