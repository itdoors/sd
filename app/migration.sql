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
-- staging ----
-- prod ----