DROP DATABASE IF EXISTS bonder;

CREATE DATABASE bonder;

USE bonder;

CREATE TABLE allowedmacs (
	macid              INTEGER PRIMARY KEY AUTO_INCREMENT,
	mac                CHAR(12) NOT NULL,
	configid           INTEGER NOT NULL
);

CREATE TABLE configs (
	configid                       INTEGER PRIMARY KEY AUTO_INCREMENT,
	networkgroupid                 INTEGER NOT NULL,
	userid                         INTEGER NOT NULL,
	networkid                      INTEGER NOT NULL,
	allowed_macs                   BOOLEAN NOT NULL DEFAULT FALSE 
);

CREATE UNIQUE INDEX configs__idx ON
	configs ( userid ASC );

CREATE TABLE groups (
	groupid          INTEGER PRIMARY KEY AUTO_INCREMENT,
	title            VARCHAR(255) NOT NULL,
	data             TEXT NOT NULL
);

CREATE TABLE logs (
	logid            INTEGER PRIMARY KEY AUTO_INCREMENT,
	type             SMALLINT NOT NULL,
	description      VARCHAR(255) NOT NULL,
	userid           INTEGER NOT NULL
);

CREATE TABLE networkgroups (
	networkgroupid   INTEGER PRIMARY KEY AUTO_INCREMENT,
	name             VARCHAR(255) NOT NULL,
	userid           INTEGER NOT NULL
);


CREATE TABLE networks (
	networkid                      INTEGER PRIMARY KEY AUTO_INCREMENT,
	ssid                           VARCHAR(255) NOT NULL,
	password                       VARCHAR(255) NOT NULL,
	type                           CHAR(1) NOT NULL,
	networkgroupid                 INTEGER
);

CREATE TABLE pastes (
	pasteid          INTEGER PRIMARY KEY AUTO_INCREMENT,
	data             TEXT NOT NULL,
	title            VARCHAR(255),
	creationtime     TIMESTAMP NOT NULL,
	flag             CHAR(1) NOT NULL,
	userid           INTEGER NOT NULL
);

CREATE TABLE performances (
	performanceid        INTEGER PRIMARY KEY AUTO_INCREMENT,
	uploadspeed          DOUBLE NOT NULL,
	testdate             DATE NOT NULL,
	downloadspeed        DOUBLE NOT NULL,
	networkid            INTEGER NOT NULL
);

CREATE TABLE sessions (
	sesisonid        INTEGER PRIMARY KEY AUTO_INCREMENT,
	hash             VARCHAR(255) NOT NULL,
	userid           INTEGER NOT NULL
);

CREATE TABLE users (
	userid         INTEGER PRIMARY KEY AUTO_INCREMENT,
	username       VARCHAR(100) UNIQUE NOT NULL,
	passwordhash   VARCHAR(255) NOT NULL,
	passwordsalt   VARCHAR(32) NOT NULL,
	groupid        INTEGER NOT NULL,
	joined         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE allowedmacs
	ADD CONSTRAINT allowedmacs_configs_fk FOREIGN KEY ( configid )
		REFERENCES configs ( configid );

ALTER TABLE configs
	ADD CONSTRAINT configs_networkgroups_fk FOREIGN KEY ( networkgroupid )
		REFERENCES networkgroups ( networkgroupid );

ALTER TABLE configs
	ADD CONSTRAINT configs_networks_fk FOREIGN KEY ( networkid )
		REFERENCES networks ( networkid );

ALTER TABLE configs
	ADD CONSTRAINT configs_users_fk FOREIGN KEY ( userid )
		REFERENCES users ( userid );

ALTER TABLE users
	ADD CONSTRAINT users_groups_fk FOREIGN KEY ( groupid )
		REFERENCES groups ( groupid );

ALTER TABLE logs
	ADD CONSTRAINT logs_users_fk FOREIGN KEY ( userid )
		REFERENCES users ( userid );

ALTER TABLE networkgroups
	ADD CONSTRAINT networkgroups_users_fk FOREIGN KEY ( userid )
		REFERENCES users ( userid );

ALTER TABLE networks
	ADD CONSTRAINT networks_networkgroups_fk FOREIGN KEY ( networkgroupid )
		REFERENCES networkgroups ( networkgroupid );

ALTER TABLE pastes
	ADD CONSTRAINT pastes_users_fk FOREIGN KEY ( userid )
		REFERENCES users ( userid );

ALTER TABLE performances
	ADD CONSTRAINT performances_networks_fk FOREIGN KEY ( networkid )
		REFERENCES networks ( networkid );

ALTER TABLE sessions
	ADD CONSTRAINT sessions_users_fk FOREIGN KEY ( userid )
		REFERENCES users ( userid );

DELIMITER //
CREATE PROCEDURE updatepastes()
	BEGIN
		DELETE FROM pastes WHERE ((TIMESTAMPADD(MONTH, 1, creationtime) <= (SELECT CURRENT_TIMESTAMP)) AND (flag != 'E')) 
		                      OR ((TIMESTAMPADD(MONTH, 2, creationtime) <= (SELECT CURRENT_TIMESTAMP)) AND (flag = 'E'));
	END//

CREATE FUNCTION getdaystodelete(paste INTEGER)
	RETURNS INTEGER READS SQL DATA
	BEGIN
		RETURN DATEDIFF((SELECT CURRENT_TIMESTAMP), (SELECT creationtime from pastes));
	END//
DELIMITER ;

CREATE EVENT remove_pastes ON SCHEDULE EVERY 60 SECOND DO CALL updatepastes();

