/*
SQLyog v10.2 
MySQL - 5.5.45-log : Database - yukatang
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`yukatang` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `yukatang`;

/*Table structure for table `ykt_action_log` */

DROP TABLE IF EXISTS `ykt_action_log`;

CREATE TABLE `ykt_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户',
  `controller` char(50) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` char(50) NOT NULL COMMENT '动作',
  `data` text NOT NULL COMMENT '数据',
  `time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_address` */

DROP TABLE IF EXISTS `ykt_address`;

CREATE TABLE `ykt_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `realname` char(50) NOT NULL,
  `province` smallint(5) NOT NULL,
  `city` smallint(5) DEFAULT NULL,
  `district` char(50) NOT NULL,
  `street` varchar(200) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_area` */

DROP TABLE IF EXISTS `ykt_area`;

CREATE TABLE `ykt_area` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) NOT NULL,
  `areaname` varchar(50) NOT NULL,
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=4959 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_article` */

DROP TABLE IF EXISTS `ykt_article`;

CREATE TABLE `ykt_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '添加用户id',
  `cat_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '标题',
  `title_img` char(200) NOT NULL DEFAULT '' COMMENT '标题图片',
  `summary` char(200) NOT NULL DEFAULT '' COMMENT '摘要',
  `link` varchar(100) NOT NULL DEFAULT '' COMMENT '外链',
  `content` text NOT NULL COMMENT '内容',
  `sort` int(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `tags` char(200) NOT NULL DEFAULT '' COMMENT '标签',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `remark` char(200) NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1草稿 2上线',
  `comment_num` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `collect_num` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `like_num` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `views` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '目击数',
  `source` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文章来源',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_article_category` */

DROP TABLE IF EXISTS `ykt_article_category`;

CREATE TABLE `ykt_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_article_collect` */

DROP TABLE IF EXISTS `ykt_article_collect`;

CREATE TABLE `ykt_article_collect` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏id',
  `aid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户',
  PRIMARY KEY (`acid`),
  UNIQUE KEY `uid_aid` (`uid`,`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_article_comment` */

DROP TABLE IF EXISTS `ykt_article_comment`;

CREATE TABLE `ykt_article_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL COMMENT '文章id',
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `content` text NOT NULL COMMENT '评论内容',
  `top_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属评论id',
  `likes` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '赞',
  `dislikes` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '踩',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '审核',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `to_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '针对评论用户uid',
  `reply_num` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_auth_group` */

DROP TABLE IF EXISTS `ykt_auth_group`;

CREATE TABLE `ykt_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rule_ids` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_auth_group_user` */

DROP TABLE IF EXISTS `ykt_auth_group_user`;

CREATE TABLE `ykt_auth_group_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_ids` char(100) NOT NULL DEFAULT '' COMMENT '组id串',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `remark` char(200) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_group_id` (`id`,`uid`),
  KEY `uid` (`id`),
  KEY `group_id` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_auth_rule` */

DROP TABLE IF EXISTS `ykt_auth_rule`;

CREATE TABLE `ykt_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `pid` smallint(6) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL,
  `sort` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_category` */

DROP TABLE IF EXISTS `ykt_category`;

CREATE TABLE `ykt_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) NOT NULL,
  `pid` int(11) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_consign` */

DROP TABLE IF EXISTS `ykt_consign`;

CREATE TABLE `ykt_consign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `cat_id` tinyint(1) unsigned NOT NULL COMMENT '分类id',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1未审核 2通知修改 3通知寄货 4收到 5通过 6未通过',
  `public_attr_ids` char(100) NOT NULL COMMENT '公有属性',
  `goods_name` char(100) NOT NULL DEFAULT '' COMMENT '寄售商品名称',
  `goods_img` char(100) NOT NULL COMMENT '商品封面',
  `goods_desc` text NOT NULL COMMENT '商品简介',
  `goods_param` text NOT NULL COMMENT '商品参数',
  `goods_detail` text NOT NULL COMMENT '商品详细',
  `original` char(100) NOT NULL COMMENT '原作',
  `author` char(100) NOT NULL COMMENT '作者',
  `cp` char(100) NOT NULL COMMENT 'CP',
  `sample` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '有否有样品',
  `claim` char(200) NOT NULL COMMENT '特殊要求',
  `treat_default` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '瑕疵处理 1默认 0到货决定',
  `attr_des` text NOT NULL COMMENT '套装描述',
  `attr_val` text NOT NULL COMMENT '套装值',
  `express_num` char(100) NOT NULL COMMENT '快递单号',
  `multi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有套装0 1 2',
  `gid` int(10) unsigned NOT NULL COMMENT '商品id',
  `modify_desc` char(255) NOT NULL DEFAULT '' COMMENT '修改原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_expressage` */

