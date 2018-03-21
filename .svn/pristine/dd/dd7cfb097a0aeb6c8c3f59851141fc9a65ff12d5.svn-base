/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : ChildrenGardenLocate

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-02-28 13:55:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wbtime
-- ----------------------------
DROP TABLE IF EXISTS `wbtime`;
CREATE TABLE `wbtime` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wbid` int(11) DEFAULT NULL COMMENT '腕表ID',
  `up_ud_time` int(11) DEFAULT NULL COMMENT '上传ud的时间',
  `is_wifi` enum('1','0') DEFAULT NULL COMMENT '0是没有wifi信息 1是有wifi信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='记录wb上传ud信息';
