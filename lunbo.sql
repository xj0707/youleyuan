/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : ChildrenGardenLocate

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-04-17 14:37:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lunbo
-- ----------------------------
DROP TABLE IF EXISTS `lunbo`;
CREATE TABLE `lunbo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `imageurl` varchar(255) DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL COMMENT '0为启用1为未启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='首页轮播图';

-- ----------------------------
-- Records of lunbo
-- ----------------------------
INSERT INTO `lunbo` VALUES ('1', 'a.gif', '1');
INSERT INTO `lunbo` VALUES ('2', 'b.gif', '1');
