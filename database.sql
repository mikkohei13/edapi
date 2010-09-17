-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.22-log

-- Eduskunta-rajapinnan tietokantataulut

CREATE TABLE `edapi_aanestykset` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aanestystunniste` varchar(20) NOT NULL,
  `edustaja` varchar(100) NOT NULL,
  `valinta` varchar(10) NOT NULL,
  `puolue` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
)

CREATE TABLE `edapi_meta` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aanestystunniste` varchar(20) NOT NULL,
  `aanestys` int(10) unsigned NOT NULL,
  `vuosi` int(10) unsigned NOT NULL,
  `istunto` int(10) unsigned NOT NULL,
  `otsikko` varchar(500) NOT NULL,
  `pvm` int(10) unsigned NOT NULL,
  `url` varchar(500) NOT NULL,
  `kasittely` varchar(200) NOT NULL,
  `asettelu` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
)
