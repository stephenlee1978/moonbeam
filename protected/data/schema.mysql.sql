CREATE TABLE dbshop.tbl_user(
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(128) NOT NULL DEFAULT '',
  `password` VARCHAR(128) NOT NULL DEFAULT '',
  nickname VARCHAR(128) DEFAULT NULL,
  email VARCHAR(128) NOT NULL DEFAULT '',
  logintime TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  admin BIT(1) DEFAULT b'0',
  active BIT(1) DEFAULT b'0',
  outtime DATETIME DEFAULT NULL,
  PRIMARY KEY (id)
)

INSERT INTO tbl_user (username, password, nickname, email, logintime, admin, active) 
VALUES ('stephen', '21232F297A57A5A743894A0E4A801FC3', '李雁', '4447709@qq.com', NULL, 1, 1);

CREATE TABLE dbshop.tbl_product_type(
  id INT(11) NOT NULL AUTO_INCREMENT,
  parent_id INT(11) DEFAULT 0,
  name VARCHAR(128) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
)

CREATE TABLE dbshop.tbl_product(
  id INT(11) NOT NULL AUTO_INCREMENT,
  brandName VARCHAR(255) DEFAULT '',
  productTitle VARCHAR(255) NOT NULL,
  price DECIMAL[20,2] NOT NULL,
  originalPrice DECIMAL[20,2] NOT NULL,
  percentOff INT(20) DEFAULT 1,
  swatches VARCHAR(255) NOT NULL,
  sizes VARCHAR(255) NOT NULL,
  details VARCHAR(255) DEFAULT '',
  designer VARCHAR(255) DEFAULT '',
  PRIMARY KEY (id)
)

/*任务表*/
CREATE TABLE dbshop.tbl_task(
  id INT(11) NOT NULL AUTO_INCREMENT,
  product_tpye_id INT(11) NOT NULL,
  url VARCHAR(255) NOT NULL DEFAULT '',
  finish BIT(1) DEFAULT b'0',
  PRIMARY KEY (id)
)

INSERT INTO tbl_product_type (name) VALUES ('服饰'); /*id:1*/
INSERT INTO tbl_product_type (name) VALUES ('鞋履'); /*id:2*/
INSERT INTO tbl_product_type (name) VALUES ('手袋'); /*id:3*/
INSERT INTO tbl_product_type (name) VALUES ('配饰'); /*id:4*/

INSERT INTO tbl_product_type (name, parent_id) VALUES ('牛仔系列', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('连衣裙', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('夹克/外套', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('连身衣', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('皮革制品', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('打底裤', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('贴身内衣', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('居家便服/瑜伽服', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('孕妇装', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('长裤', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('短裤', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('裙装', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('套装单品', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('毛衣/针织衫', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('泳装', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('上衣', 1);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('背心', 1);

INSERT INTO tbl_product_type (name, parent_id) VALUES ('短靴', 2);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('中长靴', 2);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('名师精品', 2);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('平底鞋', 2);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('浅口鞋', 2);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('凉鞋', 2);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('运动鞋', 2);

INSERT INTO tbl_product_type (name, parent_id) VALUES ('妈妈包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('背包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('沙滩包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('黑色手提包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('晚宴包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('化妆小包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('斜跨包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('名师精品', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('圆底包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('小提包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('肩背包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('购物包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('钱包', 3);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('周末休闲包', 3);

INSERT INTO tbl_product_type (name, parent_id) VALUES ('珠宝', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('腰带', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('时尚书刊', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('名师精品', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('护目镜', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('手套', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('发饰', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('帽子', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('家饰/礼品', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('产品护理', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('围巾/披肩', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('电子产品配饰', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('伞具', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('手表', 4);
INSERT INTO tbl_product_type (name, parent_id) VALUES ('冬季配饰', 4);

