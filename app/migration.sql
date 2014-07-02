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
-- DROP INDEX department_people_month_info_pkey;
-- ALTER TABLE department_people_month_info ADD CONSTRAINT FK_BA53F01FEAC5BEFA FOREIGN KEY (department_people_id) REFERENCES department_people (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE SEQUENCE department_people_month_info_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
ALTER TABLE department_people_month_info ADD id BIGINT DEFAULT nextval('department_people_month_info_id_seq'::regclass);
ALTER TABLE department_people_month_info ALTER COLUMN id SET NOT NULL;
ALTER TABLE department_people_month_info ADD PRIMARY KEY (id);

CREATE UNIQUE INDEX department_people_month_info_unique_idx ON department_people_month_info (department_people_id, year, month, department_people_replacement_id, replacement_type);


CREATE SEQUENCE once_only_accrual_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE once_only_accrual (id BIGINT NOT NULL DEFAULT nextval('once_only_accrual_id_seq'::regclass), mpk_id BIGINT DEFAULT NULL, department_people_month_info_id BIGINT DEFAULT NULL, work_type VARCHAR(1) NOT NULL, type VARCHAR(2) NOT NULL, code VARCHAR(2) NOT NULL, value DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, is_active BOOLEAN DEFAULT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_85E87E8C895D31C8 ON once_only_accrual (mpk_id);
CREATE INDEX IDX_85E87E8C97E5C034 ON once_only_accrual (department_people_month_info_id);
ALTER TABLE once_only_accrual ADD CONSTRAINT FK_85E87E8C895D31C8 FOREIGN KEY (mpk_id) REFERENCES mpk (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE once_only_accrual ADD CONSTRAINT FK_85E87E8C97E5C034 FOREIGN KEY (department_people_month_info_id) REFERENCES department_people_month_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE;


CREATE SEQUENCE invoice_companystructure_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE invoice_companystructure (id INT NOT NULL, invoice_id INT NOT NULL, companystructure_id INT NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_517FBE652989F1FD ON invoice_companystructure (invoice_id);
CREATE INDEX IDX_517FBE6562580AED ON invoice_companystructure (companystructure_id);
CREATE TABLE invoice_message (id SERIAL NOT NULL, invoice_id INT DEFAULT NULL, user_id INT DEFAULT NULL, model_contact_id INT DEFAULT NULL, note VARCHAR(255) NOT NULL, createdate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_446406AC2989F1FD ON invoice_message (invoice_id);
CREATE INDEX IDX_446406ACA76ED395 ON invoice_message (user_id);
CREATE INDEX IDX_446406AC8E9E2EDD ON invoice_message (model_contact_id);
CREATE TABLE invoicecron (id SERIAL NOT NULL, invoice_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(64) NOT NULL, description TEXT NOT NULL, reason VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_F25776C12989F1FD ON invoicecron (invoice_id);
ALTER TABLE invoice_companystructure ADD CONSTRAINT FK_517FBE652989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice_companystructure ADD CONSTRAINT FK_517FBE6562580AED FOREIGN KEY (companystructure_id) REFERENCES companystructure (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice_message ADD CONSTRAINT FK_446406AC2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice_message ADD CONSTRAINT FK_446406ACA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice_message ADD CONSTRAINT FK_446406AC8E9E2EDD FOREIGN KEY (model_contact_id) REFERENCES model_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoicecron ADD CONSTRAINT FK_F25776C12989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice ADD dogovor_quid VARCHAR(512);
ALTER TABLE invoice ADD dogovor_act TEXT;
ALTER TABLE invoice ADD dogovor_act_note TEXT;
ALTER TABLE invoice ADD customer_name VARCHAR(255) DEFAULT NULL;
ALTER TABLE invoice ADD customer_edrpou VARCHAR(255) DEFAULT NULL;
ALTER TABLE invoice ADD performer_name VARCHAR(255) DEFAULT NULL;
ALTER TABLE invoice ADD performer_edrpou VARCHAR(255) DEFAULT NULL;
ALTER TABLE invoice DROP organization_id;
ALTER TABLE invoice DROP organization_name;
ALTER TABLE invoice DROP organization_edrpou;
ALTER TABLE invoice DROP dogovor_uuie;
ALTER TABLE invoice DROP organization_edrpou_doer;
ALTER TABLE invoice ALTER dogovor_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
ALTER TABLE invoice ALTER dogovor_date DROP NOT NULL;


CREATE SEQUENCE automailer_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE automailer (id INT NOT NULL, from_email VARCHAR(255) NOT NULL, from_name VARCHAR(255) NOT NULL, to_email VARCHAR(255) NOT NULL, subject TEXT NOT NULL, body TEXT NOT NULL, alt_body TEXT NOT NULL, swift_message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, started_sending_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_html BOOLEAN NOT NULL, is_sending BOOLEAN DEFAULT NULL, is_sent BOOLEAN DEFAULT NULL, is_failed BOOLEAN DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX find_next ON automailer (is_sent, is_failed, is_sending, created_at);
CREATE INDEX recover_sending ON automailer (is_sending, started_sending_at);

CREATE TABLE email (id SERIAL NOT NULL, alias VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_E7927C74E16C6B94 ON email (alias);

-- chmod on web/files/upload
-- chmod on web/files/email
-- app/share/1c/debt/

INSERT INTO once_only_accrual
(mpk_id, department_people_month_info_id, work_type, type, code, value, description)
(SELECT
	dp.mpk_id, dpmi.id, dpmi.surcharge_type_key, 'add', 'uu', dpmi.surcharge, dpmi.surcharge_description
FROM
	department_people_month_info dpmi
LEFT JOIN
	department_people dp ON dp.id = dpmi.department_people_id
WHERE
	dpmi.surcharge_type_id = 32 AND
	dpmi.surcharge_type_id IS NOT NULL AND
	dpmi.surcharge_type_key IS NOT NULL AND
	dpmi.surcharge IS NOT NULL AND
	dpmi.surcharge::int <> 0
);

INSERT INTO once_only_accrual
(mpk_id, department_people_month_info_id, work_type, type, code, value, description)
(SELECT
	dp.mpk_id, dpmi.id, dpmi.surcharge_type_key, 'add', 'oz', dpmi.surcharge, dpmi.surcharge_description
FROM
	department_people_month_info dpmi
LEFT JOIN
	department_people dp ON dp.id = dpmi.department_people_id
WHERE
	dpmi.surcharge_type_id <> 32 AND
	dpmi.surcharge_type_id IS NOT NULL AND
	dpmi.surcharge_type_key IS NOT NULL AND
	dpmi.surcharge IS NOT NULL AND
	dpmi.surcharge::int <> 0
);

INSERT INTO once_only_accrual
(mpk_id, department_people_month_info_id, work_type, type, code, value, description)
(SELECT
	dp.mpk_id, dpmi.id, dpmi.bonus_type_key, 'premium', 'uu', dpmi.bonus, dpmi.bonus_description
FROM
	department_people_month_info dpmi
LEFT JOIN
	department_people dp ON dp.id = dpmi.department_people_id
WHERE
	dpmi.bonus_type_id = 32 AND
	dpmi.bonus_type_id IS NOT NULL AND
	dpmi.bonus_type_key IS NOT NULL AND
	dpmi.bonus IS NOT NULL AND
	dpmi.bonus::int <> 0
);

INSERT INTO once_only_accrual
(mpk_id, department_people_month_info_id, work_type, type, code, value, description)
(SELECT
	dp.mpk_id, dpmi.id, dpmi.bonus_type_key, 'premium', 'oz', dpmi.bonus, dpmi.bonus_description
FROM
	department_people_month_info dpmi
LEFT JOIN
	department_people dp ON dp.id = dpmi.department_people_id
WHERE
	dpmi.bonus_type_id <> 32 AND
	dpmi.bonus_type_id IS NOT NULL AND
	dpmi.bonus_type_key IS NOT NULL AND
	dpmi.bonus IS NOT NULL AND
	dpmi.bonus::int <> 0
);

INSERT INTO once_only_accrual
(mpk_id, department_people_month_info_id, work_type, type, code, value, description)
(SELECT
	dp.mpk_id, dpmi.id, dpmi.fine_type_key, 'fine', 'uu', dpmi.fine, dpmi.fine_description
FROM
	department_people_month_info dpmi
LEFT JOIN
	department_people dp ON dp.id = dpmi.department_people_id
WHERE
	dpmi.fine_type_id = 32 AND
	dpmi.fine_type_id IS NOT NULL AND
	dpmi.fine_type_key IS NOT NULL AND
	dpmi.fine IS NOT NULL AND
	dpmi.fine::int <> 0
);

INSERT INTO once_only_accrual
(mpk_id, department_people_month_info_id, work_type, type, code, value, description)
(SELECT
	dp.mpk_id, dpmi.id, dpmi.fine_type_key, 'fine', 'oz', dpmi.fine, dpmi.fine_description
FROM
	department_people_month_info dpmi
LEFT JOIN
	department_people dp ON dp.id = dpmi.department_people_id
WHERE
	dpmi.fine_type_id <> 32 AND
	dpmi.fine_type_id IS NOT NULL AND
	dpmi.fine_type_key IS NOT NULL AND
	dpmi.fine IS NOT NULL AND
	dpmi.fine::int <> 0
);


-- email templates update
UPDATE "public".email SET "text" = '<table><tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;">   <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://t.itdoors.com.ua/templates/metronic/img/social/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;background-position-y: -38px;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://t.itdoors.com.ua/templates/metronic/img/social/twitter.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;background-position-y: -38px;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://t.itdoors.com.ua/templates/metronic/img/social/googleplus.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;background-position-y: -38px;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://t.itdoors.com.ua/templates/metronic/img/social/linkedin.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;background-position-y: -38px;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;padding-right: 0 !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://t.itdoors.com.ua/templates/metronic/img/social/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;background-position-y: -38px;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;padding-right: 0 !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://t.itdoors.com.ua/templates/metronic/img/social/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;background-position-y: -38px;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Результаты о принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ создал решение № ${id}$ дата:  ${datePublic}$ ${title}$. <br> Срок принятия решения <span style="text-align: inherit; line-height: 1.428571429; background-color: transparent;">${dateUnpublic}$ окончен</span><span style="text-align: inherit; line-height: 1.428571429; background-color: transparent;">. </span></p><p><br> Результат решения: ${status}$</p>                                 </td>                             </tr>                         </tbody></table> ${table}$                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr> <tr> <td><table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px; background-color: rgb(255, 255, 225);">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table> <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p></td> </tr>             </tbody></table>              <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. <br>Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table></td></tr></tbody></table></td></tr> </tbody></table>' WHERE alias = 'decision-result';
