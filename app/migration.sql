CREATE SEQUENCE fos_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE fos_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE handling_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE fos_group (id INT NOT NULL, name VARCHAR(255) NOT NULL, roles TEXT NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_4B019DDB5E237E06 ON fos_group (name);
COMMENT ON COLUMN fos_group.roles IS '(DC2Type:array)';
CREATE TABLE fos_user (id INT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, is_blocked BOOLEAN DEFAULT NULL, is_fired BOOLEAN DEFAULT NULL, birthday DATE DEFAULT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical);
CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical);
COMMENT ON COLUMN fos_user.roles IS '(DC2Type:array)';
CREATE TABLE fos_user_group (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id));
CREATE INDEX IDX_583D1F3EA76ED395 ON fos_user_group (user_id);
CREATE INDEX IDX_583D1F3EFE54D947 ON fos_user_group (group_id);
CREATE TABLE handling_service (id BIGINT NOT NULL, name VARCHAR(128) NOT NULL, slug VARCHAR(128) DEFAULT NULL, sortorder INT DEFAULT NULL, PRIMARY KEY(id));
CREATE TABLE handling_handling_service (handling_id BIGINT NOT NULL, service_id BIGINT NOT NULL, PRIMARY KEY(handling_id, service_id));
CREATE INDEX IDX_AC5EC5345AB3F1F ON handling_handling_service (handling_id);
CREATE INDEX IDX_AC5EC534ED5CA9E6 ON handling_handling_service (service_id);
CREATE TABLE team (id BIGINT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, descriprion TEXT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_C4E0A61F7E3C61F9 ON team (owner_id);
CREATE TABLE team_user (handling_id BIGINT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(handling_id, user_id));
CREATE INDEX IDX_5C7222325AB3F1F ON team_user (handling_id);
CREATE INDEX IDX_5C722232A76ED395 ON team_user (user_id);
ALTER TABLE fos_user_group ADD CONSTRAINT FK_583D1F3EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE fos_user_group ADD CONSTRAINT FK_583D1F3EFE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_handling_service ADD CONSTRAINT FK_AC5EC5345AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_handling_service ADD CONSTRAINT FK_AC5EC534ED5CA9E6 FOREIGN KEY (service_id) REFERENCES handling_service (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE team_user ADD CONSTRAINT FK_5C7222325AB3F1F FOREIGN KEY (handling_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE team_user ADD CONSTRAINT FK_5C722232A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_type ALTER id DROP DEFAULT;
DROP INDEX organization_type_type_key;
ALTER TABLE organization ADD createdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE organization ADD physical_address VARCHAR(255) DEFAULT NULL;
ALTER TABLE organization ADD phone VARCHAR(50) DEFAULT NULL;
ALTER TABLE organization ALTER id TYPE INT;
ALTER TABLE organization ALTER id DROP DEFAULT;
ALTER TABLE organization ALTER is_smeta SET ;
ALTER TABLE organization RENAME COLUMN client_type_id TO creator_id;
ALTER TABLE organization DROP CONSTRAINT organization_client_type_id_lookup_id;
DROP INDEX IDX_C1EE637C9771C8EE;
ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C61220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_C1EE637C61220EA6 ON organization (creator_id);
ALTER TABLE organization_user DROP id;
ALTER TABLE organization_user ALTER organization_id TYPE INT;
ALTER TABLE organization_user ALTER organization_id DROP DEFAULT;
ALTER TABLE organization_user ALTER user_id TYPE INT;
ALTER TABLE organization_user ALTER user_id DROP DEFAULT;
DROP INDEX organization_user_pkey;
ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_user ADD PRIMARY KEY (organization_id, user_id);
ALTER TABLE city DROP district_id;
ALTER TABLE city DROP population;
ALTER TABLE city DROP square;
ALTER TABLE city DROP density;
ALTER TABLE city DROP citytype;
ALTER TABLE city ALTER id TYPE INT;
ALTER TABLE city ALTER id DROP DEFAULT;
ALTER TABLE city ALTER region_id TYPE INT;
DROP INDEX IDX_2D5B0234B08FA272;
ALTER TABLE stuff DROP companystructure_id;
ALTER TABLE stuff ALTER id DROP DEFAULT;
ALTER TABLE stuff ALTER user_id TYPE INT;
ALTER TABLE stuff ALTER user_id DROP NOT NULL;
ALTER TABLE stuff ALTER stuffclass SET  DEFAULT NULL;
DROP INDEX IDX_5941F83E62580AED;
DROP INDEX IDX_5941F83EA76ED395;
ALTER TABLE stuff ADD CONSTRAINT FK_5941F83EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE UNIQUE INDEX UNIQ_5941F83EA76ED395 ON stuff (user_id);
ALTER TABLE region ALTER id TYPE INT;
ALTER TABLE region ALTER id DROP DEFAULT;
ALTER TABLE region ALTER square SET NOT NULL;
ALTER TABLE region ALTER population SET NOT NULL;
ALTER TABLE region ALTER flag SET NOT NULL;
DROP INDEX region_name_key;
ALTER TABLE lookup ALTER id TYPE INT;
ALTER TABLE lookup ALTER id DROP DEFAULT;
ALTER TABLE handling_message_type ADD stayActionTime INT DEFAULT NULL;
ALTER TABLE handling_message_type ADD sortorder INT DEFAULT NULL;
ALTER TABLE handling_message_type ALTER id DROP DEFAULT;
ALTER TABLE handling_more_info_type ALTER id DROP DEFAULT;
ALTER TABLE handling_more_info_type DROP CONSTRAINT handling_more_info_type_handling_result_id_handling_result_id;
DROP INDEX handling_result_id_name;
ALTER TABLE handling_more_info_type ADD CONSTRAINT FK_C9D0E6348C30E3C7 FOREIGN KEY (handling_result_id) REFERENCES handling_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_result ADD sortorder INT DEFAULT NULL;
ALTER TABLE handling_result ALTER id DROP DEFAULT;
ALTER TABLE handling ALTER id DROP DEFAULT;
ALTER TABLE handling ALTER organization_id TYPE INT;
ALTER TABLE handling ALTER user_id TYPE INT;
ALTER TABLE handling ALTER user_id DROP NOT NULL;
ALTER TABLE handling ALTER is_closed SET  DEFAULT NULL;
ALTER TABLE handling ALTER last_handling_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF965732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF96577A7B643 FOREIGN KEY (result_id) REFERENCES handling_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF96576BF700BD FOREIGN KEY (status_id) REFERENCES handling_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF9657C54C8C93 FOREIGN KEY (type_id) REFERENCES handling_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF9657A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_user DROP id;
ALTER TABLE handling_user DROP part;
ALTER TABLE handling_user ALTER user_id TYPE INT;
DROP INDEX handling_user_pkey;
ALTER TABLE handling_user ADD CONSTRAINT FK_9FC2E6D75AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_user ADD CONSTRAINT FK_9FC2E6D7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_user ADD PRIMARY KEY (handling_id, user_id);
ALTER TABLE handling_type ADD sortorder INT DEFAULT NULL;
ALTER TABLE handling_type ALTER id DROP DEFAULT;
ALTER TABLE handling_status ADD sortorder INT DEFAULT NULL;
ALTER TABLE handling_status ADD slug VARCHAR(10) DEFAULT NULL;
ALTER TABLE handling_status ADD percentageString VARCHAR(20) DEFAULT NULL;
ALTER TABLE handling_status ADD progress INT DEFAULT NULL;
ALTER TABLE handling_status ALTER id DROP DEFAULT;
ALTER TABLE handling_more_info ALTER id DROP DEFAULT;
ALTER TABLE handling_more_info ALTER handling_id DROP NOT NULL;
ALTER TABLE handling_more_info ALTER handling_more_info_type_id DROP NOT NULL;
ALTER TABLE handling_more_info DROP CONSTRAINT handling_more_info_handling_id_handling_id;
ALTER TABLE handling_more_info DROP CONSTRAINT hhhi;
ALTER TABLE handling_more_info ADD CONSTRAINT FK_BA397C745AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_more_info ADD CONSTRAINT FK_BA397C74CBD40E0B FOREIGN KEY (handling_more_info_type_id) REFERENCES handling_more_info_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_message ADD is_business_trip BOOLEAN DEFAULT NULL;
ALTER TABLE handling_message ADD additional_type VARCHAR(3) DEFAULT NULL;
ALTER TABLE handling_message ALTER id DROP DEFAULT;
ALTER TABLE handling_message ALTER handling_id TYPE INT;
ALTER TABLE handling_message ALTER user_id TYPE INT;
ALTER TABLE handling_message ALTER createdate TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE handling_message ALTER createdate SET NOT NULL;
ALTER TABLE handling_message ADD CONSTRAINT FK_821AF2065AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_message ADD CONSTRAINT FK_821AF206C54C8C93 FOREIGN KEY (type_id) REFERENCES handling_message_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_message ADD CONSTRAINT FK_821AF206A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE model_contact ADD user_id INT DEFAULT NULL;
ALTER TABLE model_contact ADD owner_id INT DEFAULT NULL;
ALTER TABLE model_contact ADD createdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE model_contact ADD ownerdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE model_contact ALTER id DROP DEFAULT;
ALTER TABLE model_contact ADD CONSTRAINT FK_2CF18FC9A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE model_contact ADD CONSTRAINT FK_2CF18FC97E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_2CF18FC9A76ED395 ON model_contact (user_id);
CREATE INDEX IDX_2CF18FC97E3C61F9 ON model_contact (owner_id)
