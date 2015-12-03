-- DBTools Manager Professional (Enterprise Edition)
-- Database Dump for: monitools
-- Backup Generated in: 03/12/2015 15:51:52
-- Database Server Version: MySQL 5.5.32

-- USEGO

SET FOREIGN_KEY_CHECKS=0;
-- GO


--
-- Table: sites
--
CREATE TABLE `sites` 
(
	`id` integer (11) NOT NULL AUTO_INCREMENT , 
	`data` datetime, 
	`url` varchar (100), 
	`tipo` integer (11), 
	`status` varchar (100), 
	`erro` varchar (900), 
	`descricao` varchar (100), 
	`info` varchar (900), 
	`ativo` integer (11) UNSIGNED , 
	`size` integer (11), 
	`limite` integer (11),
	PRIMARY KEY (`id`)
) TYPE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
-- GO

--
-- Dumping Table Foreign Keys
--

--
-- Dumping Triggers
--
SET FOREIGN_KEY_CHECKS=1;
-- GO

