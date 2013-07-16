/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50045
Source Host           : localhost:3306
Source Database       : dz30

Target Server Type    : MYSQL
Target Server Version : 50045
File Encoding         : 65001

Date: 2013-07-16 18:03:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_cardelm_member`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelm_member`;
CREATE TABLE `pre_cardelm_member` (
  `memberid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) NOT NULL,
  PRIMARY KEY  (`memberid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelm_member
-- ----------------------------

-- ----------------------------
-- Table structure for `pre_cardelm_mokuai`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelm_mokuai`;
CREATE TABLE `pre_cardelm_mokuai` (
  `mokuaiid` smallint(3) unsigned NOT NULL auto_increment,
  `mokuainame` char(40) NOT NULL,
  `mokuaititle` char(40) NOT NULL,
  `mokuaiver` char(20) NOT NULL,
  `description` char(255) NOT NULL,
  `displayorder` smallint(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `upmokuai` char(40) NOT NULL,
  PRIMARY KEY  (`mokuaiid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelm_mokuai
-- ----------------------------
INSERT INTO `pre_cardelm_mokuai` VALUES ('1', 'shop', '联盟商家', 'V1.0', '商家联盟简介', '0', '1', '');

-- ----------------------------
-- Table structure for `pre_cardelm_setting`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelm_setting`;
CREATE TABLE `pre_cardelm_setting` (
  `skey` char(255) NOT NULL,
  `svalue` text NOT NULL,
  PRIMARY KEY  (`skey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelm_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `pre_cardelmserver_code`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_code`;
CREATE TABLE `pre_cardelmserver_code` (
  `codeid` mediumint(8) unsigned NOT NULL auto_increment,
  `type` char(20) NOT NULL,
  `key` char(40) NOT NULL,
  `value` text NOT NULL,
  `zhushi` char(255) NOT NULL,
  PRIMARY KEY  (`codeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_code
-- ----------------------------
INSERT INTO `pre_cardelmserver_code` VALUES ('2', 'admin', 'php', '&lt;?php\r\n?&gt;', '');
INSERT INTO `pre_cardelmserver_code` VALUES ('3', 'admin', 'test', '&lt;?php\r\nif(jdahsd){\r\n}', 'ע');

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
  `description` char(255) NOT NULL,
  `displayorder` smallint(3) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`mokuaiid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_mokuai
-- ----------------------------
INSERT INTO `pre_cardelmserver_mokuai` VALUES ('1', 'shop', '联盟商家', 'cf/041917n6ykgs6ke4qigasq.png', '商家联盟简介商家联盟简介商家联盟简介商家联盟简介商家联盟简介商家联盟简介商家联盟简介', '0', '0');
INSERT INTO `pre_cardelmserver_mokuai` VALUES ('2', 'goods', '商品展示', '', '配合联盟商家的商品展示的模块', '0', '0');
INSERT INTO `pre_cardelmserver_mokuai` VALUES ('3', 'dianping', '点评系统', '', '配合联盟商家的点评系统', '0', '0');
INSERT INTO `pre_cardelmserver_mokuai` VALUES ('4', 'cardelm', '卡益联盟', 'cf/112337fjsgivts6z0otoss.png', '配合联盟商家的一卡通系统', '0', '0');
INSERT INTO `pre_cardelmserver_mokuai` VALUES ('5', 'wxq123', '微信墙123', 'cf/112312rp14hiofsd46zhau.jpg', '配合联盟商家的微信系统', '0', '0');

-- ----------------------------
-- Table structure for `pre_cardelmserver_mokuaiver`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_mokuaiver`;
CREATE TABLE `pre_cardelmserver_mokuaiver` (
  `mokuaiverid` smallint(3) unsigned NOT NULL auto_increment,
  `mokuaivername` char(20) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `updatedescription` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `mokuaiid` varchar(255) NOT NULL,
  PRIMARY KEY  (`mokuaiverid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_mokuaiver
-- ----------------------------
INSERT INTO `pre_cardelmserver_mokuaiver` VALUES ('1', 'v1.0', '1373873541', '联盟商家的版本说明', '0', '1');

-- ----------------------------
-- Table structure for `pre_cardelmserver_page`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_page`;
CREATE TABLE `pre_cardelmserver_page` (
  `pageid` mediumint(8) unsigned NOT NULL auto_increment,
  `mokuaiid` smallint(3) NOT NULL,
  `pagetype` char(20) NOT NULL,
  `pagename` char(40) NOT NULL,
  `pagetitle` char(40) NOT NULL,
  `description` char(255) NOT NULL,
  PRIMARY KEY  (`pageid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_page
-- ----------------------------
INSERT INTO `pre_cardelmserver_page` VALUES ('1', '1', 'admin', 'setting', '设置', '系统自带的设置文件');
INSERT INTO `pre_cardelmserver_page` VALUES ('2', '1', 'admin', 'cats', '分类管理', '分类管理');
INSERT INTO `pre_cardelmserver_page` VALUES ('3', '2', 'admin', 'setting', '设置', '系统自带的设置文件');
INSERT INTO `pre_cardelmserver_page` VALUES ('6', '1', 'admin', 'shoplist', '商家管理', '商家管理');
INSERT INTO `pre_cardelmserver_page` VALUES ('7', '1', 'index', 'shopdisplay', '联盟商家', '前台的店铺展示');

-- ----------------------------
-- Table structure for `pre_cardelmserver_site`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_site`;
CREATE TABLE `pre_cardelmserver_site` (
  `siteid` mediumint(8) unsigned NOT NULL auto_increment,
  `sitegroupid` smallint(3) NOT NULL,
  `siteurl` varchar(255) NOT NULL,
  `salt` char(6) NOT NULL,
  `sitekey` char(32) NOT NULL,
  `searchurl` varchar(255) NOT NULL,
  `charset` char(10) NOT NULL,
  `clientip` char(15) NOT NULL,
  `version` char(50) NOT NULL,
  `installtime` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  `uninstalltime` int(10) unsigned NOT NULL,
  `regtime` int(10) NOT NULL,
  `realname` char(20) NOT NULL,
  `phone` char(80) NOT NULL,
  `address` char(100) NOT NULL,
  `jianyi` varchar(255) NOT NULL,
  `prov` char(30) NOT NULL,
  `city` char(30) NOT NULL,
  `dist` char(30) NOT NULL,
  `groupexpiry` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `mokuais` text NOT NULL,
  `shibiema` char(4) NOT NULL,
  `token` char(6) NOT NULL,
  `sitegroup` varchar(255) NOT NULL,
  PRIMARY KEY  (`siteid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_site
-- ----------------------------
INSERT INTO `pre_cardelmserver_site` VALUES ('1', '0', 'http://localhost/demo/dz3utf8/', '', '', '', 'UTF-8', '', 'X3', '1373644800', '1373731200', '0', '0', '', '', '', '', '', '', '', '1405267200', '0', '', '', '', '1');

-- ----------------------------
-- Table structure for `pre_cardelmserver_sitegroup`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cardelmserver_sitegroup`;
CREATE TABLE `pre_cardelmserver_sitegroup` (
  `sitegroupid` smallint(3) unsigned NOT NULL auto_increment,
  `sitegroupname` char(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `mokuaitest` varchar(255) NOT NULL,
  PRIMARY KEY  (`sitegroupid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cardelmserver_sitegroup
-- ----------------------------
INSERT INTO `pre_cardelmserver_sitegroup` VALUES ('1', '测试组', '1', '1');
