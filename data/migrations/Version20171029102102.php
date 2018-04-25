<?php

namespace DoctrineORMModule\Migrations;

use Application\Util\PlatformTrait;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171029102102 extends AbstractMigration
{
    use PlatformTrait;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->guardForPlatform();

        $this->addSql(
            'CREATE TABLE oauth_clients (
client_id VARCHAR(80) NOT NULL,
client_secret VARCHAR(80) NOT NULL,
redirect_uri VARCHAR(2000) NOT NULL,
grant_types VARCHAR(80),
scope VARCHAR(2000),
user_id VARCHAR(255), 
CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id)) 
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE oauth_access_tokens (
access_token VARCHAR(40) NOT NULL,
client_id VARCHAR(80) NOT NULL,
user_id VARCHAR(255),
expires TIMESTAMP NOT NULL,
scope VARCHAR(2000),
CONSTRAINT access_token_pk PRIMARY KEY (access_token))
 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE oauth_authorization_codes (
authorization_code VARCHAR(40) NOT NULL,
client_id VARCHAR(80) NOT NULL,
user_id VARCHAR(255),
redirect_uri VARCHAR(2000),
expires TIMESTAMP NOT NULL,
scope VARCHAR(2000),
id_token VARCHAR(2000),
CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code)) 
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE oauth_refresh_tokens (
refresh_token VARCHAR(40) NOT NULL,
client_id VARCHAR(80) NOT NULL,
user_id VARCHAR(255),
expires TIMESTAMP NOT NULL,
scope VARCHAR(2000),
CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token)) 
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'create table users(
id int auto_increment primary key, 
username varchar(255) not null, 
password varchar(2000) null,
role ENUM(\'admin\', \'client\') NOT NULL DEFAULT \'client\',
status ENUM(\'active\', \'remove\', \'suspend\') NOT NULL DEFAULT \'suspend\',
createdAt DATETIME NOT NULL ,
constraint oauth_users_username_uindex unique (username)) 
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE oauth_scopes (
type VARCHAR(255) NOT NULL DEFAULT "supported",
scope VARCHAR(2000),
client_id VARCHAR (80),is_default SMALLINT DEFAULT NULL) 
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE oauth_jwt (
client_id VARCHAR(80) NOT NULL,
subject VARCHAR(80),
public_key VARCHAR(2000),
CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id)) 
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE user_code (
id int auto_increment primary key,
user int NOT NULL,
type ENUM(\'email\') NOT NULL,
code VARCHAR(100),
createdAt DATETIME NOT NULL,
INDEX user_code_user_index (user),
CONSTRAINT user_code_users_fk FOREIGN KEY (user) REFERENCES users (id))
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
    }
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
