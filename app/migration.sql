CREATE SEQUENCE fos_group_id_seq INCREMENT BY 1 MINVALUE 0 START 0;
CREATE SEQUENCE fos_user_id_seq INCREMENT BY 1 MINVALUE 0 START 0;
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

# app/console sd:group:role-add SALES SALES
# app/console sd:group:role-add SALESADMIN SALESADMIN
# app/console sd:group:role-add SALESDISPATCHER SALESDISPATCHER
# app/console sd:user:migrate


CREATE TABLE handling_service (id BIGINT NOT NULL, name VARCHAR(128) NOT NULL, slug VARCHAR(128) DEFAULT NULL, sortorder INT DEFAULT NULL, PRIMARY KEY(id));
CREATE TABLE handling_handling_service (handling_id BIGINT NOT NULL, service_id BIGINT NOT NULL, PRIMARY KEY(handling_id, service_id));
CREATE INDEX IDX_AC5EC5345AB3F1F ON handling_handling_service (handling_id);
CREATE INDEX IDX_AC5EC534ED5CA9E6 ON handling_handling_service (service_id);
CREATE TABLE team (id BIGINT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, descriprion TEXT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_C4E0A61F7E3C61F9 ON team (owner_id);
CREATE TABLE team_user (handling_id BIGINT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(handling_id, user_id));
CREATE INDEX IDX_5C7222325AB3F1F ON team_user (handling_id);
CREATE INDEX IDX_5C722232A76ED395 ON team_user (user_id);


sha1
067f9c5e718c84cf425b381589155fb3
aeb5dd988fb28afc8383576de15e800762bf8df0


ALTER TABLE fos_user_group ADD CONSTRAINT FK_583D1F3EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE fos_user_group ADD CONSTRAINT FK_583D1F3EFE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_handling_service ADD CONSTRAINT FK_AC5EC5345AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_handling_service ADD CONSTRAINT FK_AC5EC534ED5CA9E6 FOREIGN KEY (service_id) REFERENCES handling_service (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE team_user ADD CONSTRAINT FK_5C7222325AB3F1F FOREIGN KEY (handling_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE team_user ADD CONSTRAINT FK_5C722232A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;


ALTER TABLE organization ADD createdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE organization ADD physical_address VARCHAR(255) DEFAULT NULL;
ALTER TABLE organization ADD phone VARCHAR(50) DEFAULT NULL;
ALTER TABLE organization ADD creator_id INT DEFAULT NULL;
ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C61220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_C1EE637C61220EA6 ON organization (creator_id);


ALTER TABLE stuff ADD CONSTRAINT FK_5941F83EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE UNIQUE INDEX UNIQ_5941F83EA76ED395 ON stuff (user_id);


ALTER TABLE handling_message_type ADD stayActionTime INT DEFAULT NULL;
ALTER TABLE handling_message_type ADD sortorder INT DEFAULT NULL;
ALTER TABLE handling_result ADD sortorder INT DEFAULT NULL;


ALTER TABLE handling ALTER last_handling_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF965732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF96577A7B643 FOREIGN KEY (result_id) REFERENCES handling_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF96576BF700BD FOREIGN KEY (status_id) REFERENCES handling_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF9657C54C8C93 FOREIGN KEY (type_id) REFERENCES handling_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling ADD CONSTRAINT FK_BFF9657A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;



ALTER TABLE handling_user ADD CONSTRAINT FK_9FC2E6D75AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_user ADD CONSTRAINT FK_9FC2E6D7A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_type ADD sortorder INT DEFAULT NULL;



ALTER TABLE handling_status ADD sortorder INT DEFAULT NULL;
ALTER TABLE handling_status ADD slug VARCHAR(10) DEFAULT NULL;
ALTER TABLE handling_status ADD percentageString VARCHAR(20) DEFAULT NULL;
ALTER TABLE handling_status ADD progress INT DEFAULT NULL;
ALTER TABLE handling_message ADD is_business_trip BOOLEAN DEFAULT NULL;
ALTER TABLE handling_message ADD additional_type VARCHAR(3) DEFAULT NULL;


ALTER TABLE handling_message ALTER createdate TYPE TIMESTAMP(0) WITHOUT TIME ZONE;


ALTER TABLE handling_message ADD CONSTRAINT FK_821AF2065AB3F1F FOREIGN KEY (handling_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_message ADD CONSTRAINT FK_821AF206C54C8C93 FOREIGN KEY (type_id) REFERENCES handling_message_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE handling_message ADD CONSTRAINT FK_821AF206A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;


ALTER TABLE model_contact ADD user_id INT DEFAULT NULL;
ALTER TABLE model_contact ADD owner_id INT DEFAULT NULL;

ALTER TABLE model_contact ADD createdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE model_contact ADD ownerdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE model_contact ADD CONSTRAINT FK_2CF18FC97E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_2CF18FC9A76ED395 ON model_contact (user_id);
CREATE INDEX IDX_2CF18FC97E3C61F9 ON model_contact (owner_id);


update
	model_contact
set
	user_id = (select ou.user_id from organization_user ou where ou.organization_id = model_contact.model_id limit 1)
where
	user_id is null AND
	owner_id is null AND
	model_name = 'organization';

update
	model_contact
set
	owner_id = user_id
where
	owner_id is null AND
	model_name = 'organization';

update
	organization
set
	creator_id = (select ou.user_id from organization_user ou where ou.organization_id = organization.id limit 1)
where
	creator_id is null;

+++++++++++++++++++++++++++++++++++++++++++

update handling set next_handling_date =
(
	select
		hm.createdate
	from
		handling_message hm
	where
		hm.handling_id = handling.id
	order by
		hm.id
	limit 1
)

where next_handling_date is null;

+++++++++++++++++++++++

ALTER TABLE handling ADD closer_id INT DEFAULT NULL;
ALTER TABLE handling ADD closedatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE handling ALTER is_closed SET  DEFAULT NULL;
ALTER TABLE handling ADD CONSTRAINT FK_BFF9657FD0FD350 FOREIGN KEY (closer_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_BFF9657FD0FD350 ON handling (closer_id);


+++++++++++
ALTER TABLE handling_message ADD contact_id BIGINT DEFAULT NULL;
ALTER TABLE handling_message ADD CONSTRAINT FK_821AF206E7A1254A FOREIGN KEY (contact_id) REFERENCES model_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_821AF206E7A1254A ON handling_message (contact_id);
++++++++++

CREATE SEQUENCE organization_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE organization_group (id BIGINT NOT NULL, slug VARCHAR(20) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));
ALTER TABLE organization ADD group_id BIGINT DEFAULT NULL;
ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CFE54D947 FOREIGN KEY (group_id) REFERENCES organization_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

++++++++++

/*CREATE LANGUAGE plpgsql;*/

CREATE OR REPLACE FUNCTION sf_guard_user_to_fos_user()
  RETURNS trigger AS
$BODY$
DECLARE
    tempSalt varchar(128);
    tempPassword varchar(128);
BEGIN
	tempSalt = '777';
	tempPassword = '000';
	IF NEW.salt is not null THEN
		tempSalt = NEW.salt;
	END IF;
	IF NEW.password is not null THEN
		tempPassword = NEW.password;
	END IF;
    IF NOT EXISTS (select id from fos_user where id = NEW.id) THEN
        INSERT INTO fos_user(id,username,username_canonical,email,email_canonical,first_name,last_name,middle_name,password,salt,birthday,enabled,locked,expired,roles,credentials_expired)
        values (NEW.id,NEW.username,NEW.username,NEW.email_address,NEW.email_address,NEW.first_name,NEW.last_name,NEW.middle_name,tempPassword,tempSalt,NEW.birthday,true,NEW.is_blocked,false,'a:0:{}',false);
    END IF;
    return NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

DROP TRIGGER IF EXISTS sf_to_fos_trigger ON sf_guard_user;

CREATE TRIGGER sf_to_fos_trigger
AFTER INSERT ON sf_guard_user FOR EACH ROW EXECUTE PROCEDURE sf_guard_user_to_fos_user();

+++++++++++++++++++++++

ALTER TABLE organization ADD parent_id INT DEFAULT NULL;
ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C727ACA70 FOREIGN KEY (parent_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_C1EE637C727ACA70 ON organization (parent_id);
select table_name from information_schema.columns where column_name = 'organization_id';


DROP FUNCTION IF EXISTS show_users_data_to_delete(user_id int);
CREATE OR REPLACE FUNCTION show_users_data_to_delete(user_id int) RETURNS varchar[] AS $$
DECLARE
    table_record   record;
    nbrow varchar[];
    queryResult record;
BEGIN
    FOR table_record IN (select table_name from information_schema.columns where column_name = 'user_id') LOOP
        FOR queryResult IN EXECUTE 'SELECT r::text FROM ' || table_record.table_name || ' as r where r.user_id = ' || user_id LOOP
		IF queryResult IS NOT NULL THEN
			nbrow = array_append(nbrow, (table_record.table_name || ' ' || queryResult)::varchar);
		END IF;
        END LOOP;
    END LOOP;
    RETURN nbrow;
END$$ LANGUAGE 'plpgsql';

DROP FUNCTION IF EXISTS show_stuff_data_to_delete(stuff_id int);
CREATE OR REPLACE FUNCTION show_stuff_data_to_delete(stuff_id int) RETURNS varchar[] AS $$
DECLARE
    table_record   record;
    nbrow varchar[];
    queryResult record;
BEGIN
    FOR table_record IN (select table_name from information_schema.columns where column_name = 'stuff_id') LOOP
        FOR queryResult IN EXECUTE 'SELECT r::text FROM ' || table_record.table_name || ' as r where r.stuff_id = ' || stuff_id LOOP
		IF queryResult IS NOT NULL THEN
			nbrow = array_append(nbrow, (table_record.table_name || ' ' || queryResult)::varchar);
		END IF;
        END LOOP;
    END LOOP;
    RETURN nbrow;
END$$ LANGUAGE 'plpgsql';

select unnest(show_users_data_to_delete(312))
select unnest(show_stuff_data_to_delete(117))

++++++++++++

SELECT MAX(id) FROM fos_user;
SELECT nextval('fos_user_id_seq');
SELECT setval('fos_user_id_seq', (SELECT MAX(id) FROM fos_user));


CREATE OR REPLACE FUNCTION fos_user_to_sf_guard_user()
  RETURNS trigger AS
$BODY$
BEGIN
    IF NOT EXISTS (select id from sf_guard_user where id = NEW.id) THEN
        INSERT INTO sf_guard_user(id,username,email_address,first_name,last_name,middle_name,password,salt,birthday,is_active, created_at, updated_at)
        values (NEW.id,NEW.username,NEW.email,NEW.first_name,NEW.last_name,NEW.middle_name,NEW.password,NEW.salt,NEW.birthday,true, now(), now());
    END IF;
    return NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

DROP TRIGGER IF EXISTS fos_to_sf_trigger ON fos_user;

CREATE TRIGGER fos_to_sf_trigger
AFTER INSERT ON fos_user FOR EACH ROW EXECUTE PROCEDURE fos_user_to_sf_guard_user();

CREATE OR REPLACE FUNCTION sf_guard_user_to_fos_user()
  RETURNS trigger AS
$BODY$
BEGIN
    IF NOT EXISTS (select id from fos_user where id = NEW.id) THEN
        INSERT INTO fos_user(id,username,username_canonical,email,email_canonical,first_name,last_name,middle_name,password,salt,birthday,enabled,locked,expired,roles,credentials_expired)
        values (NEW.id,NEW.username,NEW.username,NEW.email_address,NEW.email_address,NEW.first_name,NEW.last_name,NEW.middle_name,NEW.password,NEW.salt,NEW.birthday,true,NEW.is_blocked,false,'a:0:{}',false);
    END IF;
    return NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

DROP TRIGGER IF EXISTS sf_to_fos_trigger ON sf_guard_user;

CREATE TRIGGER sf_to_fos_trigger
AFTER INSERT ON sf_guard_user FOR EACH ROW EXECUTE PROCEDURE sf_guard_user_to_fos_user();

++++++++++++++++++

CREATE OR REPLACE FUNCTION sf_guard_user_change_pass()
  RETURNS trigger AS
$BODY$
BEGIN
	UPDATE fos_user set password = NEW.password, salt = NEW.salt WHERE id = NEW.id;
	return NEW;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
  COST 100;

  DROP TRIGGER IF EXISTS sf_change_pass_trigger ON sf_guard_user;

CREATE TRIGGER sf_change_pass_trigger
BEFORE UPDATE ON sf_guard_user FOR EACH ROW EXECUTE PROCEDURE sf_guard_user_change_pass();
+++++++++++++++++++++++++++++++++++++++

DROP FUNCTION IF EXISTS replace_manager(from_id int, to_id int);
CREATE OR REPLACE FUNCTION replace_manager(from_id int, to_id int) RETURNS void AS $$
BEGIN
    UPDATE model_contact SET owner_id = to_id WHERE owner_id = from_id;
    DELETE FROM organization_user WHERE user_id = from_id AND EXISTS (SELECT ou.user_id from organization_user ou WHERE user_id = to_id AND ou.organization_id = organization_user.organization_id);
    UPDATE organization_user SET user_id = to_id WHERE user_id = from_id;
    DELETE FROM handling_user WHERE user_id = from_id AND EXISTS (SELECT hu.user_id from handling_user hu WHERE user_id = to_id AND hu.handling_id = handling_user.handling_id);
    UPDATE handling_user SET user_id = to_id WHERE user_id = from_id;
END$$ LANGUAGE 'plpgsql';

select replace_manager(324, 327);
+++++++++++++++++

ALTER TABLE dogovor ADD customer_id BIGINT DEFAULT NULL;
ALTER TABLE dogovor ADD performer_id BIGINT DEFAULT NULL;
ALTER TABLE dogovor ADD prolongation_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE dogovor ALTER organization_id DROP NOT NULL;

++++

ALTER TABLE dogovor ADD saller_id INT DEFAULT NULL;
ALTER TABLE dogovor ADD CONSTRAINT FK_5F7235959A22E23 FOREIGN KEY (saller_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_5F7235959A22E23 ON dogovor (saller_id);

+++++++++++++++

ALTER TABLE dop_dogovor ADD COLUMN saller_id integer;
ALTER TABLE dop_dogovor ADD CONSTRAINT FK_15DDC3FF1CEE4D62 FOREIGN KEY (saller_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE dop_dogovor ADD CONSTRAINT FK_15DDC3FFA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
UPDATE
  dop_dogovor
SET
  saller_id = (select user_id from stuff where id = dop_dogovor.stuff_id limit 1)
WHERE
  stuff_id IS NOT NULL;

+++++++++++++++++

UPDATE
	dogovor
SET
	customer_id = organization_id,
	performer_id = 23
WHERE
	company_role_id in (20,21,23);

UPDATE
	dogovor
SET
	customer_id = 23,
	performer_id = organization_id
WHERE
	company_role_id = 22;

UPDATE
	dogovor
SET
	customer_id = organization_id,
	performer_id = 23
WHERE
	company_role_id is null AND
	dogovor_type_id in (13, 14);

UPDATE
	dogovor
SET
	prolongation_date = stopdatetime
WHERE
	prolongation_date IS NULL;

++++++++++


CREATE SEQUENCE dogovor_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE dogovor_history (id BIGINT NOT NULL, dogovor_id INT NOT NULL, dop_dogovor_id INT NOT NULL, user_id INT DEFAULT NULL, createdatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, prolongation_term VARCHAR(255) DEFAULT NULL, prolongation_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_3260558681A36DD2 ON dogovor_history (dogovor_id);
CREATE INDEX IDX_32605586C06CDB2C ON dogovor_history (dop_dogovor_id);
CREATE INDEX IDX_32605586A76ED395 ON dogovor_history (user_id);
ALTER TABLE dogovor_history ADD CONSTRAINT FK_3260558681A36DD2 FOREIGN KEY (dogovor_id) REFERENCES dogovor (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE dogovor_history ADD CONSTRAINT FK_32605586C06CDB2C FOREIGN KEY (dop_dogovor_id) REFERENCES dop_dogovor (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE dogovor_history ADD CONSTRAINT FK_32605586A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

+++++++++++
