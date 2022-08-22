
INSERT INTO sub_menu (sub_menu_name,sub_menu_main,sub_menu_link,sub_menu_icon)
VALUES('Payroll Period - Semi',1,'timekeeping/payroll-period','fas fa-user-clock');
INSERT INTO sub_menu (sub_menu_name,sub_menu_main,sub_menu_link,sub_menu_icon)
VALUES('Payroll Period - Weekly',1,'timekeeping/payroll-period-weekly','fas fa-user-clock');






INSERT INTO main_menu (menu_desc,menu_icon) VALUES ('Employee File','fas fa-user-cog');

INSERT INTO sub_menu (sub_menu_name,sub_menu_main,sub_menu_link,sub_menu_icon)
VALUES('Emp. Master Data',2,'employee-files/employee-master-data','fas fa-list-ul');



SELECT * FROM sub_menu;


`user_rights`;



SELECT id,NAME,email FROM users;

SELECT id,menu_desc FROM main_menu;

SELECT sub_menu_name FROM sub_menu WHERE sub_menu_main = 1;

`id`
`sub_menu_desc`
`sub_menu_main`;


SELECT * FROM sub_menu WHERE ;


SELECT * FROM user_rights;

`user_rights`





SELECT * FROM sub_menu;





SELECT * FROM user_rights
INNER JOIN 
WHERE user_id = 1;


SELECT sub_menu.*
FROM `main_menu` 
INNER JOIN `sub_menu` ON `sub_menu_main` = main_menu.id
INNER JOIN `user_rights` ON sub_menu.id = `sub_menu_id`
WHERE user_id = 1;

`sub_menu`


`id`
`menu_desc`
`menu_icon`;


SELECT DISTINCT `id`, `menu_desc`, `menu_icon` FROM `user_rights` INNER JOIN `sub_menu` ON `sub_menu_main` = `main_menu`.`id` INNER JOIN `user_rights` ON `sub_menu`.`id` = `sub_menu_id` WHERE `user_rights`.`user_id` = 1

