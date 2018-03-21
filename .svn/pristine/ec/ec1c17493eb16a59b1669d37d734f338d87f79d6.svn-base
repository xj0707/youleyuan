/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : ChildrenGardenLocate

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-01-16 17:06:40
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='普通管理员表';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', null, '1');
INSERT INTO `admin` VALUES ('2', 'admin1', 'e10adc3949ba59abbe56e057f20f883e', '1', null, '2');
INSERT INTO `admin` VALUES ('3', 'admin2', '202cb962ac59075b964b07152d234b70', '0', null, '2');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='安保人员';

-- ----------------------------
-- Records of security
-- ----------------------------
INSERT INTO `security` VALUES ('1', '张三', '001', '130789456', '1');
INSERT INTO `security` VALUES ('2', '李四', '002', '138654987', '2');
INSERT INTO `security` VALUES ('3', '王五', '003', '180963258', '2');

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
  `lastwifiname` varchar(255) DEFAULT NULL COMMENT '上一次报警WIFI的名字',
  `beginwarning` tinyint(1) DEFAULT '0' COMMENT '0为不是严重报警 1为严重报警',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='腕表';

-- ----------------------------
-- Records of watch
-- ----------------------------
INSERT INTO `watch` VALUES ('1', '00001', '', '', '0', '', '', '', '1484362396', '', '0');
INSERT INTO `watch` VALUES ('4', '861900039990378', null, null, '0', null, null, null, null, null, '0');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='腕表的移动轨迹';

-- ----------------------------
-- Records of watchlocation
-- ----------------------------
INSERT INTO `watchlocation` VALUES ('1', null, 'wifi_4', 'dizhi4', '-41', '1484362291');
INSERT INTO `watchlocation` VALUES ('2', '1', 'wifi_4', 'dizhi4', '-41', '1484362396');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='腕表使用历史记录';

-- ----------------------------
-- Records of watchusage
-- ----------------------------
INSERT INTO `watchusage` VALUES ('1', '00001', '13076050636', 'qwertyuiop', '34', null, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='wifi表';

-- ----------------------------
-- Records of wifi
-- ----------------------------
INSERT INTO `wifi` VALUES ('1', 'wifi_1', 'dizhi1', '-37', '10', '20', null, '0');
INSERT INTO `wifi` VALUES ('2', 'wifi_2', 'dizhi2', '-40', '20', '30', null, '0');
INSERT INTO `wifi` VALUES ('3', 'wifi_3', 'dizhi3', '-50', '28', '36', null, '0');
INSERT INTO `wifi` VALUES ('4', 'wifi_4', 'dizhi4', '-48', '22', '45', null, '1');
