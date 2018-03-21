/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : ChildrenGardenLocate

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-04-18 16:00:17
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('1', '测试1', 'a.jpg', '这是测试文件', '2017.1.17加入的测试文件，呵呵呵呵呵呵呵呵呵呵呵！', null, '1111');
INSERT INTO `news` VALUES ('2', '测试2', 'b.jpg', '这是测试文件2', '2017.1.17加入的测试文件2，哈哈哈哈哈哈哈！', null, '1111');
INSERT INTO `news` VALUES ('3', '测试3', 'c.jpg', '这是测试文件3', '2017.4.18加入的  的佛挡杀佛第三方但是发', null, '3654');
