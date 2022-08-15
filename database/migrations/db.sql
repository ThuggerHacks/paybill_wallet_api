
CREATE DATABASE
IF NOT EXISTS paybill_wallet_db;

use paybill_wallet_db;

CREATE TABLE
IF NOT EXISTS tbl_user
(
	user_name VARCHAR
(100)  NOT NULL,
    user_email  VARCHAR
(70) UNIQUE NOT NULL,
    user_password VARCHAR
(200) NOT NULL,
    user_phone_number VARCHAR
(15) UNIQUE NOT NULL,
    user_birthdate DATE NOT NULL,
    user_birthplace VARCHAR
(50) NOT NULL,
    user_profile VARCHAR
(20),
    user_id VARCHAR
(30) NOT NULL PRIMARY KEY,
    is_admin BOOLEAN DEFAULT 0,
    user_created_at timestamp,
    user_email_verified BOOLEAN DEFAULT 0,
    token VARCHAR
(300)  NOT NULL,
pro_account INT DEFAULT 0,
email_sent_code VARCHAR
(6) DEFAULT NULL
    
);

CREATE TABLE
IF NOT EXISTS tbl_wallet
(
	user_id  VARCHAR
(30) NOT NULL,
    wallet_title VARCHAR
(20) NOT NULL,
    wallet_money DECIMAL DEFAULT 0,
    wallet_activated_status BOOLEAN DEFAULT 1,
    wallet_associated_phone_number VARCHAR
(12) NOT NULL,
    wallet_created_at timestamp,
   	wallet_id VARCHAR
(30) NOT NULL PRIMARY KEY,
    FOREIGN KEY
(user_id) REFERENCES tbl_user
(user_id) ON
DELETE CASCADE

);


CREATE TABLE
IF NOT EXISTS tbl_deposit
(
	deposit_amount DECIMAL  NOT NULL  ,
    deposit_from VARCHAR
(30) NOT NULL,
    deposit_to_wallet_id VARCHAR
(30) NOT NULL,
    deposited_at timestamp,
    deposit_reference VARCHAR
(30) NOT NULL PRIMARY KEY,
    FOREIGN KEY
(deposit_to_wallet_id) REFERENCES tbl_wallet
(wallet_id) ON
DELETE CASCADE

);

CREATE TABLE
IF NOT EXISTS tbl_sent
(
	sent_amount DECIMAL   NOT NULL,
    sent_from_wallet_id VARCHAR
(30) NOT NULL,
    sent_to_wallet_id VARCHAR
(30) NOT NULL,
    sent_at timestamp,
    sent_reference VARCHAR
(30) NOT NULL PRIMARY KEY,
    FOREIGN KEY
(sent_from_wallet_id) REFERENCES tbl_wallet
(wallet_id) ON
DELETE CASCADE,
    FOREIGN KEY (sent_to_wallet_id)
REFERENCES tbl_wallet
(wallet_id) ON
DELETE CASCADE
);

CREATE TABLE
IF NOT EXISTS tbl_withdraw
(
	withdraw_amount DECIMAL   NOT NULL,
    withdraw_wallet_id VARCHAR
(30) NOT NULL,
    withdraw_at timestamp,
    withdraw_reference VARCHAR
(30) NOT NULL PRIMARY KEY,
    FOREIGN KEY
(withdraw_wallet_id) REFERENCES tbl_wallet
(wallet_id) ON
DELETE CASCADE
);

CREATE TABLE
IF NOT EXISTS tbl_saving
(
	wallet_id VARCHAR
(30) NOT NULL,
    amount_to_reach DECIMAL NOT NULL,
    wallet_status BOOLEAN DEFAULT 1,
    saving_id INT NOT NULL primary key AUTO_INCREMENT,
    FOREIGN key
(wallet_id) REFERENCES tbl_wallet
(wallet_id) ON
DELETE CASCADE
);