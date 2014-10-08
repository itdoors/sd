SELECT
	hm.id AS id,
	hm.handling_id AS handling_id,
	hm.createdate AS createdate,
	hmt.name AS typeName,
	hmt.slug AS typeSlug,
	hmt.stayactiontime AS typeStayactiontime,
	u.last_name || ' ' || u.first_name AS userFullName,
	select_next_handling_message_date(hm.id, hm.handling_id) AS nextCreatedate
FROM
	handling_message hm
	LEFT JOIN fos_user u ON hm.user_id = u.id
	LEFT JOIN handling_message_type hmt ON hm.type_id = hmt.id
WHERE
	hm.createdate >= '2014-02-24 00:00:00' AND hm.createdate <= '2014-04-07 00:00:00'

----------------------

DROP VIEW IF EXISTS handling_message_view;

CREATE VIEW handling_message_view AS
SELECT
	hm.id AS id,
	hm.handling_id AS handling_id,
	hm.createdate AS createdate,
	hmt.name AS type_name,
	hmt.slug AS type_slug,
	hmt.stayactiontime AS type_stayactiontime,
	hm.user_id AS user_id,
	hm.additional_type as additional_type,
	u.last_name || ' ' || u.first_name AS user_full_name
FROM
	handling_message hm
	LEFT JOIN fos_user u ON hm.user_id = u.id
	LEFT JOIN handling_message_type hmt ON hm.type_id = hmt.id;

----------------------

DROP VIEW IF EXISTS handling_message_report_view;

CREATE VIEW handling_message_report_view AS
SELECT
	hm.id AS id,
	hm.handling_id AS handling_id,
	hm.createdate AS createdate,
	hmt.name AS type_name,
	hmt.slug AS type_slug,
	hmt.stayactiontime AS type_stayactiontime,
	hm.user_id AS user_id,
	hm.additional_type as additional_type,
	u.last_name || ' ' || u.first_name AS user_full_name,
	select_next_handling_message_date(hm.id, hm.handling_id) AS next_createdate
FROM
	handling_message hm
	LEFT JOIN fos_user u ON hm.user_id = u.id
	LEFT JOIN handling_message_type hmt ON hm.type_id = hmt.id;

-------------------------

DROP FUNCTION select_next_handling_message_date(bigint, bigint);

CREATE OR REPLACE FUNCTION select_next_handling_message_date(hmid bigint, hid bigint)
  RETURNS timestamp without time zone AS
$BODY$
DECLARE
	result timestamp;
BEGIN
	SELECT
	    hm2.createdate
	INTO result
        FROM
	    handling_message hm2
	WHERE
	    hm2.handling_id = hId AND
	    hm2.id > hmId AND
	    hm2.additional_type <> 'fm'
	ORDER BY
	    hm2.id ASC
	limit 1;
	RETURN result;
END$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
