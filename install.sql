DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_products';
DELETE FROM `rex_xform_table` where `table_name`='rex_onlineshop_category';

INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_products', 'Products', 'Products', 100, 100, 1, 0, 1, 1);
INSERT INTO `rex_xform_table` (`status`, `table_name`, `name`, `description`, `list_amount`, `prio`, `search`, `hidden`, `export`, `import`) VALUES (1, 'rex_onlineshop_category', 'Category', 'Category', 100, 100, 1, 0, 1, 1);


DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_category';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_category', 10, 'value', 'text', 'name', 'translate:category', '', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_category', 20, 'value', 'select_sql', 'parent', 'translate:parent_category', 'select id, name from rex_onlineshop_category', '', '', '', '', '', '', 0, 1);


DELETE FROM `rex_xform_field` where `table_name`='rex_onlineshop_products';
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 10, 'value', 'text', 'name', 'translate:product_name', '', '0', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 100, 'value', 'select', 'status', 'translate:status', 'translate:online=0,translate:offline=1', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 110, 'value', 'select', 'kind', 'translate:product_kind', 'translate:coupon=0,translate:download=1,translate:package=2,translate:email=3', '2', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 130, 'value', 'text', 'size', 'translate:size', '', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 140, 'value', 'lang_textarea', 'description', 'translate:description', '', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 150, 'value', 'select_sql', 'category', 'translate:category', 'select id, name from rex_onlineshop_category', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 160, 'value', 'text', 'color', 'translate:color', '', '', '', '', '', '', '', 0, 1);
INSERT INTO `rex_xform_field` (`table_name`, `prio`, `type_id`, `type_name`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `list_hidden`, `search`) VALUES ('rex_onlineshop_products', 160, 'value', 'text', 'quantity', 'translate:quantity', '0', '', '', '', '', '', '', 0, 1);
