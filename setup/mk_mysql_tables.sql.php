<?
/* This files contains MySQL commands to setup the tables */

/*
-- to login: 
mysql -u SQL_USER -p -h SQL_SERVER

-- to show databases...
show databases;

-- to make kenyersel database. 
CREATE DATABASE kenyersel;

-- create and grant user (username: kys) with privalges to mysql... 
CREATE USER kys;
SET PASSWORD FOR 'kys'@'localhost' = PASSWORD('**YOUR_PASSWORD**')
GRANT ALL ON kenyersel.* TO 'kys'@'localhost';

-- enter kenyersel database and show tables
use kenyersel;
show tables;

-- command to run to setup tables using this file. 
mysql -u kys -h localhost --password=**YOUR_PASSWORD** < setup.sql

show databases;
use kenyersel;
show tables;
*/
if(! isset($db_tables_prefix) or $db_tables_prefix == ""){
  die("'ERROR: db_tables_prefix must not be empty'");
} 

$q = array();

$q[$db_points] = "
CREATE TABLE " . $db_points . "
( id int not null auto_increment,
  history_id int,
  prev_id int,
  action_id int,
  point_type VARCHAR(255),
  title VARCHAR(255),
  body TEXT,
  time_stamp TIMESTAMP,
  key(id)
  )";

$q[$db_actions] = "
CREATE TABLE " . $db_actions . "
( id int not null auto_increment,
  obj_id int,
  history_id int,
  action_type VARCHAR(255),
  action_body TEXT,
  user_id int,
  ipaddr VARCHAR(64),
  time_stamp TIMESTAMP,
  key(id)
  )";

$q[$db_points_h] = "
CREATE TABLE " . $db_points_h . "
( id int not null auto_increment,
  history_id int,
  prev_id int,
  action_id int,
  point_type VARCHAR(255),
  title VARCHAR(255),
  body TEXT,
  time_stamp TIMESTAMP,
  key(id)
  );";

$q[$db_relations] = "
CREATE TABLE " . $db_relations . "
( id int not null auto_increment,
  history_id int,
  prev_id int,
  action_id int,
  src_obj_id int,
  dst_obj_id int,
  relation_type VARCHAR(255),
  time_stamp TIMESTAMP,
  key(id)
  )";


$q[$db_relations_h] = "
CREATE TABLE " . $db_relations_h . "
( id int not null auto_increment,
  history_id int,
  prev_id int,
  action_id int,
  src_obj_id int,
  dst_obj_id int,
  relation_type VARCHAR(255),
  time_stamp TIMESTAMP,
  key(id)
  )";

$q[$db_unique_keys] = "
CREATE TABLE " . $db_unique_keys . "
( id int not null auto_increment,
  key(id)
  )";

$q[$db_users] = "
CREATE TABLE " . $db_users . "
( id int not null auto_increment,
  lastname VARCHAR(255),
  firstname VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(80),
  userkind VARCHAR(255),
  last_act_time TIMESTAMP,
  last_act_kind VARCHAR(255),
  last_act_code VARCHAR(255),
  key(id)
  )";

// # Table structure for table `paypal_payment_info` #
$q[$db_paypal_payment_info] = "
CREATE TABLE " . $db_paypal_payment_info . " 
("
//  Transaction and Notification variables
."`business` varchar(127) NOT NULL default '',
  `charset` varchar(255) NOT NULL default '',
  `custom` varchar(255) NOT NULL default '',
  `notify_version` varchar(255) NOT NULL default '',
  `parent_txn_id` varchar(19) NOT NULL default '',
  `receipt_id` varchar(255) NOT NULL default '',
  `receiver_email` varchar(127) NOT NULL default '',
  `receiver_id` varchar(13) NOT NULL default '',
  `resend` varchar(10) NOT NULL default '',
  `residence_country` char(2) NOT NULL default '',
  `test_ipn` char(2) NOT NULL default '',
  `txn_id` varchar(255) NOT NULL default '',
  `txn_type` varchar(255) NOT NULL default '', 
  `verify_sign` varchar(255) NOT NULL default '',"
// Buyer Information 
."`address_country` varchar(64) NOT NULL default '',
  `address_city` varchar(40) NOT NULL default '',
  `address_country_code` varchar(64) NOT NULL default '',
  `address_name` varchar(128) NOT NULL default '',
  `address_state` char(40) NOT NULL default '',
  `address_street` varchar(200) NOT NULL default '',
  `address_zip` varchar(20) NOT NULL default '',
  `contact_phone` varchar(20) NOT NULL default '',
  `first_name` varchar(64) NOT NULL default '',
  `last_name` varchar(64) NOT NULL default '',
  `payer_business_name` varchar(127) NOT NULL default '',
  `payer_email` varchar(127) NOT NULL default '',
  `payer_id` varchar(13) NOT NULL default '',"
// Payment Information 
."`auth_amount` varchar(255) default NULL,
  `auth_exp` varchar(28) default NULL,
  `auth_id` varchar(19) default NULL,
  `auth_status` varchar(255) default NULL,
  `btn_id` varchar(255) default NULL,
  `exchange_rate` varchar(255) default NULL,
  `fraud_management_pending_filters` varchar(255) default NULL,
  `invoice` varchar(127) default NULL,
  `item_name` varchar(127) default NULL,
  `item_number` varchar(127) default NULL,
  `mc_currency` varchar(255) NOT NULL default '',
  `mc_fee` varchar(255) NOT NULL default '',
  `mc_gross` varchar(255) NOT NULL default '',
  `mc_handling` varchar(255) NOT NULL default '',
  `mc_shipping` varchar(255) NOT NULL default '',
  `memo` varchar(255) default NULL,
  `option_name1` varchar(64) default NULL,
  `option_selection1` varchar(200) default NULL,
  `option_name2` varchar(64) default NULL,
  `option_selection2` varchar(200) default NULL,
  `payer_status` varchar(255) NOT NULL default '',
  `payment_date` varchar(28) NOT NULL default '',
  `payment_status` varchar(255) NOT NULL default '',
  `payment_type` varchar(255) NOT NULL default '',
  `pending_reason` varchar(255) NOT NULL default '',
  `quantity` varchar(10) default NULL,
  `reason_code` varchar(255) NOT NULL default '',
  `remaining_settle` varchar(255) NOT NULL default '',
  `settle_amount` varchar(255) NOT NULL default '',
  `settle_currency` varchar(255) NOT NULL default '',
  `shipping` varchar(255) NOT NULL default '',
  `shipping_method` varchar(255) NOT NULL default '',
  `tax` varchar(255) NOT NULL default '',
  `transaction_entity` varchar(255) NOT NULL default '',"
// TheoreyMine variables
."`time_stamp` TIMESTAMP default CURRENT_TIMESTAMP,
  `point_id` int NOT NULL default '-1'
)";

?>
