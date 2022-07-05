INSERT INTO `modules_mtto` (`description_module_mtto`)
VALUES ('Procedimientos');

CREATE TABLE `procedures` (
  `id_procedure` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `description` text NULL
) COLLATE 'utf8mb4_unicode_ci';

DROP TABLE IF EXISTS `procedures`;
CREATE TABLE `procedures` (
  `id_procedure` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NULL,
  `date` date NULL,
  `objective` longtext NULL,
  `scope` longtext NULL,
  `definitions` longtext NULL,
  `position_1` varchar(255) NULL,
  `number_workers_1` varchar(255) NULL,
  `responsibilities_1` longtext NULL,
  `position_2` varchar(255) NULL,
  `number_workers_2` varchar(255) NULL,
  `responsibilities_2` longtext NULL,
  `recommendations` longtext NULL,
  `planning` longtext NULL,
  `monthly_maintenance` longtext NULL,
  `semi_annual_maintenance` longtext NULL,
  `maintenance_2_years` longtext NULL,
  `equipment_tools` longtext NULL,
  `records` longtext NULL,
  `confidentiality_note` longtext NULL,
  `version` longtext NULL,
  `change_reason` longtext NULL
);

ALTER TABLE `inspection_of_mant_teams`
ADD `frequency_type` tinyint NULL,
ADD `frequency_type_text` varchar(255) NULL AFTER `frequency_type`,
ADD `frequency_value_hours` double NULL AFTER `frequency_type_text`,
ADD `frequency_value_date` date NULL AFTER `frequency_value_hours`;

# es necesario darle permisos del modulo al usuario

CREATE TABLE `team_activities` (
  `id_team_activity` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fk_teams_units` int NOT NULL,
  `fk_user_id` int NOT NULL,
  `hours_worked` double NOT NULL,
  `date` date NOT NULL,
  `comment` text NULL
);

ALTER TABLE `teams_units_rsu`
ADD `accumulated_hours_worked` double NOT NULL DEFAULT '0';

ALTER TABLE `team_activities`
CHANGE `hours_worked` `hours_worked` double NULL AFTER `fk_user_id`;

ALTER TABLE `team_activities`
ADD `type` tinyint NOT NULL DEFAULT '1';

CREATE TABLE `requisitions` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NOT NULL,
  `admin_id` int NULL,
  `equipment` varchar(255) NOT NULL,
  `requested_items` text NOT NULL,
  `place` varchar(255) NOT NULL,
  `request_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `status` int NOT NULL,
  `status_text` varchar(255) NOT NULL
);

INSERT INTO `modules_mtto` (`description_module_mtto`)
VALUES ('Administrador de requisiciones');

ALTER TABLE `teams_units_rsu`
CHANGE `description_teams_units` `description_teams_units` text NOT NULL AFTER `plate_teams_units`