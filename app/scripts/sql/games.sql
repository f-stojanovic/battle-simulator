/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.7.19 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `games` (
	`id` int ,
	`created` datetime ,
	`modified` datetime ,
	`name` varchar ,
	`status` varchar 
); 
insert into `games` (`id`, `created`, `modified`, `name`, `status`) values('1','2019-11-26 22:20:44','2019-11-26 22:20:44','Attack Of Od','active');
insert into `games` (`id`, `created`, `modified`, `name`, `status`) values('2','2019-11-29 21:05:27','2019-11-29 21:05:27','War Of Stront','inactive');