DROP TABLE IF EXISTS `ykt_expressage`;

CREATE TABLE `ykt_expressage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(10) unsigned NOT NULL COMMENT '地区id',
  `init_weight` decimal(10,2) unsigned NOT NULL COMMENT '首重',
  `init_cost` decimal(10,2) unsigned NOT NULL COMMENT '首费',
  `extra_weight` decimal(10,2) unsigned NOT NULL COMMENT '续重',
  `extra_cost` decimal(10,2) unsigned NOT NULL COMMENT '续费',
  PRIMARY KEY (`id`),
  UNIQUE KEY `area_id` (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_feedback` */

DROP TABLE IF EXISTS `ykt_feedback`;

CREATE TABLE `ykt_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `user` char(100) NOT NULL DEFAULT 'anonymous',
  `content` char(200) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_goods` */

DROP TABLE IF EXISTS `ykt_goods`;

CREATE TABLE `ykt_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品状态',
  `public_attr_ids` varchar(100) NOT NULL DEFAULT '' COMMENT '共有属性列表',
  `multi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '私有属性个数',
  `attr_des` text NOT NULL COMMENT '私有属性描述',
  `goods_name` varchar(200) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_img` varchar(100) NOT NULL DEFAULT '' COMMENT '商品图片',
  `goods_param` text NOT NULL COMMENT '商品参数',
  `goods_desc` text NOT NULL COMMENT '商品简介',
  `goods_detail` text NOT NULL COMMENT '商品详细内容',
  `goods_ps` text NOT NULL COMMENT '附注',
  `cp` char(200) NOT NULL DEFAULT '' COMMENT 'CP',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `onsale_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预售到现货时间',
  `on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上架',
  `pre_sale` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否预告',
  `cid` int(10) unsigned NOT NULL COMMENT '寄售id',
  `c_uid` int(10) unsigned NOT NULL COMMENT '委托人uid',
  `c_user` char(100) NOT NULL DEFAULT '' COMMENT '委托人user',
  `original` char(200) NOT NULL DEFAULT '' COMMENT '原作',
  `author` char(100) NOT NULL DEFAULT '' COMMENT '作者',
  `express_charge` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '要运费 0否 1是',
  `views` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '查看数',
  `comment_num` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `collect_num` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `like_num` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  PRIMARY KEY (`id`),
  KEY `c_uid` (`c_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1631 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_goods_collect` */

DROP TABLE IF EXISTS `ykt_goods_collect`;

CREATE TABLE `ykt_goods_collect` (
  `gcid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏id',
  `gid` int(10) unsigned NOT NULL COMMENT '收藏商品id',
  `cat_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `uid` int(10) unsigned NOT NULL COMMENT '收藏用户uid',
  PRIMARY KEY (`gcid`),
  UNIQUE KEY `g_c_u` (`gid`,`cat_id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_goods_comment` */

DROP TABLE IF EXISTS `ykt_goods_comment`;

CREATE TABLE `ykt_goods_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `gid` int(10) unsigned NOT NULL COMMENT '商品id',
  `comment` char(200) NOT NULL DEFAULT '' COMMENT '内容',
  `star` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '星级',
  `add_time` int(10) unsigned NOT NULL COMMENT '时间',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示',
  `respond` char(200) DEFAULT '' COMMENT '回应',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_goods_private` */

DROP TABLE IF EXISTS `ykt_goods_private`;

CREATE TABLE `ykt_goods_private` (
  `gpid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `attr` char(20) NOT NULL DEFAULT '' COMMENT '私有属性值',
  `attr_match` char(50) NOT NULL DEFAULT '' COMMENT '私有属性配搭（中文）',
  `stock` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '价格',
  `goods_code` char(50) NOT NULL DEFAULT '' COMMENT '商品编码',
  `area_code` char(50) DEFAULT '' COMMENT '区域码',
  `abbr` char(255) NOT NULL DEFAULT '' COMMENT '商品缩写',
  `weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '商品重量',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '默认',
  `img` char(100) NOT NULL DEFAULT '' COMMENT '私有属性图片',
  `sales` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `stock_rate` decimal(10,1) unsigned NOT NULL DEFAULT '1.0' COMMENT '库存比率',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否生效，对应商品状态',
  PRIMARY KEY (`gpid`),
  UNIQUE KEY `gid_attr` (`gid`,`attr`)
) ENGINE=InnoDB AUTO_INCREMENT=1634 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_goods_tag` */

