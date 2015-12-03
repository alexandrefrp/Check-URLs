-- DBTools Manager Professional (Enterprise Edition)
-- Database Dump for: monitools
-- Backup Generated in: 03/12/2015 15:52:07
-- Database Server Version: MySQL 5.5.32

-- USEGO

SET FOREIGN_KEY_CHECKS=0;
-- GO


--
-- Table: sites_log
--
CREATE TABLE `sites_log` 
(
	`id` integer (11), 
	`status` varchar (100), 
	`data_ini` datetime, 
	`data_fim` datetime, 
	`erro` varchar (900), 
	`contador` integer (11)
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

