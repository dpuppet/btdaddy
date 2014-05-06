/*
Navicat MySQL Data Transfer

Source Server         : jumeiRd
Source Server Version : 50531
Source Host           : localhost:3306
Source Database       : beauty

Target Server Type    : MYSQL
Target Server Version : 50531
File Encoding         : 65001

Date: 2013-11-29 18:52:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `mandate`
-- ----------------------------
DROP TABLE IF EXISTS `mandate`;
CREATE TABLE `mandate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '托管单流水号',
  `user_id` bigint(20) NOT NULL,
  `num` float(20,4) NOT NULL,
  `price` float(20,4) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `价格索引` (`price`) USING BTREE,
  KEY `用户ID索引` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mandate
-- ----------------------------

-- ----------------------------
-- Table structure for `transaction`
-- ----------------------------
DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` bigint(20) NOT NULL,
  `num` float NOT NULL,
  `price` float NOT NULL,
  `buyer_ticket` bigint(20) NOT NULL,
  `seller_ticket` bigint(20) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `托管单索引` (`buyer_ticket`,`seller_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of transaction
-- ----------------------------
