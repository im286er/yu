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

/*Data for the table `ykt_public_attr` */

LOCK TABLES `ykt_public_attr` WRITE;

insert  into `ykt_public_attr`(`id`,`pid`,`cat_id`,`title`,`level`,`sort`) values (4,0,4,'属性',1,4),(5,4,4,'原创题材',2,5),(6,4,4,'二次创作',2,6),(7,0,4,'年龄',1,7),(8,7,4,'全年龄',2,8),(9,7,4,'R15',2,9),(10,7,4,'R18',2,10),(11,0,4,'性质',1,11),(12,11,4,'大众向',2,12),(13,11,4,'女性向',2,13),(14,11,4,'男性向',2,14),(15,11,4,'混合',2,15),(73,0,4,'语言',1,73),(74,73,4,'简体中文',2,74),(75,73,4,'繁体中文',2,75),(76,73,4,'日文',2,76),(77,73,4,'英语',2,77),(78,73,4,'中日双语',2,78),(79,73,4,'中英双语',2,79),(80,73,4,'其他',2,80),(81,0,4,'类别',1,81),(82,81,4,'黑白漫画',2,82),(83,81,4,'彩色漫画',2,83),(84,81,4,'黑白小说',2,84),(85,81,4,'图文绘本',2,85),(86,81,4,'摄影集',2,86),(87,81,4,'其他',2,87),(97,0,5,'年龄',1,4),(98,97,5,'全年龄',2,5),(99,97,5,'R15',2,6),(100,97,5,'R18',2,7),(103,0,5,'性质',1,8),(104,103,5,'大众向',2,9),(105,103,5,'女性向',2,10),(106,103,5,'男性向',2,11),(107,103,5,'混合',2,12),(127,0,5,'语言',1,31),(128,127,5,'简体中文',2,32),(129,127,5,'繁体中文',2,33),(130,127,5,'日文',2,34),(131,127,5,'英语',2,35),(132,127,5,'中日双语',2,36),(133,127,5,'中英双语',2,37),(134,127,5,'其他',2,38),(141,0,7,'属性',1,4),(142,141,7,'原创题材',2,5),(143,141,7,'二次创作',2,6),(144,0,7,'年龄',1,7),(145,144,7,'全年龄',2,8),(146,144,7,'R15',2,9),(147,144,7,'R18',2,10),(148,0,7,'性质',1,11),(149,148,7,'大众向',2,12),(150,148,7,'女性向',2,13),(151,148,7,'男性向',2,14),(152,148,7,'混合',2,15),(153,0,7,'形式',1,16),(154,153,7,'钥匙扣',2,17),(155,153,7,'挂件类',2,18),(156,153,7,'贴纸类',2,19),(157,153,7,'明信片/信封',2,20),(158,153,7,'印章',2,21),(159,153,7,'周边综合套装',2,22),(160,153,7,'便签本',2,23),(161,153,7,'台历',2,24),(162,153,7,'笔记本/手帐 ',2,25),(163,153,7,'分装卡',2,26),(164,153,7,' 胸章/扣针',2,27),(165,153,7,'创可贴 ',2,28),(166,153,7,'文件夹/文件袋',2,29),(167,153,7,'卡套/卡贴',2,30),(168,153,7,'书签 ',2,31),(169,153,7,'随身镜',2,32),(170,153,7,'团扇/折扇',2,33),(171,153,7,'鼠标垫',2,34),(172,153,7,'眼镜布',2,35),(173,153,7,'纸模型',2,36),(174,153,7,'手办',2,37),(175,153,7,'发夹/头饰',2,38),(176,153,7,'冰箱贴',2,39),(177,153,7,'杯垫',2,40),(178,153,7,'海报/挂画',2,41),(179,153,7,'纪念币',2,42),(189,0,6,'属性',1,4),(190,189,6,'原创题材',2,5),(191,189,6,'二次创作',2,6),(192,0,6,'年龄',1,7),(193,192,6,'全年龄',2,8),(194,192,6,'R15',2,9),(195,192,6,'R18',2,10),(196,0,6,'性质',1,11),(197,196,6,'大众向',2,12),(198,196,6,'女性向',2,13),(199,196,6,'男性向',2,14),(200,196,6,'混合',2,15),(201,0,6,'纸质',1,16),(202,201,6,'和纸',2,17),(203,201,6,'PVC',2,18);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
