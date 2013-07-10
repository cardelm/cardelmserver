/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50045
Source Host           : localhost:3306
Source Database       : dz3utf8

Target Server Type    : MYSQL
Target Server Version : 50045
File Encoding         : 65001

Date: 2013-07-11 03:02:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_cardelmserver_menu`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_menu`;
CREATE TABLE `pre_cardelmserver_menu` (
  `menuid` smallint(3) unsigned NOT NULL auto_increment,
  `menuname` char(20) NOT NULL,
  `menutitle` char(20) NOT NULL,
  `upid` smallint(3) NOT NULL,
  `displayorder` smallint(3) NOT NULL,
  PRIMARY KEY  (`menuid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_menu
-- ----------------------------
INSERT INTO `pre_cardelmserver_menu` VALUES ('1', 'system', '系统设置', '0', '0');
INSERT INTO `pre_cardelmserver_menu` VALUES ('2', 'setting', '基础设置', '1', '0');
INSERT INTO `pre_cardelmserver_menu` VALUES ('3', 'sitegroup', '站长组', '1', '1');
INSERT INTO `pre_cardelmserver_menu` VALUES ('4', 'site', '站长管理', '1', '2');
INSERT INTO `pre_cardelmserver_menu` VALUES ('5', 'menu', '菜单管理', '1', '3');
INSERT INTO `pre_cardelmserver_menu` VALUES ('6', 'mokuai', '模块管理', '1', '4');

-- ----------------------------
-- Table structure for `pre_cardelmserver_mokuai`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_mokuai`;
CREATE TABLE `pre_cardelmserver_mokuai` (
  `mokuaiid` smallint(3) unsigned NOT NULL auto_increment,
  `mokuainame` char(20) NOT NULL,
  `mokuaititle` char(20) NOT NULL,
  `mokuaiico` char(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`mokuaiid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_mokuai
-- ----------------------------
INSERT INTO `pre_cardelmserver_mokuai` VALUES ('1', 'shop', '联盟商家', '', '0');

-- ----------------------------
-- Table structure for `pre_cardelmserver_sitegroup`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_sitegroup`;
CREATE TABLE `pre_cardelmserver_sitegroup` (
  `sitegroupid` smallint(3) unsigned NOT NULL auto_increment,
  `sitegroupname` char(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`sitegroupid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_sitegroup
-- ----------------------------
INSERT INTO `pre_cardelmserver_sitegroup` VALUES ('1', '测试组', '0');