DROP TABLE IF EXISTS `ykt_goods_tag`;

CREATE TABLE `ykt_goods_tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标签id',
  `tag_name` char(20) NOT NULL DEFAULT '' COMMENT '标签名',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '标签状态 1停用 2启用',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_name` (`tag_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_goods_tag_map` */

DROP TABLE IF EXISTS `ykt_goods_tag_map`;

CREATE TABLE `ykt_goods_tag_map` (
  `gid` int(10) unsigned NOT NULL COMMENT '商品id',
  `tag_id` int(10) unsigned NOT NULL COMMENT '标签id',
  PRIMARY KEY (`gid`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_order` */

DROP TABLE IF EXISTS `ykt_order`;

CREATE TABLE `ykt_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` char(50) NOT NULL DEFAULT '' COMMENT '订单号码',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `address_id` int(11) unsigned NOT NULL COMMENT '使用的地址ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '订单状态',
  `express_com` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '快递公司',
  `express_num` char(80) NOT NULL DEFAULT '' COMMENT '快递单号',
  `express_msg` text NOT NULL COMMENT '快递信息',
  `express_fee` decimal(10,1) unsigned NOT NULL COMMENT '运费',
  `express_fee_coupon` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '运费优惠',
  `goods_price` decimal(10,1) unsigned NOT NULL COMMENT '订单商品总额',
  `total_weight` decimal(10,1) unsigned NOT NULL COMMENT '订单总重',
  `order_price` decimal(10,1) unsigned NOT NULL COMMENT '订单总额',
  `order_price_pay` decimal(10,1) unsigned NOT NULL COMMENT '最终订单支付金额',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `pay_time` int(10) unsigned NOT NULL COMMENT '付款时间',
  `assort_time` int(10) unsigned NOT NULL COMMENT '配货时间',
  `send_time` int(10) unsigned NOT NULL COMMENT '发货时间',
  `send_time_real` int(10) unsigned NOT NULL COMMENT '真实发货时间',
  `take_time` int(10) unsigned NOT NULL COMMENT '收货时间',
  `finish_time` int(10) unsigned NOT NULL COMMENT '完成时间',
  `cancel_time` int(10) unsigned NOT NULL COMMENT '取消时间',
  `pay_way` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '支付方式 1支付宝',
  `alipay_serial` char(255) NOT NULL COMMENT '支付宝支付流水',
  `wechat_serial` char(255) NOT NULL COMMENT '微信支付流水',
  `remark` char(255) NOT NULL COMMENT '卖家备注',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父订单号 0为主订单',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_sn_status` (`order_sn`,`status`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_order_goods` */

DROP TABLE IF EXISTS `ykt_order_goods`;

CREATE TABLE `ykt_order_goods` (
  `order_id` int(11) unsigned NOT NULL COMMENT '订单id',
  `gpid` int(11) unsigned NOT NULL COMMENT '商品私有属性',
  `gid` int(11) unsigned NOT NULL COMMENT '商品id',
  `num` int(11) unsigned NOT NULL COMMENT '购买数量',
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `comment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否评价了',
  PRIMARY KEY (`order_id`,`gpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_order_goods_refund` */

DROP TABLE IF EXISTS `ykt_order_goods_refund`;

CREATE TABLE `ykt_order_goods_refund` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `gpid` int(11) unsigned NOT NULL,
  `gid` char(20) NOT NULL,
  `order_sn` char(50) NOT NULL,
  `img` char(100) NOT NULL,
  `description` char(255) NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `order_id` int(11) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_g_a` (`uid`,`gpid`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_page` */

DROP TABLE IF EXISTS `ykt_page`;

CREATE TABLE `ykt_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL COMMENT '名称',
  `content` text NOT NULL COMMENT '内容',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `sign` char(50) NOT NULL COMMENT '标记',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_private_msg` */

DROP TABLE IF EXISTS `ykt_private_msg`;

CREATE TABLE `ykt_private_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `rec_uid` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `summary` varchar(255) NOT NULL COMMENT '摘要',
  `link` varchar(255) NOT NULL COMMENT '链接',
  `mapping_table` char(50) NOT NULL COMMENT '相关表',
  `mapping_id` int(11) unsigned NOT NULL COMMENT '相关表id',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `add_time` int(11) unsigned NOT NULL COMMENT '新增时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_promote` */

DROP TABLE IF EXISTS `ykt_promote`;

CREATE TABLE `ykt_promote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL COMMENT '块id',
  `title` char(80) NOT NULL COMMENT '标题',
  `img` char(100) NOT NULL COMMENT '图片',
  `link` char(100) NOT NULL COMMENT '链接',
  `show` tinyint(1) unsigned NOT NULL COMMENT '显示',
  `sort` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `sorttime` int(10) unsigned NOT NULL COMMENT '排序时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_promote_block` */

DROP TABLE IF EXISTS `ykt_promote_block`;

CREATE TABLE `ykt_promote_block` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL COMMENT '父亲id',
  `sign` char(50) NOT NULL COMMENT '块标记',
  `desc` char(255) NOT NULL COMMENT '块位描述',
  `level` tinyint(1) NOT NULL COMMENT '层级',
  PRIMARY KEY (`id`),
  KEY `sign` (`sign`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_public_attr` */

DROP TABLE IF EXISTS `ykt_public_attr`;

CREATE TABLE `ykt_public_attr` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_public_msg` */

DROP TABLE IF EXISTS `ykt_public_msg`;

CREATE TABLE `ykt_public_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `hot` tinyint(1) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_qa` */

DROP TABLE IF EXISTS `ykt_qa`;

CREATE TABLE `ykt_qa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `question` char(200) NOT NULL DEFAULT '' COMMENT '问题',
  `answer` char(200) NOT NULL DEFAULT '' COMMENT '回答',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `attr` set('a','b','c','d') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ykt_user` */

DROP TABLE IF EXISTS `ykt_user`;

CREATE TABLE `ykt_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `salt` varchar(32) NOT NULL COMMENT '密码盐值',
  `hash` varchar(32) DEFAULT NULL COMMENT '密码hash值',
  `realname` char(50) NOT NULL COMMENT '真名',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `mobile` char(20) NOT NULL DEFAULT '0' COMMENT '手机',
  `qq` char(20) NOT NULL COMMENT 'qq',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '注册ip',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `avatar` char(200) NOT NULL DEFAULT '' COMMENT '头像',
  `consigner` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否寄售用户',
  `sign` char(255) NOT NULL COMMENT '签名',
  `birth` char(20) NOT NULL COMMENT '出生日期',
  `consign_num` smallint(8) unsigned NOT NULL COMMENT '寄售数',
  `article_num` smallint(8) unsigned NOT NULL COMMENT '投稿数',
  `fans_num` smallint(8) unsigned NOT NULL COMMENT '粉丝数',
  `alipay_account` char(50) NOT NULL COMMENT '支付宝账户',
  `alipay_user` char(50) NOT NULL COMMENT '支付宝用户',
  `point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `remark` char(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
