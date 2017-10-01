/*
SQLyog v10.2 
MySQL - 5.5.18 : Database - auth
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`auth_db` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `auth_db`;

/*Table structure for table `t_auth` */

CREATE TABLE `t_auth` (
  `auth_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '安全令ID',
  `user_id` bigint(20) NOT NULL COMMENT '对应用户ID',
  `auth_default` int(1) NOT NULL COMMENT '是否是默认ID',
  `auth_name` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '安全令备注',
  `serial` varchar(17) CHARACTER SET utf8 NOT NULL COMMENT '安全令序列号',
  `region` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT '安全令地区',
  `secret` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '安全令密钥',
  `restore_code` varchar(10) NOT NULL COMMENT '安全令还原码',
  `auth_img` int(1) NOT NULL COMMENT '安全令图片',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `t_deleted_auth` */

CREATE TABLE `t_deleted_auth` (
  `auth_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '安全令ID',
  `user_id` bigint(20) NOT NULL COMMENT '对应用户ID',
  `auth_default` int(1) NOT NULL COMMENT '是否是默认ID',
  `auth_name` varchar(64) NOT NULL COMMENT '安全令备注',
  `serial` varchar(17) NOT NULL COMMENT '安全令序列号',
  `region` varchar(2) NOT NULL COMMENT '安全令地区',
  `secret` varchar(40) NOT NULL COMMENT '安全令密钥',
  `restore_code` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT '安全令还原码',
  `auth_img` int(1) NOT NULL COMMENT '安全令图片'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `t_donate` */

CREATE TABLE `t_donate` (
  `donate_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '捐赠ID',
  `donate_name` varchar(256) CHARACTER SET utf8 NOT NULL DEFAULT '匿名土豪' COMMENT '捐赠者昵称',
  `donate_time` int(11) unsigned NOT NULL COMMENT '捐赠时间',
  `donate_currency` char(64) CHARACTER SET utf8 NOT NULL DEFAULT '软妹币' COMMENT '捐赠币种',
  `donate_count` float unsigned NOT NULL COMMENT '捐赠数量',
  PRIMARY KEY (`donate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `t_sync_time` */

DROP TABLE IF EXISTS `t_sync_time`;

/*Table structure for table `t_sync_time` */

CREATE TABLE `t_sync_time` (
  `region` char(4) NOT NULL COMMENT '安全令地区',
  `sync` bigint(13) NOT NULL COMMENT '安全令服务器时间',
  `last_sync` datetime NOT NULL COMMENT '上次同步时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sync_time` */

LOCK TABLES `t_sync_time` WRITE;

insert  into `t_sync_time`(`region`,`sync`,`last_sync`) values ('US',0,'2013-06-20 00:00:00'),('CN',0,'2013-06-20 00:00:00'),('EU',0,'2013-06-20 00:00:00');

UNLOCK TABLES;

/*Table structure for table `t_user` */

CREATE TABLE `t_user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '用户名',
  `user_pass` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '用户密码',
  `user_right` int(4) NOT NULL DEFAULT '0' COMMENT '0默认,1共享,999封禁',
  `user_email` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '用户邮箱',
  `user_email_check_token` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '用户邮箱确认Token',
  `user_email_checked` int(1) NOT NULL COMMENT '用户邮箱是否确认,1已确认,0未确认',
  `user_register_time` datetime NOT NULL COMMENT '用户注册时间',
  `user_question` bigint(4) NOT NULL COMMENT '用户安全问题',
  `user_answer` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '用户安全问题答案',
  `user_email_find_password_token` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '用户通过邮件找回密码Token',
  `user_email_find_password_mode` int(1) NOT NULL COMMENT '1为可使用,0为不可使用',
  `user_password_reset_token` varchar(40) NOT NULL COMMENT '用户密码重置Token',
  `user_password_reset_token_used` int(1) NOT NULL COMMENT '1为已使用,0为未使用',
  `user_last_reset_password_time` int(11) NOT NULL DEFAULT '0' COMMENT '用户上次重置密码时间，用于防止COOKIE用户登录',
  `user_last_login_ip` varchar(128) NOT NULL COMMENT '上次登录IP',
  `user_this_login_ip` varchar(128) NOT NULL COMMENT '本次登录IP',
  `user_last_login_time` datetime NOT NULL COMMENT '用户上次登录时间',
  `user_this_login_time` datetime NOT NULL COMMENT '用户本次登录时间',
  `user_last_used_session_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次使用的SESSION时间，为使用非对称加密准备',
  `user_donated` int(1) NOT NULL DEFAULT '0' COMMENT '用户是否捐赠,1为已捐赠',
  `user_wechat_openid` varchar(64) DEFAULT NULL COMMENT '用户绑定的微信OPENID',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
