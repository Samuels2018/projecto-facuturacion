ALTER TABLE fi_europa_documento_plantilla ADD COLUMN tipo VARCHAR(50) NULL DEFAULT NULL;

-- Modificando el valor de campos_extra_detalle para que permite más contenido
ALTER TABLE campos_extra_detalle MODIFY COLUMN valor TEXT NULL;