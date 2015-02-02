CREATE SEQUENCE fos_user_id_seq INCREMENT BY 1 MINVALUE 0 START 0;
CREATE SEQUENCE fos_group_id_seq INCREMENT BY 1 MINVALUE 0 START 0;
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
UPDATE "public".email SET "text" = '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас пригласили поучаствовать в принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ пригласил Вас оставить мнение о решении номер: ${id}$ дата:  ${datePublic}$ ${title}$. <br> Конечный срок принятия решения <b>${dateUnpublic}$</b>. <br><br> Пожалуйста, перейдите по ссылке, чтобы оставить решение:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>' WHERE alias = 'decision-making';
UPDATE "public".email SET "text" = '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Результаты о принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ создал решение № ${id}$ дата:  ${datePublic}$ ${title}$. <br> Срок принятия решения <span style="text-align: inherit; line-height: 1.428571429; background-color: transparent;">${dateUnpublic}$ окончен</span><span style="text-align: inherit; line-height: 1.428571429; background-color: transparent;">. </span></p><p><br> Результат решения: ${status}$</p>                                 </td>                             </tr>                         </tbody></table> ${table}$                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr> <tr> <td><table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table> <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p></td> </tr>             </tbody></table>              <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. <br>Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>' WHERE alias = 'decision-result';
UPDATE "public".email SET "text" = '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас пригласили поучаствовать в принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ пригласил Вас оставить мнение о решении номер: ${id}$ дата:  ${datePublic}$ ${title}$. <br> Конечный срок принятия решения <b>${dateUnpublic}$</b>. <br><br><span style="font-weight: bold;">У Вас осталось 15 минут!</span><br><br> Пожалуйста, перейдите по ссылке, чтобы оставить решение:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>' WHERE alias = 'decision-making-only-15-minutes';
--test   ++++++++++
--stagin ++++++++++

