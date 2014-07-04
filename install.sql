/*
DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_products';
DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_category';
DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_tax';
DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_type';
DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_delivery';

INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_products', 'Products', 'Products', 100, 100, 1, 0, 1, 1);
INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_category', 'Category', 'Category', 100, 100, 1, 0, 1, 1);
INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_tax', 'Tax', 'Tax', 100, 100, 1, 0, 1, 1);
INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_type', 'Type', 'Type', 100, 100, 1, 0, 1, 1);
INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_delivery', 'Delivery', 'Delivery', 100, 100, 1, 0, 1, 1);


DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_category';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_category', 10, 'value', 'text', 'name', 'translate:category', '', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_category', 20, 'value', 'select_sql', 'parent', 'translate:parent_category', 'select id, name from rex_onlineshop_category', '', '', '', '', '', '', 0, 1);


DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_tax';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_tax', 10, 'value', 'text', 'name', 'translate:tax', '', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_tax', 10, 'value', 'text', 'percent', 'translate:percent', '', '', '', '', '', '', '', 0, 1);


DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_type';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_type', 10, 'value', 'text', 'name', 'translate:type', '', '', '', '', '', '', '', 0, 1);

DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_delivery';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_delivery', 10, 'value', 'text', 'name', 'translate:delivery', '', '', '', '', '', '', '', 0, 1);


DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_products';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 10, 'value', 'text', 'name', 'translate:product_name', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 20, 'value', 'lang_textarea', 'description', 'translate:description', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 30, 'value', 'text', 'price', 'translate:price', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 40, 'value', 'select_sql', 'rex_onlineshop_category', 'translate:category', 'select id, name from rex_onlineshop_category', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 50, 'value', 'text', 'size', 'translate:size', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 60, 'value', 'text', 'color', 'translate:color', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 70, 'value', 'text', 'dimension_h', 'translate:height', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 80, 'value', 'text', 'dimension_w', 'translate:width', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 90, 'value', 'text', 'dimension_d', 'translate:deep', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 100, 'value', 'text', 'weight', 'translate:weight', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 110, 'value', 'text', 'count', 'translate:count', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 120, 'value', 'text', 'status', 'translate:status', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 130, 'value', 'text', 'update', 'translate:update', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 140, 'value', 'select_sql', 'rex_onlineshop_tax', 'translate:tax', 'select id, name from rex_onlineshop_tax', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 150, 'value', 'select_sql', 'rex_onlineshop_type', 'translate:type', 'select id, name from rex_onlineshop_type', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 160, 'value', 'select_sql', 'rex_onlineshop_delivery', 'translate:delivery', 'select id, name from rex_onlineshop_delivery', '', '', '', '', '', '', 0, 1);
*/
