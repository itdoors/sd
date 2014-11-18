CREATE SEQUENCE geo_city_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE geo_district_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE geo_region_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE geo_city (id INT NOT NULL, region_id INT DEFAULT NULL, district_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, long DOUBLE PRECISION NOT NULL, lat DOUBLE PRECISION NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_297C2D3498260155 ON geo_city (region_id);
CREATE INDEX IDX_297C2D34B08FA272 ON geo_city (district_id);
CREATE TABLE geo_district (id INT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_DF78232698260155 ON geo_district (region_id);
CREATE TABLE geo_region (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));

alter table geo_city drop column id;
alter table geo_city add column id serial;

alter table geo_district drop column id;
alter table geo_district add column id serial;

alter table geo_region drop column id;
alter table geo_region add column id serial;

insert into geo_region(name)
select distinct my_geo.obl from my_geo
order by my_geo.obl;

insert into geo_district(name, region_id)
select distinct my_geo.reg, geo_region.id from
my_geo left join geo_region on
my_geo.obl = geo_region.name
order by my_geo.reg;

insert into geo_city(name, district_id, region_id, long, lat)
select distinct my_geo.city, geo_district.id, geo_district.region_id, my_geo.long, my_geo.lat from
my_geo left join geo_district on
my_geo.reg = geo_district.name
where my_geo.city is not null
order by my_geo.city;