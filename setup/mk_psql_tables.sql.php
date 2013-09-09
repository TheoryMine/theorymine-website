<?
/* This files contains PostgreSQL commands to setup the tables */

/*
-- to login: 
psql DB_NAME USER_NAME

-- create a user
sudo -u postgres createuser -P -S -D -A -R USERNAME
(-P means it will ask you for password for the sql database
-S means is not a superuser
-D cannot create new DB
-A cannot crate new users
-R cannot create new roles)

-- to make kenyersel database, owned by USERNAME
sudo -u postgres createdb -O USERNAME kenyersel;

-- enter kenyersel database and show tables
psql kenyersel USERNAME

NOTE: USERNAME must be same as your unix username. 

-- command to run to setup tables using this file. 
psql kenyersel USERNAME < setup.sql

*/
if(! isset($db_tables_prefix) or $db_tables_prefix == ""){
  die("'ERROR: db_tables_prefix must not be empty'");
}

$q = array();

$q[$db_points] = "
CREATE TABLE " . $db_points . "
( id SERIAL,
	history_id integer,
  prev_id int,
	action_id integer,
	point_type VARCHAR(255),
	title VARCHAR(255),
	body TEXT,
  time_stamp TIMESTAMP,
	PRIMARY KEY (id)
  );";


$q[$db_actions] = "
CREATE TABLE " . $db_actions . "
( id SERIAL,
  obj_id integer,
  history_id integer,
  action_type VARCHAR(255),
  action_body TEXT,
  user_id integer,
  ipaddr VARCHAR(64),
  time_stamp TIMESTAMP,
  PRIMARY KEY (id)
  );";

$q[$db_points_h] = "
CREATE TABLE " . $db_points_h . "
( id SERIAL,
  prev_id int,
  history_id integer,
  action_id integer,
  point_type VARCHAR(255),
  title VARCHAR(255),
  body TEXT,
  time_stamp TIMESTAMP,
  PRIMARY KEY (id)
  );";

$q[$db_relations] = "
CREATE TABLE " .  $db_relations . "
( id SERIAL,
  prev_id integer,
  history_id integer,
  action_id integer,
  src_obj_id integer,
  dst_obj_id integer,
  relation_type VARCHAR(255),
  time_stamp TIMESTAMP,
  PRIMARY KEY (id)
  );";

$q[$db_relations_h] = "
CREATE TABLE " . $db_relations_h . "
( id SERIAL,
  prev_id integer,
  history_id integer,
  action_id integer,
  src_obj_id integer,
  dst_obj_id integer,
  relation_type VARCHAR(255),
  time_stamp TIMESTAMP,
  PRIMARY KEY (id)
  );";

$q[$db_unique_keys] = "
CREATE TABLE " . $db_unique_keys . "
( id SERIAL,
  PRIMARY KEY (id)
  );";


$q[$db_users] = "
CREATE TABLE " . $db_users . "
( id SERIAL,
	lastname VARCHAR(255), 
	firstname VARCHAR(255),
	email VARCHAR(255) UNIQUE,
	password VARCHAR(80),
	userkind VARCHAR(255),
	last_act_time TIMESTAMP,
	last_act_kind VARCHAR(255),
  last_act_code VARCHAR(255),
	PRIMARY KEY(id)
  );";

/* Table structure for table paypal_payment_info */
$q[$db_paypal_payment_info] = "
CREATE TABLE " . $db_paypal_payment_info . "
( *** NEEDS TO BE FIXED, see MYSQL version *** );";

?>
