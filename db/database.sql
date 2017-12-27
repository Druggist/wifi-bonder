DROP DATABASE IF EXISTS bonder;

CREATE DATABASE bonder;

USE bonder;

CREATE TABLE bannedmacs (
	macid              INTEGER NOT NULL,
	mac                CHAR(12) NOT NULL,
	configs_configid   INTEGER NOT NULL
);

ALTER TABLE bannedmacs ADD CONSTRAINT bannedmacs_pk PRIMARY KEY ( macid );

CREATE TABLE configs (
	configid                       INTEGER NOT NULL,
	networkgroups_networkgroupid   INTEGER NOT NULL,
	users_username                 VARCHAR(100) NOT NULL,
	networks_networkid             INTEGER NOT NULL
);

CREATE UNIQUE INDEX configs__idx ON
	configs ( users_username ASC );

ALTER TABLE configs ADD CONSTRAINT configs_pk PRIMARY KEY ( configid );

CREATE TABLE groups (
	groupid          INTEGER NOT NULL,
	name             VARCHAR(255) NOT NULL,
	data             TEXT NOT NULL,
	users_username   VARCHAR(100) NOT NULL
);

ALTER TABLE groups ADD CONSTRAINT groups_pk PRIMARY KEY ( groupid );

CREATE TABLE logs (
	logid            INTEGER NOT NULL,
	type             SMALLINT NOT NULL,
	description      VARCHAR(255) NOT NULL,
	users_username   VARCHAR(100) NOT NULL
);

ALTER TABLE logs ADD CONSTRAINT logs_pk PRIMARY KEY ( logid );

CREATE TABLE networkgroups (
	networkgroupid   INTEGER NOT NULL,
	name             VARCHAR(255) NOT NULL,
	users_username   VARCHAR(100) NOT NULL
);

ALTER TABLE networkgroups ADD CONSTRAINT networkgroups_pk PRIMARY KEY ( networkgroupid );

CREATE TABLE networks (
	networkid                      INTEGER NOT NULL,
	ssid                           VARCHAR(255) NOT NULL,
	password                       VARCHAR(255) NOT NULL,
	type                           CHAR(1) NOT NULL,
	networkgroups_networkgroupid   INTEGER
);

ALTER TABLE networks ADD CONSTRAINT networks_pk PRIMARY KEY ( networkid );

CREATE TABLE pastes (
	pasteid          INTEGER NOT NULL,
	data             TEXT NOT NULL,
	title            VARCHAR(255),
	creationtime     TIMESTAMP NOT NULL,
	flag             CHAR(1) NOT NULL,
	users_username   VARCHAR(100) NOT NULL
);

ALTER TABLE pastes ADD CONSTRAINT pastes_pk PRIMARY KEY ( pasteid );

CREATE TABLE performances (
	performanceid        INTEGER NOT NULL,
	uploadspeed          DOUBLE NOT NULL,
	testdate               DATE NOT NULL,
	downloadspeed        DOUBLE NOT NULL,
	networks_networkid   INTEGER NOT NULL
);

ALTER TABLE performances ADD CONSTRAINT performances_pk PRIMARY KEY ( performanceid );

CREATE TABLE sessions (
	sesisonid        INTEGER NOT NULL,
	data             VARCHAR(255) NOT NULL,
	users_username   VARCHAR(100) NOT NULL
);

ALTER TABLE sessions ADD CONSTRAINT sessions_pk PRIMARY KEY ( sesisonid );

CREATE TABLE users (
	username       VARCHAR(100) NOT NULL,
	passwordhash   VARCHAR(255) NOT NULL,
	passwordsalt   VARCHAR(32) NOT NULL
);

ALTER TABLE users ADD CONSTRAINT users_pk PRIMARY KEY ( username );

ALTER TABLE bannedmacs
	ADD CONSTRAINT bannedmacs_configs_fk FOREIGN KEY ( configs_configid )
		REFERENCES configs ( configid );

ALTER TABLE configs
	ADD CONSTRAINT configs_networkgroups_fk FOREIGN KEY ( networkgroups_networkgroupid )
		REFERENCES networkgroups ( networkgroupid );

ALTER TABLE configs
	ADD CONSTRAINT configs_networks_fk FOREIGN KEY ( networks_networkid )
		REFERENCES networks ( networkid );

ALTER TABLE configs
	ADD CONSTRAINT configs_users_fk FOREIGN KEY ( users_username )
		REFERENCES users ( username );

ALTER TABLE groups
	ADD CONSTRAINT groups_users_fk FOREIGN KEY ( users_username )
		REFERENCES users ( username );

ALTER TABLE logs
	ADD CONSTRAINT logs_users_fk FOREIGN KEY ( users_username )
		REFERENCES users ( username );

ALTER TABLE networkgroups
	ADD CONSTRAINT networkgroups_users_fk FOREIGN KEY ( users_username )
		REFERENCES users ( username );

ALTER TABLE networks
	ADD CONSTRAINT networks_networkgroups_fk FOREIGN KEY ( networkgroups_networkgroupid )
		REFERENCES networkgroups ( networkgroupid );

ALTER TABLE pastes
	ADD CONSTRAINT pastes_users_fk FOREIGN KEY ( users_username )
		REFERENCES users ( username );

ALTER TABLE performances
	ADD CONSTRAINT performances_networks_fk FOREIGN KEY ( networks_networkid )
		REFERENCES networks ( networkid );

ALTER TABLE sessions
	ADD CONSTRAINT sessions_users_fk FOREIGN KEY ( users_username )
		REFERENCES users ( username );

DELIMITER //
CREATE PROCEDURE getupdatedpastes()
	BEGIN
		DELETE FROM pastes WHERE ((TIMESTAMPADD(MONTH, 1, creationtime) <= (SELECT CURRENT_TIMESTAMP)) AND (flag != 'E')) 
		                      OR ((TIMESTAMPADD(MONTH, 2, creationtime) <= (SELECT CURRENT_TIMESTAMP)) AND (flag = 'E'));
		SELECT * FROM pastes;
	END//

CREATE FUNCTION getdaystodelete(paste INTEGER)
	RETURNS INTEGER READS SQL DATA
	BEGIN
		RETURN DATEDIFF((SELECT CURRENT_TIMESTAMP), (SELECT creationtime from pastes));
	END//
DELIMITER ;

