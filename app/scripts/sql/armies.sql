/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.7.19 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `armies` (
	`id` int ,
	`created` datetime ,
	`modified` datetime ,
	`name` varchar ,
	`units` int ,
	`attack_strategy` varchar ,
	`game_id` int 
); 
insert into `armies` (`id`, `created`, `modified`, `name`, `units`, `attack_strategy`, `game_id`) values('1','2019-12-01 18:58:29','2019-12-01 21:48:09','The Doom Corps','85','Random','1');
insert into `armies` (`id`, `created`, `modified`, `name`, `units`, `attack_strategy`, `game_id`) values('2','2019-12-01 18:58:29','2019-12-01 21:47:50','The Hellfire Legion','90','Weakest','1');
insert into `armies` (`id`, `created`, `modified`, `name`, `units`, `attack_strategy`, `game_id`) values('3','2019-12-01 18:58:29','2019-12-01 21:44:56','The Thunder Troops','100','Weakest','1');
insert into `armies` (`id`, `created`, `modified`, `name`, `units`, `attack_strategy`, `game_id`) values('4','2019-12-01 21:27:29','2019-12-01 21:48:09','The Hopeless','80','Random','1');
insert into `armies` (`id`, `created`, `modified`, `name`, `units`, `attack_strategy`, `game_id`) values('5','2019-12-01 21:42:19','2019-12-01 21:48:09','The Velvet Victors','95','Strongest','1');
