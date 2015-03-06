CREATE TABLE sd_claim (id SERIAL NOT NULL, customer_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, importance VARCHAR(50) NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, closedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, text TEXT NOT NULL, disabled BOOLEAN NOT NULL, discr VARCHAR(255) NOT NULL, targetIndividual_id BIGINT DEFAULT NULL, targetDepartment_id BIGINT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_A497B2F49395C3F3 ON sd_claim (customer_id);
CREATE INDEX IDX_A497B2F4A750E600 ON sd_claim (targetIndividual_id);
CREATE INDEX IDX_A497B2F4A7F70FD2 ON sd_claim (targetDepartment_id);
CREATE TABLE claim_claim_performer_rule (claim_id INT NOT NULL, claim_performer_rule_id INT NOT NULL, PRIMARY KEY(claim_id, claim_performer_rule_id));
CREATE INDEX IDX_C9B7EB757096A49F ON claim_claim_performer_rule (claim_id);
CREATE INDEX IDX_C9B7EB75BA809BC5 ON claim_claim_performer_rule (claim_performer_rule_id);
CREATE TABLE sd_claim_message (id SERIAL NOT NULL, user_id INT DEFAULT NULL, claim_id INT DEFAULT NULL, text TEXT NOT NULL, is_visible BOOLEAN NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_8BFA8A96A76ED395 ON sd_claim_message (user_id);
CREATE INDEX IDX_8BFA8A967096A49F ON sd_claim_message (claim_id);
CREATE TABLE sd_claim_performer_rule (id SERIAL NOT NULL, performer_id INT DEFAULT NULL, claim_id INT DEFAULT NULL, can_edit_finance_data BOOLEAN NOT NULL, can_post_to_clients BOOLEAN NOT NULL, claim_updated BOOLEAN NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_6A0F72AB6C6B33F3 ON sd_claim_performer_rule (performer_id);
CREATE INDEX IDX_6A0F72AB7096A49F ON sd_claim_performer_rule (claim_id);
CREATE TABLE sd_business_role (id SERIAL NOT NULL, individual_id BIGINT DEFAULT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_AB4EDDB0AE271C0D ON sd_business_role (individual_id);
CREATE TABLE granted_organizations_for_client (client_id INT NOT NULL, organization_id BIGINT NOT NULL, PRIMARY KEY(client_id, organization_id));
CREATE INDEX IDX_64E72DAD19EB6921 ON granted_organizations_for_client (client_id);
CREATE INDEX IDX_64E72DAD32C8A3DE ON granted_organizations_for_client (organization_id);
CREATE TABLE client_departments_for_order (client_id INT NOT NULL, department_id BIGINT NOT NULL, PRIMARY KEY(client_id, department_id));
CREATE INDEX IDX_AC799AFF19EB6921 ON client_departments_for_order (client_id);
CREATE INDEX IDX_AC799AFFAE80F5DF ON client_departments_for_order (department_id);
CREATE TABLE origin_organizations_for_client (client_id INT NOT NULL, organization_id BIGINT NOT NULL, PRIMARY KEY(client_id, organization_id));
CREATE INDEX IDX_73953D1219EB6921 ON origin_organizations_for_client (client_id);
CREATE INDEX IDX_73953D1232C8A3DE ON origin_organizations_for_client (organization_id);
CREATE TABLE responsibility (id SERIAL NOT NULL, stuff_id INT DEFAULT NULL, department_id BIGINT DEFAULT NULL, discr VARCHAR(255) NOT NULL, claimTypes TEXT DEFAULT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_694E8A08950A1740 ON responsibility (stuff_id);
CREATE INDEX IDX_694E8A08AE80F5DF ON responsibility (department_id);
COMMENT ON COLUMN responsibility.claimTypes IS '(DC2Type:array)';
COMMENT ON TABLE sd_claim IS 'ServiceDeskBundle:Claim';
COMMENT ON TABLE sd_claim_performer_rule IS 'Rule for performer to access claim features';
COMMENT ON TABLE granted_organizations_for_client IS 'Client can add claims only for these organizations/departments';
COMMENT ON TABLE client_departments_for_order IS 'Departments allowed to client for order';
COMMENT ON TABLE claim_claim_performer_rule IS 'Binding Performer"s rules to concrete claim';
COMMENT ON TABLE sd_business_role IS 'Various types of individual"s possibilities';
COMMENT ON TABLE origin_organizations_for_client IS 'Organizations of client"s origin';
COMMENT ON TABLE responsibility IS 'Responsibilities assigned to staff';

ALTER TABLE sd_claim ADD CONSTRAINT FK_A497B2F49395C3F3 FOREIGN KEY (customer_id) REFERENCES sd_business_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim ADD CONSTRAINT FK_A497B2F4A750E600 FOREIGN KEY (targetIndividual_id) REFERENCES individual (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim ADD CONSTRAINT FK_A497B2F4A7F70FD2 FOREIGN KEY (targetDepartment_id) REFERENCES departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE claim_claim_performer_rule ADD CONSTRAINT FK_C9B7EB757096A49F FOREIGN KEY (claim_id) REFERENCES sd_claim (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE claim_claim_performer_rule ADD CONSTRAINT FK_C9B7EB75BA809BC5 FOREIGN KEY (claim_performer_rule_id) REFERENCES sd_claim_performer_rule (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim_message ADD CONSTRAINT FK_8BFA8A96A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim_message ADD CONSTRAINT FK_8BFA8A967096A49F FOREIGN KEY (claim_id) REFERENCES sd_claim (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim_performer_rule ADD CONSTRAINT FK_6A0F72AB6C6B33F3 FOREIGN KEY (performer_id) REFERENCES sd_business_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim_performer_rule ADD CONSTRAINT FK_6A0F72AB7096A49F FOREIGN KEY (claim_id) REFERENCES sd_claim (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_business_role ADD CONSTRAINT FK_AB4EDDB0AE271C0D FOREIGN KEY (individual_id) REFERENCES individual (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE granted_organizations_for_client ADD CONSTRAINT FK_64E72DAD19EB6921 FOREIGN KEY (client_id) REFERENCES sd_business_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE granted_organizations_for_client ADD CONSTRAINT FK_64E72DAD32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE client_departments_for_order ADD CONSTRAINT FK_AC799AFF19EB6921 FOREIGN KEY (client_id) REFERENCES sd_business_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE client_departments_for_order ADD CONSTRAINT FK_AC799AFFAE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE origin_organizations_for_client ADD CONSTRAINT FK_73953D1219EB6921 FOREIGN KEY (client_id) REFERENCES sd_business_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE origin_organizations_for_client ADD CONSTRAINT FK_73953D1232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE responsibility ADD CONSTRAINT FK_694E8A08950A1740 FOREIGN KEY (stuff_id) REFERENCES sd_business_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE responsibility ADD CONSTRAINT FK_694E8A08AE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE fos_user ADD individual_id BIGINT DEFAULT NULL;
ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479AE271C0D FOREIGN KEY (individual_id) REFERENCES individual (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE UNIQUE INDEX UNIQ_957A6479AE271C0D ON fos_user (individual_id);

CREATE TABLE individual_contacts (id BIGSERIAL NOT NULL, individual_id BIGINT DEFAULT NULL, type VARCHAR(50) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_A070366FAE271C0D ON individual_contacts (individual_id);
ALTER TABLE individual_contacts ADD CONSTRAINT FK_A070366FAE271C0D FOREIGN KEY (individual_id) REFERENCES individual (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE sd_business_role ADD companystructure_id BIGINT DEFAULT NULL;
ALTER TABLE sd_business_role ADD CONSTRAINT FK_AB4EDDB062580AED FOREIGN KEY (companystructure_id) REFERENCES companystructure (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_AB4EDDB062580AED ON sd_business_role (companystructure_id);

ALTER TABLE file ADD CONSTRAINT FK_8C9F36107096A49F FOREIGN KEY (claim_id) REFERENCES sd_claim (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE file ADD CONSTRAINT FK_8C9F36108D7ECC77 FOREIGN KEY (claim_message__id) REFERENCES sd_claim_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE sd_claim ADD importance_id BIGINT DEFAULT NULL;
ALTER TABLE sd_claim DROP importance;
ALTER TABLE sd_claim ALTER type TYPE VARCHAR(50);
ALTER TABLE sd_claim ALTER status TYPE VARCHAR(50);
ALTER TABLE sd_claim ADD CONSTRAINT FK_A497B2F44C3BBD10 FOREIGN KEY (importance_id) REFERENCES importance (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_A497B2F44C3BBD10 ON sd_claim (importance_id);
ALTER TABLE organization_importance ADD CONSTRAINT FK_A3C22F764C3BBD10 FOREIGN KEY (importance_id) REFERENCES importance (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE organization_importance ADD CONSTRAINT FK_A3C22F7632C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE file ADD description VARCHAR(255);
ALTER TABLE sd_claim_message RENAME COLUMN is_visible TO staffOnly;

ALTER TABLE sd_claim ADD mpk VARCHAR(255) DEFAULT NULL;
ALTER TABLE sd_claim ADD scale VARCHAR(255) DEFAULT NULL;
ALTER TABLE sd_claim ADD statusLastModified TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE sd_claim ADD self_org_id BIGINT DEFAULT NULL;
ALTER TABLE sd_claim ADD CONSTRAINT FK_A497B2F4786D540 FOREIGN KEY (self_org_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_A497B2F4786D540 ON sd_claim (self_org_id);

CREATE TABLE sd_claim_finance (id SERIAL NOT NULL, claim_id INT DEFAULT NULL, costs_n DOUBLE PRECISION NOT NULL, costs_nds DOUBLE PRECISION NOT NULL, costs_nonnds DOUBLE PRECISION NOT NULL, income_nds DOUBLE PRECISION NOT NULL, income_nonnds DOUBLE PRECISION NOT NULL, bill_number VARCHAR(100) DEFAULT NULL, profitability DOUBLE PRECISION NOT NULL, nds DOUBLE PRECISION NOT NULL, obnal DOUBLE PRECISION NOT NULL, is_closed BOOLEAN NOT NULL, work VARCHAR(255) DEFAULT NULL, mpk VARCHAR(50) DEFAULT NULL, payment_type VARCHAR(10) DEFAULT NULL, costs_beznalnonnds DOUBLE PRECISION NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_F36F50097096A49F ON sd_claim_finance (claim_id);
ALTER TABLE sd_claim_finance ADD CONSTRAINT FK_F36F50097096A49F FOREIGN KEY (claim_id) REFERENCES sd_claim (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

alter table sd_claim_finance drop profitability;

CREATE TABLE CostNal (id SERIAL NOT NULL, finance_record_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_9E4779E2E2A4F20 ON CostNal (finance_record_id);
ALTER TABLE CostNal ADD CONSTRAINT FK_9E4779E2E2A4F20 FOREIGN KEY (finance_record_id) REFERENCES sd_claim_finance (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE sd_claim_finance DROP costs_n;

ALTER TABLE sd_claim_finance ADD status VARCHAR(50) DEFAULT NULL;
ALTER TABLE sd_claim_finance ADD statusLastModified TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;