-- invoice
ALTER TABLE invoice ADD customer_id INT DEFAULT NULL;
ALTER TABLE invoice ADD performer_id INT DEFAULT NULL;
ALTER TABLE invoice ADD CONSTRAINT FK_906517449395C3F3 FOREIGN KEY (customer_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice ADD CONSTRAINT FK_906517446C6B33F3 FOREIGN KEY (performer_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_906517449395C3F3 ON invoice (customer_id);
CREATE INDEX IDX_906517446C6B33F3 ON invoice (performer_id);
UPDATE "public".lookup SET "group" = 'organization_sign' WHERE id = 61;
UPDATE "public".lookup SET "group" = 'organization_sign' WHERE id = 60;
--test   ++++++++++++++++++++++++++++++++++++++++
--stagin -----------------------------------------------

-- task
CREATE TABLE task (id SERIAL NOT NULL, user_id INT DEFAULT NULL, createdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, task_type VARCHAR(128) DEFAULT NULL, startdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, stopdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id);
ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
--test   +++++++++++++++++++++++++++++++++++++++++++++++++
--stagin -----------------------------------------------

-- task
ALTER TABLE invoice RENAME COLUMN dogovor_act_note TO bank;
--test   ++++++++++++++++++++++++++++++++++++++++++++++++
--stagin -----------------------------------------------

CREATE TABLE model_contact_send_email (id BIGSERIAL NOT NULL, model_contact_id INT DEFAULT NULL, is_send BOOLEAN NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_F0569F38E9E2EDD ON model_contact_send_email (model_contact_id);
ALTER TABLE model_contact_send_email ADD CONSTRAINT FK_F0569F38E9E2EDD FOREIGN KEY (model_contact_id) REFERENCES model_contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
--test   ++++++++++++++++++++++++++++++++++++++++++++++
--stagin -----------------------------------------------

UPDATE "public".email SET "text" = '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас пригласили поучаствовать в принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ пригласил Вас оставить мнение о решении номер: ${id}$ дата:  ${datePublic}$ ${title}$. <br> Конечный срок принятия решения <b>${dateUnpublic}$</b>. <br><br> Пожалуйста, перейдите по ссылке, чтобы оставить решение:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>' WHERE alias = 'decision-making';
UPDATE "public".email SET "text" = '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Результаты о принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ создал решение № ${id}$ дата:  ${datePublic}$ ${title}$. <br> Срок принятия решения <span style="text-align: inherit; line-height: 1.428571429; background-color: transparent;">${dateUnpublic}$ окончен</span><span style="text-align: inherit; line-height: 1.428571429; background-color: transparent;">. </span></p><p><br> Результат решения: ${status}$</p>                                 </td>                             </tr>                         </tbody></table> ${table}$                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr> <tr> <td><table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table> <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p></td> </tr>             </tbody></table>              <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. <br>Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>' WHERE alias = 'decision-result';
UPDATE "public".email SET "text" = '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас пригласили поучаствовать в принятии решения № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ пригласил Вас оставить мнение о решении номер: ${id}$ дата:  ${datePublic}$ ${title}$. <br> Конечный срок принятия решения <b>${dateUnpublic}$</b>. <br><br><span style="font-weight: bold;">У Вас осталось 15 минут!</span><br><br> Пожалуйста, перейдите по ссылке, чтобы оставить решение:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>' WHERE alias = 'decision-making-only-15-minutes';
INSERT INTO "public".email ("alias", subject, text) VALUES ('invoice-not-pay', 'Платеж не поступил.', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"><h4 style="display: block; margin: 5px 0 15px 0;">${firstName}$ <span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${firstName}$ </span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${middleName}$</span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">. </span></h4><h4 style="display: block; margin: 5px 0 15px 0;"><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">Согласно условий Договора № ${number}$ от ${date}$г. от Вас на компанию ${performer}$  должна была поступить оплата счетов по суммам указаным ниже:</span></h4><div><span style="line-height: 14.300000190734863px;">${table}$</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div><div><span style="line-height: 14.300000190734863px;">Просьба в ближайшее время осуществить оплату. Спасибо. Удачного рабочего дня.</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div>                                                                                                                      </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table></td></tr></tbody></table>');
INSERT INTO "public".email ("alias", subject, text) VALUES ('invoice-pay', 'Счет оплачен.', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"><h4 style="display: block; margin: 5px 0 15px 0;">${firstName}$ <span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${firstName}$ </span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${middleName}$</span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">. </span></h4><h4 style="display: block; margin: 5px 0 15px 0;"><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">Согласно условий Договора № ${number}$ от ${date}$г. от Вас на компанию ${performer}$ поступила оплата счетов по суммам указаным ниже:</span></h4><div><span style="line-height: 14.300000190734863px;">${table}$</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div><div><span style="line-height: 14.300000190734863px;"> Спасибо. Удачного рабочего дня.</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div>                                                                                                                      </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table></td></tr></tbody></table>');
--test   ++++++++++++++++++++++++++++++++++++++++++
--stagin ----------------------------------------------

INSERT INTO "public".lookup (lukey, "name", "group") VALUES ('manager_project', 'Менеджер проекта', 'manager_role')
INSERT INTO "public".lookup (lukey, "name", "group") VALUES ('manager', 'Менеджер', 'manager_role')
INSERT INTO "public".email ("alias", subject, text) VALUES ('manager-add-in-project', 'Вас добавили в проект № ${id}$', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас пригласили в проект № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ пригласил(a) Вас в проект с долей участия ${part}$. <br><br>Ознакомится с проектом вы можете по ссылке:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>');
INSERT INTO "public".email ("alias", subject, text) VALUES ('manager-delete-of-project', 'Вас удалили с проекта № ${id}$', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас удалили с проекта № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ удалил(a) Вас с проекта с долей участия ${part}$. <br></p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>');
INSERT INTO "public".email ("alias", subject, text) VALUES ('manager-change-part-in-project', 'Вам изменили процент участия в проекте № ${id}$', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вам изменили процент участия в  проекте № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ изменил(a) Вам долю участия ${part}$. <br><br>Посмотреть проект  Вы можете по ссылке:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>');

ALTER TABLE handling_user ADD lookup_id INT DEFAULT NULL;
ALTER TABLE handling_user ALTER handling_id DROP NOT NULL;
ALTER TABLE handling_user ALTER user_id TYPE INT;
ALTER TABLE handling_user ALTER user_id DROP NOT NULL;
ALTER TABLE handling_user ADD CONSTRAINT FK_9FC2E6D78955C49D FOREIGN KEY (lookup_id) REFERENCES lookup (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_9FC2E6D78955C49D ON handling_user (lookup_id);
--test   ---------------------
--stagin ---------------------
ALTER TABLE organization_user ADD lookup_id INT DEFAULT NULL;
ALTER TABLE organization_user ALTER organization_id TYPE INT;
ALTER TABLE organization_user ALTER organization_id DROP DEFAULT;
ALTER TABLE organization_user ALTER user_id TYPE INT;
ALTER TABLE organization_user ALTER user_id DROP DEFAULT;
ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D48955C49D FOREIGN KEY (lookup_id) REFERENCES lookup (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_B49AE8D48955C49D ON organization_user (lookup_id);

INSERT INTO "public".lookup (lukey, "name", "group") VALUES ('manager_organization', 'Менеджер организации', 'manager_role_organization');

ALTER TABLE grafik ADD COLUMN is_own_vacation boolean;
ALTER TABLE grafik ALTER COLUMN is_own_vacation SET DEFAULT false;
--test   -----------------------------
--stagin -----------------------------

---task-800.801,802 kved
CREATE SEQUENCE kved_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 2
  CACHE 1;
CREATE SEQUENCE kved_organization_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 3
  CACHE 1;
CREATE TABLE kved
(
  id bigint NOT NULL DEFAULT nextval('kved_id_seq'::regclass),
  code character varying(10),
  name character varying(255),
  description character varying(255),
  parent_id bigint,
  CONSTRAINT kved_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
COMMENT ON TABLE kved
  IS 'классификация видов економической деятельности';

CREATE TABLE kved_organization
(
  id bigint NOT NULL DEFAULT nextval('kved_organization_id_seq'::regclass),
  kved_id bigint,
  organization_id bigint,
  CONSTRAINT kved_organization_pkey PRIMARY KEY (id),
  CONSTRAINT kved_organization_kved_id_fkey FOREIGN KEY (kved_id)
      REFERENCES kved (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT kved_organization_organization_id_fkey FOREIGN KEY (organization_id)
      REFERENCES organization (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
COMMENT ON TABLE kved_organization
  IS 'Связка организаций с КВЕД';

-- staging ++++++++++++++++++++
-- prod +++++++++++++++++++++++

CREATE SEQUENCE documents_organization_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 3
  CACHE 1;
CREATE TABLE documents_organization
(
  id bigint NOT NULL DEFAULT nextval('documents_organization_id_seq'::regclass),
  documents_id bigint NOT NULL,
  organization_id bigint NOT NULL,
  CONSTRAINT documents_organization_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
COMMENT ON TABLE documents_organization
  IS 'Связка документов с организациями';

-- staging ++++++++++++++++++++++
-- prod +++++++++++++++++++++++++

---task-handling form upgrade
CREATE SEQUENCE handling_message_model_contact_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 6
  CACHE 1;
CREATE SEQUENCE handling_message_handling_user_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 5
  CACHE 1;

CREATE TABLE handling_message_handling_user
(
  id bigint NOT NULL DEFAULT nextval('handling_message_handling_user_id_seq'::regclass),
  handling_user_id bigint NOT NULL,
  handling_message_id bigint NOT NULL,
  CONSTRAINT handling_message_user_pkey PRIMARY KEY (id),
  CONSTRAINT handling_message_handling_user_handling_message_id_fkey FOREIGN KEY (handling_message_id)
      REFERENCES handling_message (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT handling_message_handling_user_handling_user_id_fkey FOREIGN KEY (handling_user_id)
      REFERENCES handling_user (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

COMMENT ON TABLE handling_message_handling_user
  IS 'Связка пользователей с активностью';


CREATE TABLE handling_message_model_contact
(
  id bigint NOT NULL DEFAULT nextval('handling_message_model_contact_id_seq'::regclass),
  handling_message_id bigint,
  model_contact_id bigint,
  CONSTRAINT handling_message_model_contact_pkey PRIMARY KEY (id),
  CONSTRAINT handling_message_model_contact_handling_message_id_fkey FOREIGN KEY (handling_message_id)
      REFERENCES handling_message (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT handling_message_model_contact_model_contact_id_fkey FOREIGN KEY (model_contact_id)
      REFERENCES model_contact (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

COMMENT ON TABLE handling_message_model_contact
  IS 'связка активности с контактами';
-- staging ++++++++++++++++++++++++
-- prod +++++++++++++++++++++++++++

CREATE TABLE invoice_payments (id SERIAL NOT NULL, invoice_id INT NOT NULL, summa DOUBLE PRECISION NOT NULL, date DATE NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_7AFAC16A2989F1FD ON invoice_payments (invoice_id);
ALTER TABLE invoice_payments ADD CONSTRAINT FK_7AFAC16A2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
INSERT INTO "public".lookup (lukey, "name", "group") 
	VALUES ('organization_sign_contractor', 'Подрядчики', 'organization_sign_contractor');

-- staging ++++++++++++++++++++++++
-- prod +++++++++++++++++++++++++++


CREATE TABLE organization_service_cover
(
  id bigserial NOT NULL,
  organization_id integer,
  service_id integer,
  is_interested boolean,
  is_working boolean,
  end_date date,
  responsible text,
  description text,
  evaluation integer,
  competitor_id integer,
  creator_id integer NOT NULL,
  CONSTRAINT organization_service_cover_pkey PRIMARY KEY (id),
  CONSTRAINT fk_390a9cb232c8a3de FOREIGN KEY (organization_id)
      REFERENCES organization (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_390a9cb261220ea6 FOREIGN KEY (creator_id)
      REFERENCES fos_user (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_390a9cb278a5d405 FOREIGN KEY (competitor_id)
      REFERENCES organization (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_390a9cb2ed5ca9e6 FOREIGN KEY (service_id)
      REFERENCES handling_service (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

-- Index: idx_390a9cb232c8a3de

-- DROP INDEX idx_390a9cb232c8a3de;

CREATE INDEX idx_390a9cb232c8a3de
  ON organization_service_cover
  USING btree
  (organization_id);

-- Index: idx_390a9cb261220ea6

-- DROP INDEX idx_390a9cb261220ea6;

CREATE INDEX idx_390a9cb261220ea6
  ON organization_service_cover
  USING btree
  (creator_id);

-- Index: idx_390a9cb278a5d405

-- DROP INDEX idx_390a9cb278a5d405;

CREATE INDEX idx_390a9cb278a5d405
  ON organization_service_cover
  USING btree
  (competitor_id);

-- Index: idx_390a9cb2ed5ca9e6

-- DROP INDEX idx_390a9cb2ed5ca9e6;

CREATE INDEX idx_390a9cb2ed5ca9e6
  ON organization_service_cover
  USING btree
  (service_id);
-- staging ----------------------
-- prod ++++++++++++++++++++++++
ALTER TABLE model_contact ALTER phone1 DROP NOT NULL;
-- staging ----------------------
-- prod ++++++++++++++++++++++++++++


ALTER TABLE task ADD COLUMN is_done boolean;
ALTER TABLE task ALTER COLUMN is_done SET DEFAULT false;
COMMENT ON COLUMN task.is_done IS 'Выполнен ли таск';
-- staging ----------------------
-- prod +++++++++++++++++++++++++

ALTER TABLE task ADD COLUMN performer_id bigint;
COMMENT ON COLUMN task.performer_id IS 'Исполнитель';
ALTER TABLE task
  ADD CONSTRAINT task_performer_id_fkey FOREIGN KEY (performer_id)
      REFERENCES fos_user (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;
-- staging ----------------------
-- prod +++++++++++++++++++++++++
ALTER TABLE organization_user RENAME COLUMN lookup_id TO role_id;
DROP INDEX idx_b49ae8d48955c49d;
ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4D60322AC FOREIGN KEY (role_id) REFERENCES lookup (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_B49AE8D4D60322AC ON organization_user (role_id);
-- staging ----------------------
-- prod -------------------------
UPDATE "public".email SET "text" = '<table> <tbody><tr>  <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"><h4 style="display: block; margin: 5px 0 15px 0;">${lastName}$ <span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${firstName}$ </span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${middleName}$. Добрый день</span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">. </span></h4><h4 style="display: block; margin: 5px 0 15px 0;"><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">Согласно условий Договора № ${number}$ от ${date}$г. от Вас на компанию ${performer}$  должна была поступить оплата счетов по суммам указаным ниже:</span></h4><div><span style="line-height: 14.300000190734863px;">${table}$</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div><div><span style="line-height: 14.300000190734863px;">Просьба в ближайшее время осуществить оплату. Спасибо. Удачного рабочего дня.</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div>                                                                                                                      </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table></td></tr></tbody></table>' WHERE alias = 'invoice-not-pay';
UPDATE "public".email SET "text" = '<table> <tbody><tr>  <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"><h4 style="display: block; margin: 5px 0 15px 0;">${lastName}$ <span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${firstName}$ </span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">${middleName}$. Добрый день</span><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">. </span></h4><h4 style="display: block; margin: 5px 0 15px 0;"><span style="color: inherit; line-height: 1.1; text-align: inherit; background-color: transparent;">Согласно условий Договора № ${number}$ от ${date}$г. от Вас на компанию ${performer}$ поступила оплата счетов по суммам указаным ниже:</span></h4><div><span style="line-height: 14.300000190734863px;">${table}$</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div><div><span style="line-height: 14.300000190734863px;"> Спасибо. Удачного рабочего дня.</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div>                                                                                                                      </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table></td></tr></tbody></table>' WHERE alias = 'invoice-pay';
ALTER TABLE invoice_payments ADD bank TEXT DEFAULT NULL;
-- staging ----------------------
-- prod ++++++++++++++++++++++++
ALTER TABLE task ADD COLUMN handling_message_id bigint;
-- staging ----------------------
-- prod ++++++++++++++++++++++++
ALTER TABLE invoice DROP dogovor_act_name;
ALTER TABLE invoice DROP dogovor_act_date;
ALTER TABLE invoice DROP dogovor_act_original;
ALTER TABLE invoice DROP dogovor_act;
CREATE TABLE invoice_act (id SERIAL NOT NULL, invoice_id INT NOT NULL, number VARCHAR(512) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, original BOOLEAN NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_71D9A872989F1FD ON invoice_act (invoice_id);
CREATE TABLE invoice_act_detal (id SERIAL NOT NULL, invoice_act_id INT NOT NULL, mpk VARCHAR(512) NOT NULL, note TEXT NOT NULL, count DOUBLE PRECISION NOT NULL, summa DOUBLE PRECISION NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_7875F8326A93C67B ON invoice_act_detal (invoice_act_id);
ALTER TABLE invoice_act ADD CONSTRAINT FK_71D9A872989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE invoice_act_detal ADD CONSTRAINT FK_7875F8326A93C67B FOREIGN KEY (invoice_act_id) REFERENCES invoice_act (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
-- staging ----------------------
-- prod ----------------------
ALTER TABLE history ADD action VARCHAR(255) DEFAULT NULL;
ALTER TABLE history ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE history ADD params TEXT DEFAULT NULL;
ALTER TABLE email ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE organization ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE handling ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE dogovor ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;

ALTER TABLE companystructure ADD lft INT DEFAULT NULL;
ALTER TABLE companystructure ADD lvl INT DEFAULT NULL;
ALTER TABLE companystructure ADD rgt INT DEFAULT NULL;
ALTER TABLE companystructure ADD root INT DEFAULT NULL;
ALTER TABLE companystructure ALTER id TYPE BIGINT;
ALTER TABLE companystructure ALTER id DROP DEFAULT;

UPDATE "public".companystructure SET "lft" = 14, "rgt" = 15, "root" = 76, "lvl" = 1 WHERE id = 3;
UPDATE "public".companystructure SET "lft" = 4, "rgt" = 5, "root" = 76, "lvl" = 1 WHERE id = 4;
UPDATE "public".companystructure SET "lft" = 6, "rgt" = 7, "root" = 76, "lvl" = 1 WHERE id = 5;
UPDATE "public".companystructure SET "lft" = 20, "rgt" = 21, "root" = 76, "lvl" = 1 WHERE id = 6;
UPDATE "public".companystructure SET "lft" = 22, "rgt" = 23, "root" = 76, "lvl" = 1 WHERE id = 7;
UPDATE "public".companystructure SET "lft" = 1, "rgt" = 2, "root" = 75, "lvl" = 0 WHERE id = 8;
UPDATE "public".companystructure SET "lft" = 18, "rgt" = 19, "root" = 76, "lvl" = 1 WHERE id = 9;
UPDATE "public".companystructure SET "lft" = 1, "rgt" = 32, "root" = 76, "lvl" = 0 WHERE id = 12;
UPDATE "public".companystructure SET "lft" = 1, "rgt" = 14, "root" = 77, "lvl" = 0 WHERE id = 13;
UPDATE "public".companystructure SET "lft" = 1, "rgt" = 22, "root" = 78, "lvl" = 0 WHERE id = 14;
UPDATE "public".companystructure SET "lft" = 16, "rgt" = 19, "root" = 78, "lvl" = 1 WHERE id = 15;
UPDATE "public".companystructure SET "lft" = 8, "rgt" = 9, "root" = 78, "lvl" = 1 WHERE id = 16;
UPDATE "public".companystructure SET "lft" = 20, "rgt" = 21, "root" = 78, "lvl" = 1 WHERE id = 17;
UPDATE "public".companystructure SET "lft" = 6, "rgt" = 7, "root" = 78, "lvl" = 1 WHERE id = 18;
UPDATE "public".companystructure SET "lft" = 2, "rgt" = 3, "root" = 78, "lvl" = 1 WHERE id = 19;
UPDATE "public".companystructure SET "lft" = 4, "rgt" = 5, "root" = 78, "lvl" = 1 WHERE id = 20;
UPDATE "public".companystructure SET "lft" = 14, "rgt" = 15, "root" = 78, "lvl" = 1 WHERE id = 22;
UPDATE "public".companystructure SET "lft" = 30, "rgt" = 31, "root" = 76, "lvl" = 1 WHERE id = 23;
UPDATE "public".companystructure SET "lft" = 28, "rgt" = 29, "root" = 76, "lvl" = 1 WHERE id = 24;
UPDATE "public".companystructure SET "lft" = 26, "rgt" = 27, "root" = 76, "lvl" = 1 WHERE id = 25;
UPDATE "public".companystructure SET "lft" = 24, "rgt" = 25, "root" = 76, "lvl" = 1 WHERE id = 26;
UPDATE "public".companystructure SET "lft" = 16, "rgt" = 17, "root" = 76, "lvl" = 1 WHERE id = 28;
UPDATE "public".companystructure SET "lft" = 1, "rgt" = 2, "root" = 90, "lvl" = 0 WHERE id = 29;
UPDATE "public".companystructure SET "lft" = 12, "rgt" = 13, "root" = 78, "lvl" = 1 WHERE id = 30;
UPDATE "public".companystructure SET "lft" = 10, "rgt" = 11, "root" = 78, "lvl" = 1 WHERE id = 31;
UPDATE "public".companystructure SET "lft" = 17, "rgt" = 18, "root" = 78, "lvl" = 2 WHERE id = 32;
UPDATE "public".companystructure SET "lft" = 12, "rgt" = 13, "root" = 76, "lvl" = 1 WHERE id = 33;
UPDATE "public".companystructure SET "lft" = 10, "rgt" = 11, "root" = 76, "lvl" = 1 WHERE id = 34;
UPDATE "public".companystructure SET "lft" = 8, "rgt" = 9, "root" = 76, "lvl" = 1 WHERE id = 35;
UPDATE "public".companystructure SET "lft" = 2, "rgt" = 3, "root" = 76, "lvl" = 1 WHERE id = 36;
UPDATE "public".companystructure SET "lft" = 12, "rgt" = 13, "root" = 77, "lvl" = 1 WHERE id = 37;
UPDATE "public".companystructure SET "lft" = 10, "rgt" = 11, "root" = 77, "lvl" = 1 WHERE id = 38;
UPDATE "public".companystructure SET "lft" = 8, "rgt" = 9, "root" = 77, "lvl" = 1 WHERE id = 39;
UPDATE "public".companystructure SET "lft" = 6, "rgt" = 7, "root" = 77, "lvl" = 1 WHERE id = 40;
UPDATE "public".companystructure SET "lft" = 4, "rgt" = 5, "root" = 77, "lvl" = 1 WHERE id = 41;
UPDATE "public".companystructure SET "lft" = 2, "rgt" = 3, "root" = 77, "lvl" = 1 WHERE id = 42;

ALTER TABLE companystructure ALTER lft DROP DEFAULT;
ALTER TABLE companystructure ALTER lvl DROP DEFAULT;
ALTER TABLE companystructure ALTER rgt DROP DEFAULT;

-- staging ++++++++++
-- prod ++++++++++

CREATE TABLE organization_ownership (id BIGSERIAL NOT NULL, shortname VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
ALTER TABLE organization ADD ownership_id BIGINT DEFAULT NULL;
ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C9E9FFAA0 FOREIGN KEY (ownership_id) REFERENCES organization_ownership (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE UNIQUE INDEX UNIQ_C1EE637C9E9FFAA0 ON organization (ownership_id);
COMMENT ON COLUMN organization.ownership_id IS 'Форма собственности организации';
-- staging +++++++
-- prod ++++++++++++++++++++++++
ALTER TABLE invoice ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE;
-- staging ++++++++++++++++++++
-- prod  ++++++++++++++++++++++
CREATE TABLE comment (id SERIAL NOT NULL, create_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
value VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, modelId INT NOT NULL, addition_field VARCHAR(255)
, PRIMARY KEY(id));

ALTER TABLE comment ADD user_id INT DEFAULT NULL;
ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id);
-- staging +++++
-- prod +++++
DROP INDEX uniq_c1ee637c9e9ffaa0;
CREATE INDEX IDX_C1EE637C9E9FFAA0 ON organization (ownership_id);
-- staging ++++
-- prod ++++
CREATE TABLE organization_organizationsign (organization_id BIGINT NOT NULL, organization_sign_id INT NOT NULL, PRIMARY KEY(organization_id, organization_sign_id));
CREATE INDEX IDX_2E73694C32C8A3DE ON organization_organizationsign (organization_id);
CREATE INDEX IDX_2E73694CCB2EF456 ON organization_organizationsign (organization_sign_id);
ALTER TABLE organization_organizationsign ADD CONSTRAINT FK_2E73694C32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_organizationsign ADD CONSTRAINT FK_2E73694CCB2EF456 FOREIGN KEY (organization_sign_id) REFERENCES lookup (id) NOT DEFERRABLE INITIALLY IMMEDIATE;


INSERT INTO "public".organization_organizationsign (organization_id, organization_sign_id)
SELECT id, organization_sign_id FROM "public".organization WHERE "public".organization.organization_sign_id is  not NULL;
-- staging +++++
-- prod +++++
ALTER TABLE invoice ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
-- staging +++++
-- prod +++++
CREATE TABLE task_file (id SERIAL NOT NULL, user_id INT DEFAULT NULL, task_id INT DEFAULT NULL, createdatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) DEFAULT NULL, filepath VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_FF2CA26BA76ED395 ON task_file (user_id);
CREATE INDEX IDX_FF2CA26B8DB60186 ON task_file (task_id);
ALTER TABLE task_file ADD CONSTRAINT FK_FF2CA26BA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE task_file ADD CONSTRAINT FK_FF2CA26B8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
-- staging ++++++
-- prod +++++

UPDATE "public".companystructure SET "root" = 1 WHERE id = 14;
UPDATE "public".companystructure SET "root" = 1 WHERE id = 15;
UPDATE "public".companystructure SET "root" = 1 WHERE id = 31;
UPDATE "public".companystructure SET "lft" = 0, "lvl" = 1, "rgt" = 0, "root" = 1 WHERE id = 43;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 13;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 22;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 39;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 40;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 42;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 41;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 37;
UPDATE "public".companystructure SET "lft" = 0, "rgt" = 0, "root" = 2 WHERE id = 44;
UPDATE "public".companystructure SET "lft" = 0, "rgt" = 0, "root" = 3 WHERE id = 45;
UPDATE "public".companystructure SET "root" = 2 WHERE id = 38;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 12;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 35;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 34;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 30;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 33;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 3;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 5;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 4;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 23;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 25;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 24;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 26;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 6;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 28;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 7;
UPDATE "public".companystructure SET "root" = 5 WHERE id = 8;
UPDATE "public".companystructure SET "root" = 4 WHERE id = 29;
UPDATE "public".companystructure SET "root" = 3 WHERE id = 9;
UPDATE "public".companystructure SET "parent_id" = 3, "lvl" = 2, "root" = 3 WHERE id = 32;
INSERT INTO "public".companystructure (id, parent_id, "name", mpk, address, phone, stuff_id, lft, lvl, rgt, root) 
	VALUES (46, 3, 'Киев', 'к-', NULL, NULL, NULL, 0, 2, 0, 3)
INSERT INTO "public".companystructure (id, parent_id, "name", mpk, address, phone, stuff_id, lft, lvl, rgt, root) 
	VALUES (48, 3, 'Технический отдел', 'т-', NULL, NULL, NULL, 0, 2, 0, 3)
INSERT INTO "public".companystructure (id, parent_id, "name", mpk, address, phone, stuff_id, lft, lvl, rgt, root) 
	VALUES (47, 3, 'Прилуки', 'п-', NULL, NULL, NULL, 0, 2, 0, 3)
INSERT INTO "public".companystructure (id, parent_id, "name", mpk, address, phone, stuff_id, lft, lvl, rgt, root) 
	VALUES (49, 5, 'Львов', 'л-', NULL, NULL, NULL, 0, 2, 0, 3)

-- staging +++++++
-- prod +++++++++
ALTER TABLE fos_user ADD peer_id INT DEFAULT NULL;
ALTER TABLE fos_user ADD peer_password VARCHAR(255) DEFAULT NULL;
-- staging ++++++++++
-- prod +++++++++
CREATE TABLE call (
    id SERIAL NOT NULL,
    caller_id INT NOT NULL,
    receiver_id INT  DEFAULT NULL,
    peer_id INT DEFAULT NULL,
    phone VARCHAR(12) DEFAULT NULL,
    proxy_id VARCHAR(255) DEFAULT NULL,
    unique_id VARCHAR(255) NOT NULL,
    destunique_id VARCHAR(255) DEFAULT NULL,
    datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    duration INT DEFAULT NULL,
    file_name VARCHAR(255) DEFAULT NULL,
    status VARCHAR(255) DEFAULT NULL,
    model_name VARCHAR(255) NOT NULL,
    model_id INT NOT NULL,
    PRIMARY KEY(id));
CREATE INDEX IDX_CC8E2F3EA5626C52 ON call (caller_id);
ALTER TABLE call ADD CONSTRAINT FK_CC8E2F3EA5626C52 FOREIGN KEY (caller_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

-- staging +++++
-- prod ++++

INSERT INTO role (name, model) VALUES ('matcher', 'task');
INSERT INTO stage (name, model) VALUES ('matching', 'task');
CREATE TABLE task_commit (id SERIAL NOT NULL, stage_id INT DEFAULT NULL, task_user_role_id INT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_757DAA7E2298D193 ON task_commit (stage_id);
CREATE INDEX IDX_757DAA7E469F188B ON task_commit (task_user_role_id);
ALTER TABLE task_commit ADD CONSTRAINT FK_757DAA7E2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE task_commit ADD CONSTRAINT FK_757DAA7E469F188B FOREIGN KEY (task_user_role_id) REFERENCES task_user_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

INSERT INTO stage (name, model) VALUES ('sign_up', 'task_commit');
INSERT INTO stage (name, model) VALUES ('refused_sign_up', 'task_commit');
ALTER TABLE comment ALTER COLUMN value TYPE text;
-- staging +++++++
-- prod +++++++
CREATE TABLE holiday (id SERIAL NOT NULL, date DATE NOT NULL, name VARCHAR(128) NOT NULL, short_description VARCHAR(512) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id));
-- staging ++++++
-- prod++++
ALTER TABLE history ALTER COLUMN value TYPE text;
ALTER TABLE history ALTER COLUMN old_value TYPE text;
INSERT INTO "public".email ("alias", subject, text, deletedat) 
	VALUES ('empty-template', '${tema}$', '<table> <tbody><tr>  <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"><div><span style="line-height: 14.300000190734863px;">${text}$</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div><div><span style="line-height: 14.300000190734863px;"> Удачного дня.</span></div><div><span style="line-height: 14.300000190734863px;"><br></span></div>                                                                                                                      </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer; cursor;     color: #fff; " pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table></td></tr></tbody></table>', NULL);
-- staging ++++++
-- prod +++++

INSERT INTO role (name, model) VALUES ('viewer', 'task');
-- staging ++++
-- prod ++++

CREATE TABLE task_pattern (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id));

CREATE TABLE pattern_user_role (id SERIAL NOT NULL, task_pattern_id INT DEFAULT NULL, role_id INT DEFAULT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_1F606499EB27E9A0 ON pattern_user_role (task_pattern_id);
CREATE INDEX IDX_1F606499D60322AC ON pattern_user_role (role_id);
CREATE INDEX IDX_1F606499A76ED395 ON pattern_user_role (user_id);

ALTER TABLE pattern_user_role ADD CONSTRAINT FK_1F606499EB27E9A0 FOREIGN KEY (task_pattern_id) REFERENCES task_pattern (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pattern_user_role ADD CONSTRAINT FK_1F606499D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pattern_user_role ADD CONSTRAINT FK_1F606499A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;CREATE INDEX IDX_1F606499EB27E9A0 ON pattern_user_role (task_pattern_id);
-- staging ++++++
-- prod ++++++
ALTER TABLE invoice ADD status INT DEFAULT NULL;
-- prod ++++++
ALTER TABLE invoice ADD debit_sum DOUBLE PRECISION NOT NULL DEFAULT 0;
-- prod ++++++
INSERT INTO "public".lookup (lukey, "name", "group") 
	VALUES ('fired', 'Уволен', 'user_status');
INSERT INTO "public".lookup (lukey, "name", "group") 
	VALUES ('worked', 'Работает', 'user_status');
INSERT INTO "public".lookup (lukey, "name", "group") 
	VALUES ('decree', 'В декрете', 'user_status');
-- prod ++++++
ALTER TABLE stuff ADD status_id INT DEFAULT NULL;
ALTER TABLE stuff ADD CONSTRAINT FK_5941F83E6BF700BD FOREIGN KEY (status_id) REFERENCES lookup (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_5941F83E6BF700BD ON stuff (status_id);
COMMENT ON COLUMN stuff.status_id IS 'lookup_id';

UPDATE stuff SET status_id = 67 FROM fos_user fu WHERE fu.id = stuff.user_id AND fu.is_fired  =  true;
UPDATE stuff SET status_id = 68 FROM fos_user fu WHERE fu.id = stuff.user_id AND fu.is_fired  =  false;

ALTER table fos_user DROP COLUMN is_fired;

UPDATE fos_user SET locked = is_blocked  WHERE fos_user.id = fos_user.id AND is_blocked is not NULL;
ALTER table fos_user DROP COLUMN is_blocked;
-- prod ++++++

INSERT INTO "public".email ("alias", subject, text, deletedat) 
	VALUES ('add-manager-project', 'Вас назначили менеджером проекта № ${id}$', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вас пригласили в проект № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ назначил(a) Вас в менеджером проекта с долей участия ${part}$. <br><br>Ознакомится с проектом вы можете по ссылке:    </p>                                                                         <table style="margin-bottom: 10px;width: 580px">                                         <tbody><tr>                                             <td class="panel" style="padding: 10px !important; background: #ECF8FF; border: 0;"><span style="color: rgb(22, 52, 59); font-family: Arial; font-size: 12px; line-height: 15px;">${url}$</span><br></td>                                             <td class="expander">                                             </td>                                         </tr>                                     </tbody></table>                                     <p>                                        Если переход по ссылке не работает, скопируйте и вставьте ссылку в адресную строку браузера.                                     </p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>', NULL);
INSERT INTO "public".email ("alias", subject, text, deletedat) 
	VALUES ('remove-manager-project', 'Вы больше не являетесь менеджером проекта № ${id}$', '<table> <tbody><tr>   <td align="center" valign="top">    <table align="center" style="width: 100%;background: #1f1f1f;">     <tbody><tr>      <td style="text-align: center;" align="center">       <table style="width: 585px;margin: 0 auto;text-align: inherit;" align="center">        <tbody><tr>         <td>           <table style="display: block;padding: 0px;width: 100%;position: relative;vertical-align: top;text-align: left; ">           <tbody><tr>            <td style="padding: 10px 20px 0px 0px; position: relative;;vertical-align: middle;">              <table style="width: 280px;">               <tbody><tr>                <td style="padding: 0px 0px 10px;vertical-align: middle;"> <a href="http://www.griffin.ua" style="background-image: url(http://sd.griffin.ua/templates/core/img/logo-small-gray.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 104px;cursor: pointer;height: 14px;"> </a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                              </td>                                             <td style="padding-right: 0px;padding: 10px 20px 0px 0px; position: relative;vertical-align: middle;">                                                 <table style="width: 280px;">                                                     <tbody><tr>                                                         <td>                                                             <table align="right" style="float: right;vertical-align: top; text-align: left;">                                                                 <tbody><tr>                                                                     <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                   <a href="//www.facebook.com/pages/%D0%98%D0%BC%D0%BF%D0%B5%D0%BB-%D0%93%D1%80%D0%B8%D1%84%D1%84%D0%B8%D0%BD/191430010917393" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/facebook.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                  </a> </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                    <a href="https://twitter.com/ImpelGriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/twit.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                              </a>                                                      </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;"> <a href="https://plus.google.com/+GriffinUa/posts" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/google.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                 </a>                                                         </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                               <a href="http://www.linkedin.com/company/impel-griffin?trk=company_name" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/in.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                </a>                                                                </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                                 <a href="http://vk.com/impelgriffin" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/vk.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                  </a>                                                            </td>                                                                     <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;padding: 0 2px !important;width: auto !important;">                                                              <a href="https://www.youtube.com/user/impelgriffin/" style="background-image: url(http://sd.griffin.ua/templates/core/img/socialmedia/youtube.png);outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;height: auto;max-width: none !important;float: left;clear: both;display: block;width: 30px;height: 30px;background-repeat: no-repeat;cursor: pointer;">                                                                                                                                   </a>                                                             </td>                                                          </tr>                                                             </tbody></table>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>             <div style="text-align: left;"><br></div><br><table align="center" style="width: 580px; margin: 0 auto; text-align: inherit;">                 <tbody><tr>                     <td style="background: #fff; padding: 15px !important;">                         <table style="padding: 0px; width: 100%; position: relative;display: block;">                             <tbody><tr>                                 <td style="padding: 10px 20px 0px 0px; position: relative;"> <h4 style="display: block; margin: 5px 0 15px 0;">Вы больше не являетесь менеджером проекта № ${id}$</h4>     <p>      ${lastName}$ ${firstName}$ ${middleName}$ назначил(a) нового  менеджера проекта.</p>                                 </td>                             </tr>                         </tbody></table>                         <span style="border-bottom: 1px solid #eee; margin: 15px -15px; display: block;">                         </span>                                          </td>                 </tr>             </tbody></table>             <table align="center" style="width: 100%;background: #2f2f2f;">                 <tbody><tr>                     <td align="center" style="vertical-align: middle;color: #fff;text-align: center;">                         <table style="width: 590px;margin: 0 auto;text-align: inherit;" align="center">                             <tbody><tr>                                 <td>                                     <table style="display: block;padding: 0px; width: 100%; position: relative;vertical-align: top; text-align: left;">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;padding: 10px 20px 0px 0px;text-align: left;"><span style="font-size:12px;"><i>Это письмо автоматически создано системой ImpelNET. Отвечать на него нет необходимости.</i></span>                                             </td>                                         </tr>                                     </tbody></table>                                     <table style="     display: block; ">                                         <tbody><tr>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table class="four columns">                                                     <tbody><tr>                                                         <td style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">  (c) Impel Griffin Group 1995-2014   </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                             <td style="vertical-align: middle;color: #fff;">                                                 <table style="width: 340px;">                                                     <tbody><tr>                                                         <td class="vertical-middle align-reverse" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;text-align: right;color: #fff;"> <a href="http://www.griffin.ua" style="     cursor: pointer;     color: #fff; cursor" pointer"="">                                                                 О нас</a>                                                         </td>                                                     </tr>                                                 </tbody></table>                                             </td>                                         </tr>                                     </tbody></table>                                 </td>                             </tr>                         </tbody></table>                     </td>                 </tr>             </tbody></table>         </td>     </tr> </tbody></table>', NULL);
-- prod +++++++

CREATE TABLE oper_organizer (id BIGSERIAL NOT NULL, user_id INT DEFAULT NULL, department_id BIGINT DEFAULT NULL, start_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
ALTER TABLE oper_organizer ADD CONSTRAINT FK_907B76AEA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE oper_organizer ADD CONSTRAINT FK_907B76AEAE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE TABLE comment_organizer (id SERIAL NOT NULL, user_id INT DEFAULT NULL, create_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value TEXT NOT NULL, addition_field VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_35308269A76ED395 ON comment_organizer (user_id);

ALTER TABLE comment_organizer ADD operorganizer_id BIGINT DEFAULT NULL;
ALTER TABLE comment_organizer ADD CONSTRAINT FK_35308269A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE comment_organizer ADD CONSTRAINT FK_353082697323D87A FOREIGN KEY (operorganizer_id) REFERENCES oper_organizer (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_353082697323D87A ON comment_organizer (operorganizer_id);

-- prod ++++

ALTER TABLE stuff_departments ADD id BIGSERIAL NOT NULL;
ALTER TABLE stuff_departments DROP CONSTRAINT stuff_departments_pkey;
ALTER TABLE stuff_departments ADD PRIMARY KEY (id);
CREATE UNIQUE INDEX stuff_departments_unique ON stuff_departments (departments_id, stuff_id, claimtype_id, userkey);
-- prod +++++++
ALTER TABLE departments ALTER city_id TYPE INT;
ALTER TABLE departments ALTER departments_type_id TYPE BIGINT;
ALTER TABLE departments ALTER organization_id TYPE INT;
ALTER TABLE departments ALTER organization_id SET NOT NULL;
ALTER TABLE departments ALTER status_id TYPE BIGINT;
ALTER TABLE departments ALTER isdeleted SET  DEFAULT false;
ALTER TABLE departments ALTER isdeleted SET NOT NULL;
ALTER TABLE departments ADD CONSTRAINT FK_16AEB8D43C0D92A3 FOREIGN KEY (opermanager_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE departments ALTER city_id SET NOT NULL;
-- prod ---------
CREATE SEQUENCE news_fos_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE news_role_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE news_fos_user (id INT NOT NULL, news_id INT DEFAULT NULL, user_id INT DEFAULT NULL, viewed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id));
CREATE TABLE news_role (id INT NOT NULL, news_id INT DEFAULT NULL, role_id INT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_DC44C728B5A459A0 ON news_fos_user (news_id);
CREATE INDEX IDX_DC44C728D60322AC ON news_fos_user (user_id);
CREATE INDEX IDX_82B47CE1B5A459A0 ON news_role (news_id);
CREATE INDEX IDX_82B47CE1D60322AC ON news_role (role_id);
ALTER TABLE news_fos_user ADD CONSTRAINT FK_DC44C728B5A459A0 FOREIGN KEY (news_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE news_fos_user ADD CONSTRAINT FK_DC44C728D60322AC FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE news_role ADD CONSTRAINT FK_82B47CE1B5A459A0 FOREIGN KEY (news_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE news_role ADD CONSTRAINT FK_82B47CE1D60322AC FOREIGN KEY (role_id) REFERENCES fos_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
-- prod +++++++++
ALTER TABLE mpk ALTER self_organization_id TYPE BIGINT;
ALTER TABLE mpk ALTER name SET NOT NULL;
-- prod ++++++
ALTER TABLE mpk DROP CONSTRAINT mpk_name_key;
CREATE UNIQUE INDEX mpk_unique_idx ON mpk (name, self_organization_id);
ALTER TABLE departments ALTER name DROP NOT NULL;
-- prod ++++++++
UPDATE "public".lookup SET "lukey" = 'one-off' WHERE id = 13;
UPDATE "public".lookup SET "lukey" = 'complex' WHERE id = 14;
UPDATE "public".lookup SET "lukey" = 'with_contractors' WHERE id = 15;
UPDATE "public".lookup SET "lukey" = 'internal' WHERE id = 16;
UPDATE "public".lookup SET "lukey" = 'rent' WHERE id = 17;
UPDATE "public".lookup SET "lukey" = 'disinfection' WHERE id = 24;
UPDATE "public".lookup SET "lukey" = 'supply' WHERE id = 71;
UPDATE "public".lookup SET "lukey" = 'privacy' WHERE id = 72;
UPDATE "public".lookup SET "lukey" = 'loan' WHERE id = 73;
UPDATE "public".lookup SET "lukey" = 'rent_legal_address' WHERE id = 74;
UPDATE "public".lookup SET "lukey" = 'domestic_services' WHERE id = 75;
-- prod ++++++
ALTER TABLE invoice_payments ALTER bank SET NOT NULL;
ALTER TABLE invoice ADD guid VARCHAR(512) DEFAULT NULL;
CREATE UNIQUE INDEX UNIQ_906517442B6FCFB2 ON invoice (guid);
ALTER TABLE invoice ADD update_datetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
-- prod ++++

ALTER TABLE oper_organizer ADD isVisited BOOLEAN DEFAULT 'false' NOT NULL;
-- prod ++++

ALTER TABLE news_role ADD vote BOOLEAN DEFAULT NULL;
-- prod ++++++

CREATE SEQUENCE news_companystructure_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE news_companystructure (id SERIAL NOT NULL, news_id INT DEFAULT NULL, companystructure_id BIGINT DEFAULT NULL, vote BOOLEAN DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_DD0A01A9B5A459A0 ON news_companystructure (news_id);
CREATE INDEX IDX_DD0A01A939A87BEA ON news_companystructure (companystructure_id);
-- prod +++++

CREATE TABLE itd_js_error (id SERIAL NOT NULL, create_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, url TEXT NOT NULL, message TEXT DEFAULT NULL, extra TEXT DEFAULT NULL, PRIMARY KEY(id));
-- prod ++++++

CREATE TABLE oper_organizer_type (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
ALTER TABLE oper_organizer ADD type_id INT DEFAULT NULL;
ALTER TABLE oper_organizer ALTER isvisited SET  DEFAULT 'false';
ALTER TABLE oper_organizer ADD CONSTRAINT FK_907B76AEC54C8C93 FOREIGN KEY (type_id) REFERENCES oper_organizer_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_907B76AEC54C8C93 ON oper_organizer (type_id);

INSERT INTO oper_organizer_type (name) VALUES ('department'), ('once'), ('other')
UPDATE oper_organizer SET type_id = (SELECT id FROM oper_organizer_type WHERE name='department')

-- prod ++++++

CREATE TABLE session (
    session_id character varying(255) NOT NULL,
    session_value text NOT NULL,
    session_time integer NOT NULL,
    CONSTRAINT session_pkey PRIMARY KEY (session_id)
);
CREATE TABLE login_statistic (id SERIAL NOT NULL, user_id INT DEFAULT NULL, logedIn TIMESTAMP(0) WITHOUT TIME ZONE, logedOut TIMESTAMP(0) WITHOUT TIME ZONE, clientIp VARCHAR(25) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_4056C69A76ED395 ON login_statistic (user_id);
CREATE TABLE login_user_activity (id SERIAL NOT NULL, lastActivity TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
ALTER TABLE login_statistic ADD sessionid VARCHAR(255);
ALTER TABLE login_statistic ALTER logedin SET NOT NULL;
ALTER TABLE login_statistic ADD CONSTRAINT FK_4056C69A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE login_user_activity ADD user_id INT DEFAULT NULL;
ALTER TABLE login_user_activity ADD CONSTRAINT FK_6AEB0E35A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
-- prod ++++++++

ALTER TABLE login_statistic ADD cause VARCHAR(25);
-- prod +++++++

CREATE TABLE position (id BIGSERIAL NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));
ALTER TABLE fos_user ADD position_id BIGINT DEFAULT NULL;
ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479DD842E46 FOREIGN KEY (position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_957A6479DD842E46 ON fos_user (position_id);
-- prod +++++++

CREATE TABLE organization_current_account_type (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_43025E485E237E06 ON organization_current_account_type (name);
COMMENT ON COLUMN organization_current_account_type.name IS 'Название типа расчетного счета';
CREATE TABLE organization_current_account (id BIGSERIAL NOT NULL, type_id BIGINT DEFAULT NULL, organization_id BIGINT DEFAULT NULL, bank_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_85A323F4C54C8C93 ON organization_current_account (type_id);
CREATE INDEX IDX_85A323F432C8A3DE ON organization_current_account (organization_id);
CREATE INDEX IDX_85A323F411C8FB41 ON organization_current_account (bank_id);
COMMENT ON COLUMN organization_current_account.name IS 'Р/С';
CREATE TABLE Bank (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, mfo VARCHAR(255) NOT NULL, PRIMARY KEY(id));
COMMENT ON COLUMN Bank.name IS 'Название банка';
COMMENT ON COLUMN Bank.mfo IS 'МФО банка';
CREATE TABLE pay_master (id BIGSERIAL NOT NULL, payer_id BIGINT DEFAULT NULL, customer_id BIGINT DEFAULT NULL, contractor_id BIGINT DEFAULT NULL, dogovor_id BIGINT DEFAULT NULL, create_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, invoice_date DATE NOT NULL, expected_date DATE NOT NULL, payment_date DATE NOT NULL, invoice_amount DOUBLE PRECISION NOT NULL, vat BOOLEAN DEFAULT 'false' NOT NULL, description TEXT DEFAULT NULL, scan VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_A4926999C17AD9A9 ON pay_master (payer_id);
CREATE INDEX IDX_A49269999395C3F3 ON pay_master (customer_id);
CREATE INDEX IDX_A4926999B0265DC7 ON pay_master (contractor_id);
CREATE INDEX IDX_A492699981A36DD2 ON pay_master (dogovor_id);
COMMENT ON COLUMN pay_master.create_datetime IS 'Дата создания счета (создается автоматически)';
COMMENT ON COLUMN pay_master.invoice_date IS 'Дата счета';
COMMENT ON COLUMN pay_master.expected_date IS 'Ожидаемая дата оплаты';
COMMENT ON COLUMN pay_master.payment_date IS 'Дата оплаты';
COMMENT ON COLUMN pay_master.invoice_amount IS 'Сумма счета';
COMMENT ON COLUMN pay_master.vat IS 'Сумма счета с НДС (да||нет)';
COMMENT ON COLUMN pay_master.scan IS 'Скан счета (имя файла) (/uploaded/paymaster/{id})';
ALTER TABLE organization_current_account ADD CONSTRAINT FK_85A323F4C54C8C93 FOREIGN KEY (type_id) REFERENCES organization_current_account_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_current_account ADD CONSTRAINT FK_85A323F432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_current_account ADD CONSTRAINT FK_85A323F411C8FB41 FOREIGN KEY (bank_id) REFERENCES Bank (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master ADD CONSTRAINT FK_A4926999C17AD9A9 FOREIGN KEY (payer_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master ADD CONSTRAINT FK_A49269999395C3F3 FOREIGN KEY (customer_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master ADD CONSTRAINT FK_A4926999B0265DC7 FOREIGN KEY (contractor_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master ADD CONSTRAINT FK_A492699981A36DD2 FOREIGN KEY (dogovor_id) REFERENCES dogovor (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master ADD creator_id INT DEFAULT NULL;
ALTER TABLE pay_master ADD CONSTRAINT FK_A492699961220EA6 FOREIGN KEY (creator_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_A492699961220EA6 ON pay_master (creator_id);
ALTER TABLE pay_master ADD current_account_id BIGINT DEFAULT NULL;
ALTER TABLE pay_master ADD CONSTRAINT FK_A492699944D096C8 FOREIGN KEY (current_account_id) REFERENCES organization_current_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_A492699944D096C8 ON pay_master (current_account_id);
INSERT INTO "public".organization_current_account_type ("name") VALUES ('Главный');
INSERT INTO "public".organization_current_account_type ("name") VALUES ('Дополнительный');
CREATE TABLE pay_master_mpk (pay_master_id BIGINT NOT NULL, mpk_id BIGINT NOT NULL, PRIMARY KEY(pay_master_id, mpk_id));
CREATE INDEX IDX_12F888553EBD646D ON pay_master_mpk (pay_master_id);
CREATE INDEX IDX_12F88855895D31C8 ON pay_master_mpk (mpk_id);
ALTER TABLE pay_master ALTER payment_date DROP NOT NULL;
ALTER TABLE pay_master_mpk ADD CONSTRAINT FK_12F888553EBD646D FOREIGN KEY (pay_master_id) REFERENCES pay_master (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master_mpk ADD CONSTRAINT FK_12F88855895D31C8 FOREIGN KEY (mpk_id) REFERENCES mpk (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master ADD is_rejected BOOLEAN DEFAULT NULL;
COMMENT ON COLUMN pay_master.is_rejected IS 'Статус счета (NULL (новый)|0 (принят)|1 (отклонен))';
ALTER TABLE pay_master ADD reason TEXT DEFAULT NULL;

-- prod ++++

CREATE TABLE coach_action (id SERIAL NOT NULL, user_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, type_id INT DEFAULT NULL, department_id BIGINT DEFAULT NULL, text TEXT DEFAULT NULL, startedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, finishedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_9C63C727A76ED395 ON coach_action (user_id);
CREATE INDEX IDX_9C63C7271F55203D ON coach_action (topic_id);
CREATE INDEX IDX_9C63C727C54C8C93 ON coach_action (type_id);
CREATE INDEX IDX_9C63C727AE80F5DF ON coach_action (department_id);
CREATE TABLE coach_action_individual (coach_action_id INT NOT NULL, individual_id BIGINT NOT NULL, PRIMARY KEY(coach_action_id, individual_id));
CREATE INDEX IDX_CCDD9F3CB43E4A2 ON coach_action_individual (coach_action_id);
CREATE INDEX IDX_CCDD9F3CAE271C0D ON coach_action_individual (individual_id);
CREATE TABLE coach_action_topic (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE coach_action_type (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE coach_report (id SERIAL NOT NULL, action_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text TEXT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_1F803C319D32F035 ON coach_report (action_id);
CREATE INDEX IDX_1F803C31A76ED395 ON coach_report (user_id);

ALTER TABLE coach_action ADD CONSTRAINT FK_9C63C727A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_action ADD CONSTRAINT FK_9C63C7271F55203D FOREIGN KEY (topic_id) REFERENCES coach_action_topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_action ADD CONSTRAINT FK_9C63C727C54C8C93 FOREIGN KEY (type_id) REFERENCES coach_action_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_action ADD CONSTRAINT FK_9C63C727AE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_action_individual ADD CONSTRAINT FK_CCDD9F3CB43E4A2 FOREIGN KEY (coach_action_id) REFERENCES coach_action (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_action_individual ADD CONSTRAINT FK_CCDD9F3CAE271C0D FOREIGN KEY (individual_id) REFERENCES individual (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_report ADD CONSTRAINT FK_1F803C319D32F035 FOREIGN KEY (action_id) REFERENCES coach_action (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coach_report ADD CONSTRAINT FK_1F803C31A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE TABLE coach_region (id SERIAL NOT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_D4CDBAC3A76ED395 ON coach_region (user_id);
CREATE TABLE coaches_regions (coach_id INT NOT NULL, region_id INT NOT NULL, PRIMARY KEY(coach_id, region_id));
CREATE INDEX IDX_758AAA3F3C105691 ON coaches_regions (coach_id);
CREATE UNIQUE INDEX UNIQ_758AAA3F98260155 ON coaches_regions (region_id);

ALTER TABLE coach_region ADD CONSTRAINT FK_D4CDBAC3A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coaches_regions ADD CONSTRAINT FK_758AAA3F3C105691 FOREIGN KEY (coach_id) REFERENCES coach_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE coaches_regions ADD CONSTRAINT FK_758AAA3F98260155 FOREIGN KEY (region_id) REFERENCES Region (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
-- prod ++++++++
CREATE TABLE handling_message_file (id BIGSERIAL NOT NULL, handling_message_id BIGINT DEFAULT NULL, createdate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, file VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_6A91B1E8B924C345 ON handling_message_file (handling_message_id);
COMMENT ON COLUMN handling_message_file.createdate IS 'Дата создания';
COMMENT ON COLUMN handling_message_file.file IS 'Название файла';
-- prod ++++++++
INSERT INTO "public".handling_message_file (handling_message_id, createdate, file)
    (SELECT handling_message.id, handling_message.createdatetime, handling_message.filepath
        FROM "public".handling_message
        LEFT JOIN handling_message_file as hmf on handling_message.id = hmf.handling_message_id
        WHERE  hmf."file" is null
        AND handling_message.filepath is not null
    );
-- prod ++++++
ALTER TABLE dogovor ADD delay_type_id BIGINT DEFAULT NULL;
ALTER TABLE dogovor ADD delay INT DEFAULT 0 NOT NULL;
ALTER TABLE dogovor ADD delayComment INT DEFAULT NULL;
COMMENT ON COLUMN dogovor.delay IS 'Отстрочка (количество дней)';
COMMENT ON COLUMN dogovor.delayComment IS 'Комментарий к отстрочке';
COMMENT ON COLUMN dogovor.subject IS 'Тема договора';
COMMENT ON COLUMN dogovor.maturity IS 'Отстрочка (старое поле)';
ALTER TABLE handling_message ALTER createdate SET NOT NULL;
COMMENT ON COLUMN handling_message.handling_id IS 'ID проекта';
COMMENT ON COLUMN handling_message.user_id IS 'ID пользователя (который создал)';
COMMENT ON COLUMN handling_message.createdatetime IS 'Дата создания (создается автоматически)';
COMMENT ON COLUMN handling_message.description IS 'Описание';
COMMENT ON COLUMN handling_message.createdate IS 'Дата создания (указывается пользователем)';
COMMENT ON COLUMN handling_message.filepath IS 'Это старое поле (нужно будет удалить после пересохранения звонков)';
COMMENT ON COLUMN handling_message.filename IS 'Название документа';
ALTER TABLE handling_message_file ADD CONSTRAINT FK_6A91B1E8B924C345 FOREIGN KEY (handling_message_id) REFERENCES handling_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE TABLE delay_type (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, PRIMARY KEY(id));                           
COMMENT ON COLUMN delay_type.name IS 'Тип дня отстрочки';                                                                                                 
COMMENT ON COLUMN delay_type.short_name IS 'Сокращеное название';
INSERT INTO "public".delay_type ("name", short_name) VALUES ('Банковские', 'Б');
INSERT INTO "public".delay_type ("name", short_name) VALUES ('Календарные', 'К');
ALTER TABLE dogovor ALTER delay SET  DEFAULT NULL;
ALTER TABLE dogovor ALTER delay DROP NOT NULL;
ALTER TABLE dogovor ADD delay_comment VARCHAR(255) DEFAULT NULL;
ALTER TABLE dogovor DROP delaycomment;

UPDATE dogovor SET delay_comment = maturity;
UPDATE dogovor SET payment_deferment = ( SELECT invoice.delay_days FROM  invoice WHERE invoice.dogovor_id = dogovor.id LIMIT 1) WHERE payment_deferment is NULL;
UPDATE dogovor SET delay_type_id = (SELECT case when invoice.delay_days_type = 'Б' then 1  when invoice.delay_days_type = 'К' then 2 end FROM  invoice WHERE invoice.dogovor_id = dogovor.id and invoice.delay_days_type is not NULL LIMIT 1);
ALTER TABLE dogovor DROP delay;
CREATE UNIQUE INDEX unique_organization_current_account_idx ON organization_current_account (name, organization_id, bank_id);
-- prod ++++++
ALTER TABLE pay_master DROP customer_id;
ALTER TABLE pay_master DROP CONSTRAINT fk_a49269999395c3f3;
DROP INDEX idx_a49269999395c3f3;
CREATE TABLE pay_master_customer (pay_master_id BIGINT NOT NULL, customer_id BIGINT NOT NULL, PRIMARY KEY(pay_master_id, customer_id));
CREATE INDEX IDX_FCB1B0713EBD646D ON pay_master_customer (pay_master_id);
CREATE INDEX IDX_FCB1B0719395C3F3 ON pay_master_customer (customer_id);

COMMENT ON COLUMN dogovor.payment_deferment IS 'Отстрочка (количество дней)';
COMMENT ON COLUMN dogovor.planned_pf1 IS 'Плановый ПФ1';
COMMENT ON COLUMN dogovor.planned_pf1_percent IS 'Плановый ПФ1, %';
COMMENT ON COLUMN dogovor.delay_comment IS 'Комментарий к отстрочке';
-- prod ++++
ALTER TABLE pay_master_customer ADD CONSTRAINT FK_FCB1B0713EBD646D FOREIGN KEY (pay_master_id) REFERENCES pay_master (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE pay_master_customer ADD CONSTRAINT FK_FCB1B0719395C3F3 FOREIGN KEY (customer_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE TABLE pay_master_status (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
COMMENT ON COLUMN pay_master_status.name IS 'Название статуса платежа';
INSERT INTO "public".pay_master_status ("name") 
	VALUES ('Срочно');
INSERT INTO "public".pay_master_status ("name") 
	VALUES ('По возможности');
ALTER TABLE pay_master ADD status_id BIGINT DEFAULT NULL;
ALTER TABLE pay_master ADD CONSTRAINT FK_A49269996BF700BD FOREIGN KEY (status_id) REFERENCES pay_master_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_A49269996BF700BD ON pay_master (status_id);
ALTER TABLE pay_master ADD is_acceptance BOOLEAN DEFAULT NULL;
COMMENT ON COLUMN pay_master.is_acceptance IS 'Акцепт (1 - подтвержен, 0 - отклонен)';
ALTER TABLE pay_master DROP is_rejected;
ALTER TABLE pay_master ADD to_pay BOOLEAN DEFAULT NULL;
COMMENT ON COLUMN pay_master.to_pay IS 'Отметка на оплату';
-- prod +++++
ALTER TABLE bank ADD guid VARCHAR(255) NOT NULL;
COMMENT ON COLUMN bank.guid IS 'guid из 1С';
CREATE TABLE bank_cron (id SERIAL NOT NULL, bank_id BIGINT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(64) NOT NULL, description TEXT NOT NULL, reason VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_BDAD527611C8FB41 ON bank_cron (bank_id);
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ ВАТ АСТРА БАНК, м. Київ', '380548', '82680965-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БТА БАНК", м. Київ', '321723', '82680967-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БМ БАНК", м. Київ', '380913', '82680969-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Київ', '320564', '82680971-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІРЕУС БАНК МКБ", м. Київ', '300658', '82680975-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕНКОМБАНК", м. Київ', '320876', '82680977-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СВЕДБАНК", м. Київ', '300164', '8268097f-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКРСИББАНК", м. Харків', '351005', '82680981-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНДУСТРІАЛБАНК", м. Дніпропетровськ', '307189', '37247006-7a4b-11e1-8e27-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "КИЇВСЬКА РУСЬ", м. Київ', '319092', '6b45e334-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Полтава', '331401', '6b45e33b-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УНІВЕРСАЛ БАНК", м. Київ', '322001', 'a40258f0-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Одеса', '328209', 'd2c55832-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКБ БАЗИС", м. Харків', '351599', 'e0145878-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Київ', '320304', 'e0145892-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "ЧОРН.БАНК РОЗВ.ТА РЕК", м. Сімферополь', '384577', 'eabe3d20-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРОКРЕДИТ БАНК", м. Київ', '320984', 'f8c734cb-a329-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Iвано-Франківськ', '336677', '01e08e4b-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДИТПРОМБАНК", м. Київ', '300863', '0c09b3eb-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ПЕТРОКОММЕРЦ-УКРАЇНА", м. Київ', '300120', '0c09b402-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДОНЕЦЬКІЙ ОБЛАСТІ, м. Донецьк', '834016', '17adef5e-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АЛЬФА-БАНК", м. Київ', '300346', '17adef67-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ФОРУМ", м. Київ', '322948', '17adef80-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Київ', '321842', '23332c42-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРОФІН БАНК", м. Донецьк', '334594', '350da839-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Київ', '380805', '8268097b-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЦРУ Фінанси та кредит", м. Київ', '300937', '4ad64492-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РОЗРАХУНКОВИЙ ЦЕНТР", м. Київ', '320649', '82680979-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СБЕРБАНК РОСІЇ", м. Київ', '320627', '8268097d-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ ВТБ БАНК, м. Київ', '321767', '8268096b-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УКРСОЦБАНК", м. Одеса', '328016', '4ad6449b-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК КРЕДИТ ДНІПРО", м. Дніпропетровськ', '305749', '583aba01-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Донецьк', '335496', '583aba17-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКРЕКСІМБАНК", м. Київ', '322313', '663c8ab6-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Сімферополь', '324805', '663c8ad5-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Київ', '300335', '663c8aed-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Донецьк', '394040', '70955f6e-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УСБ", м. Київ', '322012', '70955f7f-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ГОЛ.УПР. ПАТ ПІБ В ДОНЕЦ.ОБЛ", м. Донецьк', '334635', '791fabd6-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АГРОКОМБАНК", м. Київ', '322302', '8e3c9eab-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Суми', '337546', '8e3c9ebd-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Херсон', '352770', '8e3c9ed3-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Дніпропетровськ', '305299', '9f5399cd-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ДОНГОРБАНК", м. Донецьк', '334970', '9f5399e7-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Львів', '325774', '9f539a04-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Львів', '325321', 'ab3a9717-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Херсон', '352479', 'ab3a9730-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "ПОЛТАВА-БАНК", м. Полтава', '331489', 'b54be546-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "УКРГАЗПРОМБАНК", м. Київ', '320843', 'b54be552-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Кіровоград', '323624', 'ca3b8b80-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДОБАНК", м. Львів', '325365', 'ca3b8b99-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЗАЛІЗНИЧНЕ ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.КИЇВ", м. Київ', '322153', 'ca3b8ba0-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "МЕТАБАНК", м. Запоріжжя', '313582', 'd36a5a30-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "МТБ", м. Іллічівськ', '328168', 'df587e78-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Київ', '322904', 'df587e7f-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "МЕГАБАНК", м. Харків', '351629', 'df587e88-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Одеса', '328351', 'ec9930cc-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Хмельницький', '315966', 'ec9930cf-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Київ', '320230', 'ec9930d2-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНГ БАНК УКРАЇНА", м. Київ', '300539', 'ec9930d9-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК", м. Київ', '300131', 'ec9930de-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СІТІБАНК", м. Київ', '300584', 'ec9930e6-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДОБАНК", м. Львів', '325912', 'ec9930e9-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЗРУ Фінанси та кредит", м. Запоріжжя', '313731', 'ec9930ec-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ЛЬВІВ", м. Львів', '325633', 'ec9930ef-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Дніпродзержинська', '305965', 'f049d3aa-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУ ДКУ у Київській області, м. Київ', '821018', 'f049d3b4-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Черкаській області, м. Черкаси', '854018', 'f049d3bc-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Днiпропетровськ', '305653', 'f049d3bf-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Львів', '325570', 'f049d3c2-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Луганськ', '304007', 'f049d3c7-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Тернопіль', '338501', 'f049d3ca-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Сімферополь', '324021', 'f049d3d1-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Донецьк', '334862', 'f049d3d8-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "IНДЕКС-БАНК", м. Дніпропетровськ', '307015', 'f049d3db-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СЕБ БАНК", м. Київ', '300175', 'f049d3e0-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК Національні інвестиції", м. Київ', '300498', 'f049d3e7-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УКРСОЦБАНК", м. Харків', '351016', 'f7164850-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНДЕКС-БАНК", м. Київ', '300614', 'f7164853-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Запоріжжя', '313827', 'f7164856-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПрАТ "СВЕДБАНК ІНВЕСТ", м. Київ', '320650', 'f7164859-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Київ', '320382', 'f716485d-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРАВЕКС-БАНК", м. Київ', '321983', 'f7164873-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('HБУ Львівс.інститут БС УБС (м.Київ), м.Львів', '399067', 'f7164877-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У МИКОЛАЇВСЬКІЙ ОБЛАСТІ, м. Миколаїв', '826013', 'f716487e-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Дніпропетровськ', '306458', 'f7164883-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Херсон', '352639', 'f716488b-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "ЕКСПРЕС-БАНК", м. Львів', '325956', 'f716488e-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '804013', 'f7164895-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "ІНТЕГРАЛ", м. Київ', '320735', '314dd430-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Київ', '320241', 'f716489c-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РОДОВІД БАНК", м. Київ', '321712', '171dc60d-bc07-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Хмельницький', '315609', 'f71648a8-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Київ', '300711', 'f71648af-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЗРУ Фінанси та кредит", м. Львів', '325923', 'f71648b8-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Сімферополь', '384436', 'f71648be-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УКРСОЦБАНК", м. Сімферополь', '324010', '00fcac0a-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "ДІАМАНТБАНК", м. Київ', '320854', '00fcac12-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "НОВИЙ", м. Дніпропетровськ', '305062', '00fcac17-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "БГ БАНК", м. Київ', '320995', '00fcac2d-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Ужгород', '312378', '00fcac3b-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Кіровоград', '323583', '00fcac3f-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Ужгород', '312345', '00fcac44-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УКРСОЦБАНК", м. Львів', '325019', '00fcac50-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "УКРКОМУНБАНК", м. Луганськ', '304988', '00fcac59-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Рівне', '333368', '00fcac5e-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "ПІВДЕНКОМБАНК", м. Кривий Ріг', '306889', '00fcac63-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКРЕКСІМБАНК", м. Ужгород', '312226', '00fcac6b-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Одеса', '328704', '00fcac71-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Луцьк', '303440', '00fcac78-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСIМБАНК", м. Дніпропетровськ', '305675', '00fcac7e-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Рівне', '333391', '0b5f0a06-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Хмельницький', '315405', '0b5f0a1a-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Черкаси', '354347', '0b5f0a20-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЛЬВІВ", м. Львів', '325268', '0b5f0a2f-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНДУСТРІАЛБАНК", м. Запоріжжя', '313849', '0b5f0a37-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "МЕРКУРІЙ", м. Харків', '351663', '0b5f0a40-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('Управління ДК в АРК, м. Сімферополь', '824026', '0b5f0a4f-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "УКРБІЗНЕСБАНК", м. Донецьк', '334969', '0b5f0a64-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КФС "КБ Експобанк", м. Київ', '322421', '314dd40a-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ СЕВАСТОПОЛЬСЬКА ФІЛІЯ ПАТ КБ"ПРИВАТБАНК", м. Севастополь', '324935', '0b5f0a81-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ЧЕРКАСИ", м. Черкаси', '354091', '152ff890-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "ІНДЕКС-БАНК", м. Львів', '325279', '152ff8a4-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Iвано-Франківськ', '336462', '152ff8b4-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "КРЕДОБАНК", м. Луцьк', '303224', '152ff8c7-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Запоріжжя', '313399', '152ff8d5-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Харків', '350589', '152ff8db-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Одеса', '328191', '152ff8de-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КФС "ХАРКІВСЬКЕ РУ", м. Харків', '351964', '152ff8f0-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ Путіловська філія ПАТ ПІБв, м. Донецьк', '334914', '152ff8f7-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДИТПРОМБАНК", м. Львів', '385305', '152ff906-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРИМСЬКЕ ЦЕНТРАЛЬНЕ ВІД.ПАТ ПІБ", м. Сімферополь', '324430', '206687d6-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПЕРШИЙ ІНВЕСТИЦІЙНИЙ БАНК", м. Київ', '300506', '206687e2-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Київ', '322755', '206687e8-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УСБ", м. Київ', '300023', '206687eb-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Харків', '350385', '206687f6-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Донецьк', '335076', '206687fd-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДОБАНК", м. Сімферополь', '324913', '274871ae-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Київ', '320917', '274871b6-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ ПАТ Банк "Морський", м. Севастополь', '324742', '274871c3-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Луганськ', '304289', '274871c8-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Харків', '351823', '274871cb-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЛІВОБЕРЕЖНЕ ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.КИЇВ", м. Київ', '322119', '274871ce-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Львів', '325718', '274871d6-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Харків', '351533', '274871e4-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Черкаси', '354411', '274871ec-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ГРАНТ", м. Київ', '322788', '27487203-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КОНТРАКТ", м. Київ', '322465', '27487206-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ГРАНТ", м. Харків', '351607', '314dd3ed-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КФС "КБ Експобанк", м. Київ', '322294', '314dd3f2-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "Фінанси та кредит", м. Кривий Ріг', '305835', '314dd40d-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ У ТЕРНОПІЛЬСЬКІЙ ОБЛ, м. Тернопіль', '838012', '314dd438-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Сімферополь', '384016', '314dd443-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Харківській області, м. Харків', '851011', '314dd448-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Кривий Рiг', '305750', '314dd465-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКБ БАНК", м. Кременчук', '331100', '314dd468-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Тернопіль', '338783', '314dd46b-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Херсон', '352093', '3aa065e3-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКБ БАЗИС", м. Харків', '351760', '3aa065ed-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "КРЕДОБАНК", м. Київ', '321897', '3aa06602-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Пустомити', '385231', '3aa0660a-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРАЇНСЬКИЙ ПРОФЕСІЙНИЙ БАНК", м. Київ', '300205', '3aa0660d-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Кіровоград', '323538', '3aa0661e-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "УСБ", м. Донецьк', '334011', '3aa06645-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ У ХМЕЛЬНИЦЬКІЙ ОБЛАСТІ, м. Хмельницький', '815013', '3aa06654-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Чернівці', '356464', '45e7a59a-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Чернігів', '353348', '45e7a59d-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "МОТОР-БАНК", м. Запоріжжя', '313009', '2233c487-2f8e-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КІБ КРЕДІ АГРІКОЛЬ", м. Київ', '300379', '58ddd964-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Вінниця', '302689', 'b0456ee8-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЛЕГБАНК", м. Київ', '300056', 'c4339736-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКРЕКСІМБАНК", м. Харків', '351618', 'cc6cd3bd-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Луганськ', '304665', 'ea37d646-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Луганськ', '364393', 'ea37d656-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Чернівці', '356282', 'fdffd8ed-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Луганськ', '304193', '1d0f1b8c-a32d-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Донецьк', '335537', '29e4cd1f-a7ad-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІЕЙБІ БАНК", м. Київ', '380537', '67459569-a7af-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКТАБАНК", м. Дніпропетровськ', '307394', '67459570-a7af-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПФБ", м. Київ', '320906', '2f2c49b5-4721-11e1-9d95-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРУ Фінанси та кредит", м. Полтава', '331832', '42dfb0a1-a931-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Київ', '380764', '2c421fe4-56d8-11e1-b7dc-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КИЇВСЬКЕ МІСЬКЕ ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК", м. Київ', '322250', '42dfb0b8-a931-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "Фінанси та кредит", м. Харків', '350697', '42dfb0bb-a931-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "СТАРОКИЇВСЬКИЙ", м. Київ', '321477', '42dfb0bf-a931-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Київ', '380333', '439300b2-a932-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АРКАДА", м. Київ', '322335', '439300b7-a932-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ДЕЛЬТА БАНК", м. Київ', '380236', 'af6273f6-ab91-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "ЕНЕРГОБАНК", м. Київ', '300272', 'af6273fc-ab91-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УніКредит Банк"', '303536', '57e423b7-ad1b-11e0-8c94-001d608fc1f7');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Запоріжжя', '313623', '8604f542-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІМЕКСБАНК", м. Миколаїв', '326825', '8604f546-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Сімферополь', '308111', '8604f54b-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "МТБ", м. Херсон', '342285', '8604f555-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ Акордбанк", м. Київ', '380634', '8604f559-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Донецьк', '335106', '8604f55b-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ОДЕСА", м. Одеса', '328135', '8604f55d-ae05-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "ПІВДЕННИЙ", м. Запоріжжя', '313753', 'd776d4a0-aece-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ЗОЛОТІ ВОРОТА", м. Харків', '351931', 'd776d4a2-aece-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК НАЦІОНАЛЬНИЙ КРЕДИТ", м. Київ', '320702', 'b136352c-b105-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "УКРІНБАНК", м. Львів', '325826', 'b1363541-b105-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Суми', '337483', 'c8cbebf2-b751-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Донецьк', '334851', 'c8cbec02-b751-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Ялта', '384522', 'c8cbec04-b751-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ЖИТОМИР", м. Житомир', '311056', '78ca6131-b753-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК БОГУСЛАВ", м. Київ', '380322', 'c714581e-b754-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЕРСТЕ БАНК", м. Київ', '380009', '42dfb09e-a931-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Сімферополь', '384652', '4926ff52-b828-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ФОРТУНА-БАНК", м. Київ', '300904', '173fcf87-b8f4-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПТБ", м. Київ', '380388', 'dc39037f-48c1-11e1-9577-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '805012', '173fcf99-b8f4-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЗЛАТОБАНК", м. Київ', '380612', '21d72170-b9cc-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ФІНБАНК", м. Одеса', '328685', '073615d4-f587-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Хмельницький', '315858', '171dc606-bc07-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Миколаїв', '326739', '171dc60b-bc07-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПЛЮС БАНК", м. Івано-Франківськ', '336310', '04cb39e9-bda5-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Хмельницький', '315784', 'b2e6cc12-be61-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ХАРКІВ", м. Харків', '351458', '70599406-c184-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДИТПРОМБАНК", м. Дніпропетровськ', '306890', '39ecd77e-c26f-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СХІДНО-ПРОМИСЛОВИЙ КОМЕРЦІЙНИЙ БАНК", м. Луганськ', '304706', '648dedaf-c3e5-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ОРУ Фінанси та кредит", м. Одеса', '328823', '90e67c85-c7d8-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Миколаїв', '326803', '2d5b5e23-c89a-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РЕАЛ БАНК", м. Лисичанськ', '304524', 'aca8c2a5-cee4-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РЕГІОН-БАНК", м. Харків', '351254', '3aac5b6d-d21c-11e0-978e-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Одеса', '328618', '3aac5b74-d21c-11e0-978e-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ХЕРСОН", м. Херсон', '352286', '55e43a10-d398-11e0-9ea7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "ЮНЕКС", м. Київ', '322539', '6af4facb-d478-11e0-9ea7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Львів', '385532', 'e496bc33-daa6-11e0-bb11-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КОМЕРЦІЙНИЙ БАНК", м. Київ', '380980', 'e4204451-ddd4-11e0-a726-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '825014', '09881a06-de96-11e0-a726-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ ХРЕЩАТИК", м. Київ', '300670', 'c82dded9-e02a-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Харків', '350761', '99d70f38-e28f-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Харків', '351834', '6927df7f-e5a6-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК КІПРУ", м. Київ', '320940', '4926ff63-b828-11e0-8bcd-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Луганськ', '304632', '2e907bfd-e805-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Дніпропетровськ', '305578', '309ce55c-e8d7-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "УКРІНБАНК", м. Київ', '300250', '20d1ee08-ea60-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ЧЕРНІГІВ", м. Чернігів', '353456', '4db0f0aa-ef12-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Сімферополь', '324786', '71b6081f-f0ab-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Львів', '325978', 'c12b5776-f3e3-11e0-a5ee-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДОБУ", м. Київ', '300465', '10ede60d-f633-11e0-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КЛІРИНГОВИЙ ДІМ", м. Київ', '300647', '10ede61a-f633-11e0-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЄКБ", м. Дніпропетровськ', '307349', '10ede626-f633-11e0-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СИГМАБАНК", м. Дніпропетровськ', '307305', '94a3f67b-f8ae-11e0-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ЗОЛОТІ ВОРОТА", м. Київ', '300238', 'a9a03d0f-0520-11e1-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНДУСТРІАЛБАНК", м. Львів', '385424', '1e325474-1024-11e1-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕНКОМБАНК", м. Діпропетровськ', '305266', 'd9532508-11b2-11e1-b0a3-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "МІСТО БАНК", м. Київ', '380593', 'b6e0eee6-1409-11e1-b0a3-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК М.ЗАПОРІЖЖЯ", м. Запоріжжя', '313355', 'b6e0eef6-1409-11e1-b0a3-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Чернігів', '353586', '0db217d6-14d7-11e1-b0a3-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Київ', '380269', 'af716c55-1a56-11e1-b785-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Рівне', '333681', 'ca062441-1cb6-11e1-b785-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВБР", м. Київ', '380719', 'ca062443-1cb6-11e1-b785-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Запоріжжя', '313957', '83a03f9b-2a1c-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Рівненській області, м. Рівне', '833017', '83a03fb3-2a1c-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЕРДЕ БАНК", м. Київ', '380667', '2785dac0-2d6a-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Маріуполь', '335429', 'aeef7a43-305c-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "Бізнес Стандарт", м. Київ', '339500', 'd130ae6d-3125-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК РУСКИЙ СТАНДАРТ", м. Київ', '380418', '9681fd83-31ee-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ ПІВДЕНКОМБАНК", м. Донецьк', '335946', '9681fd8f-31ee-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "НАДРА", м. Київ', '320003', '1cca5db7-35da-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Луганськ', '304795', 'fa5067c9-3770-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНДУСТРІАЛБАНК", м. Київ', '320962', 'fa5067cd-3770-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ТОВ "УНІКОМБАНК", м. Харків', '350006', '1f19825b-3c25-11e1-a992-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДИТПРОМБАНК", м. Донецьк', '335593', 'e9b8c922-3db2-11e1-841f-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСIМБАНК", м. Донецьк', '334817', 'caca3341-458c-11e1-9d95-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКСУ у Полтавській області', '831019', '1e942a68-34e8-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКООПСПІЛКА", м. Львів', '325815', 'c6d08362-53b7-11e1-b7dc-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УHІВ. Філія ПАТ УніКредит Банк у м.Києві', '300744', '82680983-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "Д-М БАНК", м. Київ', '380689', '350850dc-5c5a-11e1-a03d-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКБ БАНК", м. Донецьк', '335764', '4f7f0c6d-5eb3-11e1-a03d-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ФІНЕКСБАНК", м. Київ', '380311', '4f7f0c71-5eb3-11e1-a03d-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЄВРОБАНК", м. Київ', '380355', '4f7f0c75-5eb3-11e1-a03d-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ДРУ", м. Донецьк', '335816', 'c87d1453-6823-11e1-bbbd-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ВІННИЦЯ", м. Вінниця', '302571', '8826e245-71a0-11e1-8e27-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІМЕКСБАНК", м. Київ', '300766', '8cdbdec4-7746-11e1-8e27-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ ФСевастопольське міське відділен ВАТОщад, м. Севастополь', '384027', 'cb958001-7977-11e1-8e27-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "УКРІНБАНК", м. Київ', '300142', 'f3c314c9-7d5a-11e1-8e27-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ТЕРРА БАНК", м. Київ', '380601', 'c0b42af3-7ee4-11e1-8e27-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Рівне', '333539', 'b46ad977-8853-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Iвано-Франківськ', '336688', 'b46ad97b-8853-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Чернівці', '356271', 'c7d9aa58-89ef-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПУМБ", м. Львів', '385350', '668f4b22-8dd7-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ УФС", м. Донецьк', '377777', '668f4b28-8dd7-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ТРАСТ", м. Київ', '380474', 'b9a42c3f-8e9a-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Закарпатській області, м. Ужгород', '812016', '8e5ac51c-94fb-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ТАВРИКА", м. Київ', '300788', '27c5f917-9810-11e1-8016-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Сумській області, м. Суми', '837013', 'b60f69a5-9feb-11e1-8f28-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КОНВЕРСБАНК", м. Київ', '339339', 'b60f69a9-9feb-11e1-8f28-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Миколаїв', '326610', '39deee38-a30f-11e1-8f28-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('HБУ Головне управління по м.Києву та облаті', '321024', 'decdb31c-a49e-11e1-8f28-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Донецьк', '335678', '6de8c34c-b391-11e1-ba76-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "А-БАНК", м. Дніпропетровськ', '307770', 'd4348f9b-c660-11e1-ae45-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ІНВЕСТИЦІЙ ТА ЗАОЩАДЖЕНЬ", м. Київ', '380281', 'f976ef18-cb1a-11e1-ae45-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "УКРІНБАНК", м. Одеса', '328696', 'f976ef1c-cb1a-11e1-ae45-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КЛІРИНГОВІЙ ДІМ", м. Сімферополь', '384920', 'f44e0842-da14-11e1-84e2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПОЛІКОМБАНК", м. Чернігів', '353100', '9af5e555-dc89-11e1-84e2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДИТПРОМБАНК", м. Луцьк', '303741', '488c39ae-ed1a-11e1-a576-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КОМЕРЦІЙНИЙ БАНК", м. Київ', '380054', '488c39e1-ed1a-11e1-a576-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Львів', '325796', '0ccc1bf2-f1a0-11e1-a576-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "КАПІТАЛ", м. Донецьк', '334828', 'c86da372-f80c-11e1-a963-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВІДДІЛЕННЯ ПАТ ПРОМІНВЕСТБАНК В М.ЛУГАНСЬК", м. Луганськ', '304308', 'b819495d-022e-11e2-a963-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ У ЖИТОМИРСЬКІЙ ОБЛАСТІ, м. Житомир', '811039', 'b81949ca-022e-11e2-a963-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ДРРУ Фінанси та кредит", м. Дніпропетровськ', '307231', 'e103d22e-0874-11e2-b7c3-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Краматорськ', '335548', '785d2df0-1dad-11e2-93f1-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КИЇВСІТІ", м. Київ', '380775', '554bad95-1e71-11e2-93f1-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ Ф-я ВАТ "Укрексімбанк", Севастополь, м. Севастополь', '384986', 'e71336b0-2d7c-11e2-93f1-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Херсон', '352640', 'e71336b9-2d7c-11e2-93f1-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСIМБАНК", м. Кривий Ріг', '305589', '942e7ff1-442f-11e2-93f6-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АК "КБ ПРОМЕКОНОМБАНК", м. Донецьк', '334992', '2f23ef31-45c7-11e2-93f6-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК Фінанси та кредит", м. Луганськ', '304717', '46840ad1-5fbb-11e2-93f9-005056c00008');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІМЕКСБАНК", м. Одеса', '388584', '354083a9-849b-11e2-9400-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКБ Київ", м. Київ', '322498', '1fe07b24-a80e-11e2-9178-5404a6ecc3a4');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('"КРЕДИТВЕСТ БАНК", м. Київ', '380441', '98fc4d2b-b0b3-11e2-ba82-5404a6ecc3a4');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ Актив-банк", м. Київ', '300852', '1fe07b26-a80e-11e2-9178-5404a6ecc3a4');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ОКСІ БАНК", м. Львів', '325990', '94cf1585-c207-11e2-b644-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ДЕРЖАВНІЙ КАЗНАЧЕЙСЬКІЙ СЛУЖБІ УКРАЇНИ, м. Києва', '820172', '4e1e7fd9-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АКЦІОНЕРНИЙ КОМЕРЦІЙНИЙ ПРОМИСЛОВО-ІНВЕСТИЦІЙНИЙ БАНК", м. Київ', '300012', '81274cf4-ac50-11e0-89d7-001fd0d31498');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУ ОЩАДНИЙ БАНК УКРАЇНИ, м. Київ', '322669', '0b5f0a54-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БРОКБІЗНЕСБАНК", м. Київ', '300249', 'd36a5a4c-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЕКСПРЕС-БАНК", м. Київ', '322959', '20668806-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПуАТ "СЕБ Банк"', '300175', 'c763466b-ffb7-11e0-abf2-003048635795');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ФАМІЛЬНИЙ", м. Донецьк', '334840', '7e3aed1f-cf5e-11e2-b39d-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "КИЇВСЬКА РУСЬ", м. Львів', '385543', '059c81c8-d1b3-11e2-b39d-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Запоріжжя', '313979', '1986f6f9-dd81-11e2-999f-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК, м. Iвано-Франківськ', '836014', 'db4bce4f-dd95-11e2-999f-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Вінницькій області, м. Вінниця', '802015', 'db4bce53-dd95-11e2-999f-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РЕАЛ БАНК", м. Харків', '351588', '7c2c9753-e7b0-11e2-999f-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ХК БАНК", м. Дніпропетровськ', '307123', '17cf5ea3-eac1-11e2-856b-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Суми', '337568', 'e4d9b740-edee-11e2-856b-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "УКРЕКСІМБАНК", м. Черкаси', '354789', '349c5a68-eebd-11e2-856b-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Херсон', '352457', '349c5a6a-eebd-11e2-856b-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПФБ", м. Кременчук', '331768', '55e3bc13-f2a1-11e2-856b-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК  У  ВОЛИНСЬКІЙ ОБЛАСТІ, м.м.Луцьк', '803014', '6a4acba6-f5fc-11e2-b2e1-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "КБ Глобус", м. Київ', '380526', 'e8f349b7-1072-11e3-8b1d-005056937580');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КРЕДИТ ЄВРОПА БАНК", м. Київ', '380366', '02c6e13e-30b4-11e3-953c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '813015', 'cf33c963-3bba-11e3-94f0-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "ДЕРЖАВНИЙ ОЩАДНИЙ БАНК УКРАЇНИ", м. Дніпропетровськ', '305482', '8a47ac0d-420c-11e3-94f0-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('HБУ Господарсько-експлуат. управління НБУ, м. Київ', '399261', 'baa80cac-47a1-11e3-99f7-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ФІНРОСТБАНК", м. Одеса', '328599', 'e0301f60-528b-11e3-9769-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РАДИКАЛ БАНК", с. Петропавлівська Борщагівка', '319111', '83733e0c-5e48-11e3-b6ba-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('КБ "ОЛІМПІЙСЬКА УКРАЇНА", м. Київ', '322324', '003b84e6-63e9-11e3-b6ba-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКООПСПІЛКА", м. Київ', '322625', '7ef81feb-6edb-11e3-b6ba-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ВіЕс Банк", м. Львів', '325213', '82680985-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АКБ "КБ ІНВЕСТБАНК", м. Одеса', '328094', '1382a5c2-93fd-11e3-a605-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Донецьк', '377012', '657a2320-9ebc-11e3-a605-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КОМІНВЕСТБАНК", м. Ужгород', '312248', '77fd9440-9f88-11e3-a605-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЄВРОГАЗБАНК", м. Київ', '380430', '018321dd-0bec-11e3-b47f-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СБЕРБАНК РОСІЇ", м. Київ', '320627', '4f8b7de3-1ec5-11e3-a110-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ОТП БАНК", м. Київ', '300528', '4f8b7ddf-1ec5-11e3-a110-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "Укргазбанк", м. Київ', '320478', '8268096e-a235-11e0-9eaf-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '018321e4-0bec-11e3-b47f-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "РОЗРАХУНКОВИЙ ЦЕНТР", м. Київ', '320649', '7262004e-4862-11e3-99f7-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "ЄВРОГАЗБАНК"', '380430', 'a4eb0b79-454f-11e3-94f0-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДОНЕЦЬКІЙ ОБЛАСТІ, м. Донецьк', '834016', '9d5dc887-47ab-11e3-99f7-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКРСИББАНК", м. Харків', '351005', 'a73677ae-51b5-11e3-9769-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СБЕРБАНК РОСІЇ", м. Київ', '320627', 'a73677b2-51b5-11e3-9769-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АБ "Укргазбанк", м. Київ', '320478', '0b5f0a61-a32c-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ОТП БАНК", м. Київ', '300528', 'd36a5a3e-a32a-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('АТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Київ', '380805', 'b117d9a2-b34b-11e3-9446-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ОД ПАТ "РАЙФФАЙЗЕН БАНК АВАЛЬ", м. Миколаїв', '326182', '01d61a61-b8c8-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ Земельний Капітал", м. Дніпропетровськ', '305880', '42b65455-c150-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'ac533efb-c156-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '000813', 'ac533efc-c156-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '000805', 'ac533efd-c156-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '000804', 'ac533f06-c156-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '000825', 'b24fa894-c156-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'd17df1c3-c15a-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '000813', 'd17df1c4-c15a-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '000805', 'd17df1c5-c15a-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '000804', 'd17df1c6-c15a-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '000825', 'd96e8a74-c15a-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'c6ba6135-c15d-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '000805', 'c6ba6136-c15d-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '000813', 'c6ba6137-c15d-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '000804', 'c6ba6138-c15d-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '000825', 'd00aa18e-c15d-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРАЙМ-БАНК", м. Київ', '300669', '018bcc97-c471-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПІВДЕННИЙ", м. Луганськ', '304999', 'b5c0a199-cbb8-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "КБ Преміум", м. Київ', '339555', '08c6a927-d048-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУ ДКСУ М.КИЄВІ, м. Київ', '820019', 'f049d3b7-a32b-11e0-a6d3-000423d81302');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ЄКБ", м. Дніпропетровськ', '305987', 'e9ea8e16-d42e-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '000804', '0e096a7d-d45b-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '0e096a7e-d45b-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '000805', '0e096a7f-d45b-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '000813', '0e096a80-d45b-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '000825', '240b969d-d45b-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '000804', '818aa747-d468-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '818aa748-d468-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '000805', '818aa749-d468-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '000813', '818aa74a-d468-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '000825', '97b62df1-d468-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ЛУГАНСЬКІЙ ОБЛАСТІ, м. Луганськ', '000804', '7bad644f-d46c-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '7bad6450-d46c-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('УДК У ДНІПРОПЕТ.ОБЛ, м. Дніпропетровськ', '000805', '7bad6451-d46c-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Запорізькій області, м. Запоріжжя', '000813', '7bad6452-d46c-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДКУ у Львівській області, м. Львів', '000825', '7bad6453-d46c-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '6b9f163d-d518-11e3-9604-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРИВАТБАНК", м. Житомир', '311744', '63e0237c-fc3f-11e3-85a4-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ВАТ "СТАНДАРТ", м. Київ', '380690', 'cb3b6fcc-fd15-11e3-85a4-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АРТЕМ-БАНК", м. Київ', '300885', '5fd18600-0d86-11e4-85a4-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "УКРЕКСІМБАНК", м. Вінниця', '302429', '536ecd68-123b-11e4-9312-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ПРАВЕКС-БАНК", м. Київ', '380838', '38b0a56b-1404-11e4-9312-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '495040a5-194c-11e4-bba7-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "СЕБ КОРПОРАТИВНИЙ БАНК", м. Київ', '380797', 'af4288b2-1956-11e4-bba7-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '9e8ee9cb-2eb3-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '920f78fc-2eb7-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'e256b3cd-2eb8-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'c824aa96-2eb9-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'c54a97c5-2f66-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '6868c54b-2f81-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "ІНТЕРКРЕДИТБАНК", м. Дніпропетровськ', '307424', 'e57dfe21-340b-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК ВЕЛЕС", м. Київ', '322799', 'de93219e-34ec-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '641ca893-38d2-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'ce92532c-4a01-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '7ae2e936-4a0a-11e4-870c-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "БАНК СІЧ", м. Київ', '380816', 'b684f729-5b59-11e4-a742-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '5a5c7b1f-61bd-11e4-a742-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '456f2afa-6449-11e4-a742-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АВАНТ-БАНК", м. Київ', '380708', '62e0294e-6f21-11e4-847d-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', 'b7d2dae6-7a67-11e4-92ae-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ГУДК У М.КИЄВІ, м. Київ', '000820', '43b0c9e6-7a6c-11e4-92ae-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('ПАТ "АБ "РАДАБАНК", м. Дніпропетровськ', '306500', '9082f2d2-81df-11e4-92ae-ac162d8baeeb');
INSERT INTO "public".bank ("name", mfo, guid) 
	VALUES ('HБУ УНІВЕРСИТЕТ БАНКІВ.СПРАВИ НБУ, М.КИЇВ, м. Київ', '399391', 'faa6ba8b-89bb-11e4-92ae-ac162d8baeeb');
-- prod +++++
CREATE TABLE currency (id BIGSERIAL NOT NULL, short_name VARCHAR(64) NOT NULL, code INT NOT NULL, PRIMARY KEY(id));
COMMENT ON COLUMN currency.short_name IS 'Краткое название валюты';
COMMENT ON COLUMN currency.code IS 'Код валюты';
ALTER TABLE organization_current_account ADD currency_id BIGINT DEFAULT NULL;
DROP INDEX unique_organization_current_account_idx;
ALTER TABLE organization_current_account ADD CONSTRAINT FK_85A323F438248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_85A323F438248176 ON organization_current_account (currency_id);
CREATE UNIQUE INDEX unique_organization_current_account_idx ON organization_current_account (name, organization_id, bank_id, currency_id);
ALTER TABLE bank_cron ADD CONSTRAINT FK_BDAD527611C8FB41 FOREIGN KEY (bank_id) REFERENCES Bank (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE UNIQUE INDEX UNIQ_6956883F77153098 ON currency (code);

INSERT INTO "public".currency (short_name, code) 
	VALUES ('грн', 980);
INSERT INTO "public".currency (short_name, code) 
	VALUES ('USD', 840);
INSERT INTO "public".currency (short_name, code) 
	VALUES ('EUR', 978);
INSERT INTO "public".currency (short_name, code) 
	VALUES ('PLN', 985);
INSERT INTO "public".currency (short_name, code) 
	VALUES ('RUB', 643);

INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 1891, 364, '26004196728', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 1754, 364, '26008196735', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 1754, 364, '26042196735', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 14, '26009000801901', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 15, '26009011001449', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 16, '2600901032047', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 43, '26007301019835', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 43, '26042010019835', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 367, '260093017275', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 367, '260433017275', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 195, '26004274613001', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 195, '26004274613001', 2);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 195, '26004274613001', 3);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 18, '26006009100048', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 19, '26009013343001', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 41, '26009060294537', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 39, '26004153553', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 42, '26001013001698', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 20, '26005000640601', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 21, '26009192039600', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 282, '26005010005786', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 357, '26002000010910', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 21, '26054192039600', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 223, '26153300096801', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 192, '26003001304681', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 357, '29248000000253', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 21, '26009192039600', 4);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 247, '26000066779001', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 367, '260063017275', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 216, '29244809996333', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 331, '29096620349277', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 40, '26002016663601', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 43, '26007301019835', 3);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 191, '26107260200005', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 364, '260093017275', 2);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 216, '260093101', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 394, '37122001006047', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 216, '260093101', 5);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 364, '260093017275', 3);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 216, '2600326315', 5);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 191, '26000260200094', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 75, '26008200799003', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 23, 351, '26003300427278', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 2590, 367, '2600330123940', 1);
INSERT INTO "public".organization_current_account (type_id, organization_id, bank_id, "name", currency_id) 
	VALUES (2, 2590, 216, '260067522', 1);

-- prod +++++++++++
ALTER TABLE pay_master ADD bank_id BIGINT DEFAULT NULL;
ALTER TABLE pay_master ADD CONSTRAINT FK_A492699911C8FB41 FOREIGN KEY (bank_id) REFERENCES Bank (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_A492699911C8FB41 ON pay_master (bank_id);
COMMENT ON COLUMN pay_master.reason IS 'Причина отклонения';

-- prod ----

CREATE TABLE position_group (position_id BIGINT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(position_id, group_id));
CREATE INDEX IDX_E100A018DD842E46 ON position_group (position_id);
CREATE INDEX IDX_E100A018FE54D947 ON position_group (group_id);

alter table companystructure add is_hidden boolean default false;
update companystructure set is_hidden = true where id = 7 or id = 23;
update companystructure set name = 'Восточное отделение' where id = 24;
update stuff set companystructure_id = 24 where companystructure_id = 23;

insert into position (name) values ('Опердиректор Украины');
insert into position (name) values ('Кладовщик');
insert into position (name) values ('Главный инженер');
insert into position (name) values ('Инженер');
insert into position (name) values ('Юрист консульт');
insert into position (name) values ('Менеджер по продажам');
insert into position (name) values ('Администратор продаж');
insert into position (name) values ('Специалист фин контролинга');
insert into position (name) values ('Бухгалтер');
insert into position (name) values ('Главный бухгалтер');
insert into position (name) values ('Начальник отдела кадров');
insert into position (name) values ('Инспектор отдела кадров');
insert into position (name) values ('Ассистент дирекции');
insert into position (name) values ('Водитель-курьер');
insert into position (name) values ('Менеджер по персоналу');

update news_companystructure set companystructure_id = 24 where companystructure_id = 23;
update invoice_companystructure set companystructure_id = 24 where companystructure_id = 23;

CREATE TABLE deputy (id BIGSERIAL NOT NULL, forStuff_id BIGINT DEFAULT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_28FA6B9F2401F346 ON deputy (forStuff_id);
ALTER TABLE deputy ADD CONSTRAINT FK_28FA6B9F2401F346 FOREIGN KEY (forStuff_id) REFERENCES stuff (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE TABLE deputy_stuff (deputy_id BIGINT NOT NULL, stuff_id BIGINT NOT NULL, PRIMARY KEY(deputy_id, stuff_id));
CREATE INDEX IDX_E2E17F34B6F93BB ON deputy_stuff (deputy_id);
CREATE INDEX IDX_E2E17F3950A1740 ON deputy_stuff (stuff_id);
ALTER TABLE deputy_stuff ADD CONSTRAINT FK_E2E17F34B6F93BB FOREIGN KEY (deputy_id) REFERENCES deputy (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE deputy_stuff ADD CONSTRAINT FK_E2E17F3950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE TABLE file_access_history (id SERIAL NOT NULL, user_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, action VARCHAR(50) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_72401305A76ED395 ON file_access_history (user_id);
ALTER TABLE file_access_history ADD CONSTRAINT FK_72401305A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE TABLE calculator_price (id BIGSERIAL NOT NULL, calculator_item_id BIGINT DEFAULT NULL, value DOUBLE PRECISION NOT NULL, unit VARCHAR(20) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_E4A557C0A9005098 ON calculator_price (calculator_item_id);
CREATE TABLE calculator_item (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT '' NOT NULL, PRIMARY KEY(id));
ALTER TABLE calculator_price ADD CONSTRAINT FK_E4A557C0A9005098 FOREIGN KEY (calculator_item_id) REFERENCES calculator_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE calculator_item ADD parent_id BIGINT DEFAULT NULL;
ALTER TABLE calculator_item ADD CONSTRAINT FK_C663BD63727ACA70 FOREIGN KEY (parent_id) REFERENCES calculator_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_C663BD63727ACA70 ON calculator_item (parent_id)


ALTER TABLE task_user_role ADD is_updated BOOLEAN NOT NULL;
ALTER TABLE task ADD editeddatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
-- prod ???
CREATE TABLE project_gos_tender (id BIGSERIAL NOT NULL, project_id BIGINT DEFAULT NULL, vdz VARCHAR(128) NOT NULL, advert INT NOT NULL, branch VARCHAR(128) NOT NULL, type_of_procedure VARCHAR(128) NOT NULL, place VARCHAR(128) NOT NULL, delivery VARCHAR(128) NOT NULL, datetime_deadline TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, datetime_opening TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, software VARCHAR(128) NOT NULL, is_participation BOOLEAN DEFAULT NULL, reason BOOLEAN DEFAULT NULL, datetime_deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_4F4896C1166D1F9C ON project_gos_tender (project_id);
COMMENT ON COLUMN project_gos_tender.vdz IS '№ ВДЗ';
COMMENT ON COLUMN project_gos_tender.advert IS 'Объявления';
COMMENT ON COLUMN project_gos_tender.branch IS 'Отрасль';
COMMENT ON COLUMN project_gos_tender.type_of_procedure IS 'Процедура закупки';
COMMENT ON COLUMN project_gos_tender.place IS 'Место';
COMMENT ON COLUMN project_gos_tender.delivery IS 'Cрок поставки';
COMMENT ON COLUMN project_gos_tender.datetime_deadline IS 'Дата и время конечного срока';
COMMENT ON COLUMN project_gos_tender.datetime_opening IS 'Дата и время вскрытия';
COMMENT ON COLUMN project_gos_tender.software IS 'Обеспечение';
COMMENT ON COLUMN project_gos_tender.is_participation IS 'Участвуем или нет в тендере';
COMMENT ON COLUMN project_gos_tender.reason IS 'Причина учистия или нет в тендере';
COMMENT ON COLUMN project_gos_tender.datetime_deleted IS 'Дата и время удаления тендера';
ALTER TABLE project_gos_tender ADD CONSTRAINT FK_4F4896C1166D1F9C FOREIGN KEY (project_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_gos_tender__organization ADD CONSTRAINT FK_AC77C8A9A930DD36 FOREIGN KEY (project_gos_tender_id) REFERENCES project_gos_tender (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_gos_tender__organization ADD CONSTRAINT FK_AC77C8A932C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_gos_tender ALTER vdz DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER advert DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER branch DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER type_of_procedure DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER place DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER delivery DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER datetime_deadline DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER datetime_opening DROP NOT NULL;
ALTER TABLE project_gos_tender ALTER software DROP NOT NULL;
ALTER TABLE handling_type ADD alias VARCHAR(128) DEFAULT NULL;
COMMENT ON COLUMN handling_type.alias IS 'Альтернативное уникальное название типа';
COMMENT ON COLUMN handling_type.name IS 'Тип проекта';
ALTER TABLE project_gos_tender ALTER reason TYPE TEXT;
CREATE TABLE project_gos_tender__kved (project_gos_tender_id BIGINT NOT NULL, kved_id BIGINT NOT NULL, PRIMARY KEY(project_gos_tender_id, kved_id));
CREATE INDEX IDX_B9C3B856A930DD36 ON project_gos_tender__kved (project_gos_tender_id);
CREATE INDEX IDX_B9C3B856B530ADF6 ON project_gos_tender__kved (kved_id);
ALTER TABLE project_gos_tender__kved ADD CONSTRAINT FK_B9C3B856A930DD36 FOREIGN KEY (project_gos_tender_id) REFERENCES project_gos_tender (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_gos_tender__kved ADD CONSTRAINT FK_B9C3B856B530ADF6 FOREIGN KEY (kved_id) REFERENCES kved (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE UNIQUE INDEX unique_project_gos_tender ON project_gos_tender (advert, datetime_deadline);
COMMENT ON COLUMN project_gos_tender.datetime_opening IS 'Дата и время раскрытия';
CREATE TABLE project_file (id BIGSERIAL NOT NULL, project_id BIGINT DEFAULT NULL, name VARCHAR(128) NOT NULL, shortText VARCHAR(128) DEFAULT NULL, create_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_datetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_B50EFE08166D1F9C ON project_file (project_id);
COMMENT ON COLUMN project_file.name IS 'Название файла';
COMMENT ON COLUMN project_file.shortText IS 'Краткое описание файла';
COMMENT ON COLUMN project_file.create_datetime IS 'Дата создания файла';
COMMENT ON COLUMN project_file.deleted_datetime IS 'Дата удаления файла';
ALTER TABLE project_file ADD CONSTRAINT FK_B50EFE08166D1F9C FOREIGN KEY (project_id) REFERENCES handling (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
INSERT INTO "public".handling_type ("name", slug, sortorder, "alias") VALUES ('Електроные торги', NULL, 7, DEFAULT);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (18, 'Благоустройство', DEFAULT, 18, 18);
ALTER TABLE handling ALTER createdate DROP NOT NULL;
ALTER TABLE handling ADD closed_user_id INT DEFAULT NULL;
ALTER TABLE handling ADD datetime_closed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE handling ALTER createdatetime SET NOT NULL;
ALTER TABLE handling ADD CONSTRAINT FK_BFF965780D8506 FOREIGN KEY (closed_user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_BFF965780D8506 ON handling (closed_user_id);
COMMENT ON COLUMN handling.datetime_closed IS 'Дата закрытия проекта';
COMMENT ON COLUMN handling.is_closed IS 'Статус проекта (закрыт, открыт)';
ALTER TABLE handling_user ALTER handling_id SET NOT NULL;
CREATE TABLE project_gos_tender_participan (id BIGSERIAL NOT NULL, participan_id BIGINT DEFAULT NULL, project_gos_tender_id BIGINT DEFAULT NULL, summa NUMERIC(10, 2) DEFAULT NULL, is_winner BOOLEAN DEFAULT NULL, reason TEXT DEFAULT NULL, datetime_deleted TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_C7FD058B298820E ON project_gos_tender_participan (participan_id);
CREATE INDEX IDX_C7FD058BA930DD36 ON project_gos_tender_participan (project_gos_tender_id);
CREATE UNIQUE INDEX unique_gos_tender_participan ON project_gos_tender_participan (participan_id, project_gos_tender_id);
COMMENT ON COLUMN project_gos_tender_participan.summa IS 'Сумма';
COMMENT ON COLUMN project_gos_tender_participan.is_winner IS 'Победитель';
COMMENT ON COLUMN project_gos_tender_participan.reason IS 'Комментарий';
COMMENT ON COLUMN project_gos_tender_participan.datetime_deleted IS 'Дата и время удаления участника';
ALTER TABLE project_gos_tender_participan ADD CONSTRAINT FK_C7FD058B298820E FOREIGN KEY (participan_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_gos_tender_participan ADD CONSTRAINT FK_C7FD058BA930DD36 FOREIGN KEY (project_gos_tender_id) REFERENCES project_gos_tender (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_gos_tender_participan ADD datetime_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL;
COMMENT ON COLUMN project_gos_tender_participan.datetime_create IS 'Дата и время добавления участника';
UPDATE "public".handling_service SET "slug" = 'project' WHERE slug is null;

INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (19, 'БУД Будівництво, будівельні матеріали та спецтехніка', 'gos_tender', 19, 19);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (20, 'КОМ Комп"ютери та оргтехніка, програмне забезпечення', 'gos_tender', 20, 20);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (21, 'КУЛ Господарчі товари та культурно-побутова продукція', 'gos_tender', 21, 21);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (22, 'ЛЕГ Легка промисловість', 'gos_tender', 22, 22);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (23, 'МЕБ Меблія', 'gos_tender', 23, 23);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (24, 'МЕД Медицина та соціальна сфера', 'gos_tender', 24, 24);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (25, 'НДР Наукові дослідження та розробки', 'gos_tender', 25, 25);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (26, 'НЕР Нерухомість та оренда', 'gos_tender', 26, 26);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (27, 'ПАЛ Енергетика, паливо, хімія', 'gos_tender', 27, 27);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (28, 'ППД Поліграфія, друкарська справа', 'gos_tender', 28, 28);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (29, 'ПСГ Сільське господарство', 'gos_tender', 29, 29);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (30, 'ПХП Харчова промисловість та громадське харчування', 'gos_tender', 30, 30);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (31, 'ТЕХ Технологічне обладнання, комплектуючі та матеріали', 'gos_tender', 31, 31);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (32, 'ТРЗ Транспортні засоби та комплектуючі, технічне обслуговування', 'gos_tender', 32, 32);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (33, 'ТРП Товари, роботи, послуги', 'gos_tender', 33, 33);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (34, 'МЕТ Метали та продукція металообробки', 'gos_tender', 34, 34);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (35, 'КПН Консалтингові послуги, навчання', 'gos_tender', 35, 35);
INSERT INTO "public".handling_service (id, "name", slug, sortorder, report_number) VALUES (36, 'ЖИТ Житлово-комунальне, побутове обслуговування та спецтехніка', 'gos_tender', 36, 36);
CREATE TABLE project_file_type (id BIGSERIAL NOT NULL, name VARCHAR(128) NOT NULL, "group" VARCHAR(64) NOT NULL, PRIMARY KEY(id));
COMMENT ON COLUMN project_file_type.name IS 'Название типа документа';
COMMENT ON COLUMN project_file_type."group" IS 'Группа документов';
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Документація конкурсних торгів', 'gos_tender');
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Зміни до документації конкурсних торгів', 'gos_tender');
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Протокол розкриття конкурсних торгів', 'gos_tender');
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Інформація про відхилення учасників конкурсних торгів ', 'gos_tender');
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Акцепт ', 'gos_tender');
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Звіт про результати конкурсних торгів ', 'gos_tender');
INSERT INTO "public".project_file_type ("name", "group") VALUES ('Роз''яснення до документації конкурсних торгів ', 'gos_tender');
ALTER TABLE project_file ADD type_id BIGINT DEFAULT NULL;
ALTER TABLE project_file ADD CONSTRAINT FK_B50EFE08C54C8C93 FOREIGN KEY (type_id) REFERENCES project_file_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE project_file ALTER name DROP NOT NULL;
ALTER TABLE project_file ALTER create_datetime DROP NOT NULL;
INSERT INTO "public".handling_status ("name", sortorder, slug, percentagestring, progress) 	VALUES ('Cбор документов', 8, 'gos_tender', DEFAULT, NULL);
INSERT INTO "public".handling_status ("name", sortorder, slug, percentagestring, progress) 	VALUES ('Подача - раскрытие ', 9, 'gos_tender', DEFAULT, NULL);
INSERT INTO "public".handling_status ("name", sortorder, slug, percentagestring, progress) 	VALUES ('Подписание договора', 10, 'gos_tender', DEFAULT, NULL);
ALTER TABLE handling ADD reason_closed TEXT DEFAULT NULL;
COMMENT ON COLUMN handling.reason_closed IS 'Причина закрытия проекта';
ALTER TABLE project_file_type ADD alias VARCHAR(64) DEFAULT NULL;
COMMENT ON COLUMN project_file_type.alias IS 'Альтернативное название документа';
UPDATE "public".project_file_type SET "alias" = 'acceptance' WHERE id = 5;
UPDATE "public".project_file_type SET "alias" = 'protocol_open' WHERE id = 3;
ALTER TABLE project_file ADD user_id INT NOT NULL;
ALTER TABLE project_file ADD CONSTRAINT FK_B50EFE08A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_B50EFE08A76ED395 ON project_file (user_id);
-- prod +++