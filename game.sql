/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : game

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-05-14 00:26:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for gm_admin
-- ----------------------------
DROP TABLE IF EXISTS `gm_admin`;
CREATE TABLE `gm_admin` (
  `admin_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `role_id` int(11) NOT NULL,
  `salt` char(6) NOT NULL,
  `cteated_time` char(11) DEFAULT NULL,
  `updated_time` char(11) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gm_admin
-- ----------------------------
INSERT INTO `gm_admin` VALUES ('127', 'admin', 'd4ff078eb0f7847381c1e0570309b874', '0', 'd5a52d', '1526223368', '1526223368');
INSERT INTO `gm_admin` VALUES ('128', 'haha', 'd4ff078eb0f7847381c1e0570309b874', '0', 'd5a52d', '1526223368', '1526223368');

-- ----------------------------
-- Table structure for gm_log
-- ----------------------------
DROP TABLE IF EXISTS `gm_log`;
CREATE TABLE `gm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '操作人id',
  `title` varchar(64) NOT NULL COMMENT '操作',
  `module` varchar(255) DEFAULT NULL COMMENT '操作模块',
  `url` varchar(255) DEFAULT NULL COMMENT '操作路径',
  `type` tinyint(2) NOT NULL COMMENT '类型  1:安全日志 2:操作日志 3:登录日志',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='安全日志表';

-- ----------------------------
-- Records of gm_log
-- ----------------------------

-- ----------------------------
-- Table structure for gm_member
-- ----------------------------
DROP TABLE IF EXISTS `gm_member`;
CREATE TABLE `gm_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户标识',
  `game_id` int(11) NOT NULL COMMENT '游戏ID',
  `openid` varchar(255) NOT NULL COMMENT '账号',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `img` text NOT NULL COMMENT '头像',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `realname` varchar(255) DEFAULT NULL COMMENT '真名',
  `sex` tinyint(2) NOT NULL COMMENT '性别 0:女  1:男',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '游戏币',
  `strongbox` int(11) DEFAULT NULL COMMENT '保险箱',
  `member_level` tinyint(2) NOT NULL DEFAULT '1' COMMENT '会员级别  1:普通会员 2:中级会员 3:高级会员',
  `manage_level` tinyint(2) NOT NULL DEFAULT '1' COMMENT '管理级别  1:普通会员 2:中级会员 3:高级会员',
  `loginnum` int(11) NOT NULL COMMENT '登陆次数',
  `ip` char(40) NOT NULL COMMENT 'ip地址',
  `machine_ip` char(30) NOT NULL COMMENT '机器码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态  0:正常  1:冻结账户',
  `create_time` char(11) NOT NULL COMMENT '注册时间',
  `login_time` char(11) DEFAULT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100012 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of gm_member
-- ----------------------------
INSERT INTO `gm_member` VALUES ('10001', '10001', '1554525252', '风清扬', '', '0', '张三', '1', '9999', '100', '1', '2', '1', '192.168.0.1', '192.168.0.2', '1', '1525663985', '1525663679');
INSERT INTO `gm_member` VALUES ('10002', '10002', '12365654566', '剑来', '', '10001', '李淳罡', '1', '8911', '66', '3', '3', '3', '192.168.0.1', '192.168.0.2', '0', '1525663582', '1525663681');
INSERT INTO `gm_member` VALUES ('10003', '10003', '12399654566', '陆地剑仙', '', '10001', '王五', '1', '6666', '100', '2', '3', '6', '192.168.0.1', '192.168.0.3', '0', '1525663999', '1525663697');
INSERT INTO `gm_member` VALUES ('10004', '10004', '12375654766', '令狐冲', '', '0', '李四', '1', '7580', '0', '1', '2', '10', '192.168.0.1', '192.168.0.3', '0', '1525664256', '1525664216');
INSERT INTO `gm_member` VALUES ('10005', '10005', '1554525252', '乔帮主', '', '0', '乔峰', '1', '9999', '100', '1', '2', '1', '192.168.0.1', '192.168.0.4', '1', '1525663679', '1525664012');
INSERT INTO `gm_member` VALUES ('10006', '10006', '12365654566', '独孤求败', '', '0', 'jake', '1', '8911', '66', '3', '3', '3', '192.168.0.1', '192.168.0.4', '0', '1525663781', '1525664045');
INSERT INTO `gm_member` VALUES ('10007', '10007', '12399654566', 'joffery', '', '0', 'lily', '0', '6666', '100', '2', '3', '6', '192.168.0.1', '192.168.0.1', '0', '1525664212', '1525664116');
INSERT INTO `gm_member` VALUES ('10008', '10008', '12375654766', '啥名字', '', '0', '不知', '1', '7580', '0', '1', '2', '10', '192.168.0.1', '192.168.0.1', '0', '1525664001', '1525664186');
INSERT INTO `gm_member` VALUES ('10009', '10009', '15225453552', '哈哈', '', '0', '哈哈哈', '0', '0', '0', '1', '1', '1', '192.168.0.2', '192.168.0.1', '0', '1525664009', '1525663423');
INSERT INTO `gm_member` VALUES ('100010', '100010', '12195652366', '十', '', '0', 'ten', '1', '0', '0', '1', '1', '1', '192.168.0.2', '192.168.0.3', '1', '1525664009', '1525663562');
INSERT INTO `gm_member` VALUES ('100011', '100011', '12199852366', '十一', '', '0', 'eleven', '1', '10', null, '1', '1', '1', '192.168.0.2', '192.168.0.2', '0', '1525663645', '1525663426');

-- ----------------------------
-- Table structure for gm_migrations
-- ----------------------------
DROP TABLE IF EXISTS `gm_migrations`;
CREATE TABLE `gm_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of gm_migrations
-- ----------------------------
INSERT INTO `gm_migrations` VALUES ('1', '2018_05_13_145050_entrust_setup_tables', '1');

-- ----------------------------
-- Table structure for gm_permissions
-- ----------------------------
DROP TABLE IF EXISTS `gm_permissions`;
CREATE TABLE `gm_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_time` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_time` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of gm_permissions
-- ----------------------------
INSERT INTO `gm_permissions` VALUES ('1', 'member-index', '用户列表', '显示所有用户数', '1526194596', '1526194596');
INSERT INTO `gm_permissions` VALUES ('2', 'restrict-index', '限制列表', '显示所有限制地址', '1526194596', '1526194596');

-- ----------------------------
-- Table structure for gm_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `gm_permission_role`;
CREATE TABLE `gm_permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `gm_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `gm_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of gm_permission_role
-- ----------------------------
INSERT INTO `gm_permission_role` VALUES ('1', '1');
INSERT INTO `gm_permission_role` VALUES ('1', '2');
INSERT INTO `gm_permission_role` VALUES ('2', '2');

-- ----------------------------
-- Table structure for gm_restrict
-- ----------------------------
DROP TABLE IF EXISTS `gm_restrict`;
CREATE TABLE `gm_restrict` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL COMMENT 'IP/机器码',
  `limit_login` tinyint(4) NOT NULL DEFAULT '0' COMMENT '限制登录  0: 正常   1:禁止',
  `limit_regist` tinyint(4) NOT NULL DEFAULT '0' COMMENT '限制注册  0: 正常   1:禁止',
  `content` text,
  `limit_time` char(11) DEFAULT '0' COMMENT '失效时间  0表示无限',
  `create_time` char(11) NOT NULL COMMENT '录入时间',
  `type` tinyint(1) NOT NULL COMMENT '类型:  0: IP  1:机器码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gm_restrict
-- ----------------------------
INSERT INTO `gm_restrict` VALUES ('1', '192.168.0.1', '0', '1', '测试1', '1527177600', '1525663426', '0');
INSERT INTO `gm_restrict` VALUES ('2', '192.168.0.2', '0', '1', '测试2', '1525669987', '1525663426', '0');
INSERT INTO `gm_restrict` VALUES ('3', '192.168.0.3', '1', '1', null, '0', '1525943083', '1');
INSERT INTO `gm_restrict` VALUES ('5', '192.168.0.5', '1', '0', '哈哈', '0', '1526014907', '1');

-- ----------------------------
-- Table structure for gm_roles
-- ----------------------------
DROP TABLE IF EXISTS `gm_roles`;
CREATE TABLE `gm_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_time` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_time` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of gm_roles
-- ----------------------------
INSERT INTO `gm_roles` VALUES ('1', 'owner', '拥有者', '天神般的权限', '1526194596', '1526223610');
INSERT INTO `gm_roles` VALUES ('2', 'noramal', '普通管理员', '一般', '1526223368', '1526214256');

-- ----------------------------
-- Table structure for gm_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `gm_role_admin`;
CREATE TABLE `gm_role_admin` (
  `admin_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`admin_id`,`role_id`),
  KEY `role_admin_role_id_foreign` (`role_id`),
  CONSTRAINT `role_admin_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `gm_admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_admin_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `gm_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of gm_role_admin
-- ----------------------------
INSERT INTO `gm_role_admin` VALUES ('127', '1');
INSERT INTO `gm_role_admin` VALUES ('128', '1');
INSERT INTO `gm_role_admin` VALUES ('128', '2');
