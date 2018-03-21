/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : ChildrenGardenLocate

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-05-08 16:49:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `lasttime` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '2' COMMENT '1为超级管理员 2.为普通管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='普通管理员表';

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
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titile` varchar(255) DEFAULT NULL COMMENT '标题',
  `image` varchar(255) DEFAULT NULL COMMENT '图片URL',
  `describe` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '正文内容',
  `time` int(11) DEFAULT NULL COMMENT '发布时间',
  `type` int(4) DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_type
-- ----------------------------
DROP TABLE IF EXISTS `news_type`;
CREATE TABLE `news_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(4) DEFAULT '1111' COMMENT '类型标识',
  `typename` varchar(255) DEFAULT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='新闻的类型';

-- ----------------------------
-- Table structure for security
-- ----------------------------
DROP TABLE IF EXISTS `security`;
CREATE TABLE `security` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `b_name` varchar(255) DEFAULT NULL COMMENT '保安姓名',
  `b_id` varchar(255) DEFAULT NULL COMMENT '保安编号',
  `b_phone` varchar(255) DEFAULT NULL COMMENT '保安电话',
  `type` tinyint(4) DEFAULT '2' COMMENT '1为工作2为休息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='安保人员';

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickename` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `sex` tinyint(1) DEFAULT '1' COMMENT '1保密2女3男',
  `tourl` varchar(255) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for watch
-- ----------------------------
DROP TABLE IF EXISTS `watch`;
CREATE TABLE `watch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wbid` varchar(255) NOT NULL COMMENT '腕表设备号',
  `p_phone` varchar(11) DEFAULT NULL COMMENT '当前家长的电话',
  `p_picurl` longtext COMMENT '当前家长的图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '0为没有绑定 1为绑定',
  `wifiname` varchar(255) DEFAULT NULL COMMENT 'wifi名称',
  `wifiMac` varchar(255) DEFAULT NULL COMMENT 'wifi MAC地址',
  `wifistrong` varchar(255) DEFAULT NULL COMMENT 'wifi强度',
  `lasttime` int(11) DEFAULT NULL COMMENT '上一次汇报时间',
  `lastwifimac` varchar(255) DEFAULT NULL COMMENT '上一次报警WIFI的名字',
  `beginwarning` tinyint(1) DEFAULT '0' COMMENT '0为不是严重报警 1为严重报警',
  `wb_phone` varchar(11) DEFAULT '0' COMMENT '腕表电话号码',
  `cmd_num` int(5) DEFAULT '1' COMMENT '流水号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1040 DEFAULT CHARSET=utf8 COMMENT='腕表';

-- ----------------------------
-- Table structure for watchlocation
-- ----------------------------
DROP TABLE IF EXISTS `watchlocation`;
CREATE TABLE `watchlocation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `watchusage_id` int(11) DEFAULT NULL COMMENT 'watchusage的主键id',
  `wifiname` varchar(255) DEFAULT NULL COMMENT 'wifi的名字',
  `wifimac` varchar(255) DEFAULT NULL,
  `wifistrong` varchar(255) DEFAULT NULL COMMENT 'wifi的强度',
  `time` int(11) DEFAULT NULL COMMENT '上传的时间',
  `longitude` double(16,10) DEFAULT NULL COMMENT '经度',
  `latitude` double(16,10) DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COMMENT='腕表的移动轨迹';

-- ----------------------------
-- Table structure for watchusage
-- ----------------------------
DROP TABLE IF EXISTS `watchusage`;
CREATE TABLE `watchusage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `watchname` varchar(255) DEFAULT NULL COMMENT '设备名',
  `w_phone` varchar(255) DEFAULT NULL COMMENT '绑定人的电话',
  `w_pic` longtext COMMENT '绑定人图片二进制',
  `start_time` int(11) DEFAULT NULL COMMENT '绑定开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '解绑时间',
  `state` tinyint(4) DEFAULT '1' COMMENT '0为未完成 1为完成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='腕表使用历史记录';

-- ----------------------------
-- Table structure for wbtime
-- ----------------------------
DROP TABLE IF EXISTS `wbtime`;
CREATE TABLE `wbtime` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wbid` varchar(255) DEFAULT NULL COMMENT '腕表ID',
  `up_ud_time` int(11) DEFAULT NULL COMMENT '上传ud的时间',
  `is_wifi` enum('1','0') DEFAULT NULL COMMENT '0是没有wifi信息 1是有wifi信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='记录wb上传ud信息';

-- ----------------------------
-- Table structure for wifi
-- ----------------------------
DROP TABLE IF EXISTS `wifi`;
CREATE TABLE `wifi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wifiname` varchar(255) DEFAULT NULL COMMENT 'wifi名字',
  `wifimac` varchar(255) DEFAULT NULL COMMENT 'MAC地址',
  `wifienergy` varchar(255) DEFAULT NULL COMMENT '信号强度',
  `wifi_x` varchar(255) DEFAULT NULL COMMENT 'wifi的x坐标',
  `wifi_y` varchar(255) DEFAULT NULL COMMENT 'wifi的y坐标',
  `wifi_wz` varchar(255) DEFAULT NULL COMMENT 'WiFi的位置名称',
  `iswarning` tinyint(1) DEFAULT NULL COMMENT '0为正常 1为警告wifi',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='wifi表';
