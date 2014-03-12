-- PREV MESSAGES

SELECT
	h.id as handlingId,
            o.name as organizationName,
            ht.name as handlingMessageTypeName,
            hm.createdate as handlingMessageCreatedate,
            hm.description as handlingMessageDescription,
            hm.user_id as handlingMessageUserId,
            CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(contact.lastName, ' '), contact.firstName), ' | '), contact.phone1), ' | '), contact.phone2)  as handlingMessageContact,
            ht1.name as handlingMessageTypeName1,
            hm1.createdate as handlingMessageCreatedate1,
            hm1.description as handlingMessageDescription1,
            hm1.user_id as handlingMessageUserId1,
            CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(contact1.lastName, ' '), contact1.firstName), ' | '), contact1.phone1), ' | '), contact1.phone2)  as handlingMessageContact1,

            ht_next.name as handlingMessageTypeNextName,
            hm_next.createdate as handlingMessageCreatedateNext,
            hm_next.description as handlingMessageDescriptionNext,
            hm_next.user_id as handlingMessageUserId1,
            CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(contact_next.lastName, ' '), contact_next.firstName), ' | '), contact_next.phone1), ' | '), contact_next.phone2)  as handlingMessageContact2,

            ht_next1.name as handlingMessageTypeNameNext1,
            hm_next1.createdate as handlingMessageCreatedateNext1,
            hm_next1.description as handlingMessageDescriptionNext1,
            hm_next1.user_id as handlingMessageUserId1,
            CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(contact1.lastName, ' '), contact1.firstName), ' | '), contact1.phone1), ' | '), contact1.phone2)  as handlingMessageContact2
FROM
	handling h
	LEFT JOIN organization o ON o.id = h.organization_id

	LEFT JOIN handling_message hm ON hm.handling_id = h.id
	LEFT JOIN handling_type ht on ht.id = hm.type_id
	LEFT JOIN model_contact contact on contact.id = hm.id


	LEFT JOIN handling_message hm1 ON hm1.handling_id = h.id
	LEFT JOIN handling_type ht1 on ht1.id = hm1.type_id
	LEFT JOIN model_contact contact1 on contact1.id = hm1.id

	LEFT JOIN handling_message hm_next ON hm_next.handling_id = h.id
	LEFT JOIN handling_type ht_next on ht_next.id = hm_next.type_id
	LEFT JOIN model_contact contact_next on contact_next.id = hm_next.id


	LEFT JOIN handling_message hm_next1 ON hm_next1.handling_id = h.id
	LEFT JOIN handling_type ht_next1 on ht_next1.id = hm_next1.type_id
	LEFT JOIN model_contact contact_next1 on contact_next1.id = hm_next1.id
WHERE
	hm.id = (SELECT 
			MAX(hm2.id) 
		FROM 
			handling_message hm2 
		WHERE 
			hm2.handling_id = h.id AND 
			(hm2.additional_type <> 'fm' OR hm2.additional_type IS NULL) AND
			hm2.createdate > '2014-01-01 11:45:00' AND 
			hm2.createdate < '2014-01-31 11:45:00'
		) AND
	hm1.id = (
		SELECT 
			MIN(hm3.id) 
		FROM 
			handling_message hm3 
		WHERE 
			hm3.handling_id = h.id AND 
			hm3.additional_type = 'fm' AND
			hm3.id > (
				SELECT 
					MAX(hm4.id) 
				FROM 
					handling_message hm4 
				WHERE 
					hm4.handling_id = h.id AND 
					(hm4.additional_type <> 'fm' OR hm4.additional_type IS NULL) AND
					hm4.createdate > '2014-01-01 11:45:00' AND 
					hm4.createdate < '2014-01-31 11:45:00'
			)
		);

-- FUTURE MESSAGE

SELECT
	h.id,
	o.name,
	h.user_id,
	ht.name,
	hm.createdate,
	hm.description,
	hm.user_id,

	ht1.name,
	hm1.createdate,
	hm1.description,
	hm1.user_id
FROM
	handling h
	LEFT JOIN organization o ON o.id = h.organization_id
	LEFT JOIN handling_message hm ON hm.handling_id = h.id
	LEFT JOIN handling_type ht on ht.id = hm.type_id
	LEFT JOIN handling_message hm1 ON hm1.handling_id = h.id
	LEFT JOIN handling_type ht1 on ht1.id = hm1.type_id

WHERE
	hm.id = (SELECT
			MAX(hm2.id)
		FROM
			handling_message hm2
		WHERE
			hm2.handling_id = h.id AND
			(hm2.additional_type <> 'fm' OR hm2.additional_type IS NULL) AND
			hm2.id < (
				SELECT
					MAX(hm4.id)
				FROM
					handling_message hm4
				WHERE
					hm4.handling_id = h.id AND
					hm4.additional_type = 'fm' AND
					hm4.createdate > '2014-01-01 11:45:00' AND
					hm4.createdate < '2014-01-31 11:45:00'
			)
		) AND
	hm1.id = (
		SELECT
			MAX(hm3.id)
		FROM
			handling_message hm3
		WHERE
			hm3.handling_id = h.id AND
			hm3.additional_type = 'fm' AND
			hm3.createdate > '2014-01-01 11:45:00' AND
			hm3.createdate < '2014-01-31 11:45:00'
		)

-- LAST MESSAGES

SELECT
	h.id,
	o.name,
	h.user_id,
	ht.name,
	hm.createdate,
	hm.description,
	hm.user_id,

	ht1.name,
	hm1.createdate,
	hm1.description,
	hm1.user_id
FROM
	handling h
	LEFT JOIN organization o ON o.id = h.organization_id
	LEFT JOIN handling_message hm ON hm.handling_id = h.id
	LEFT JOIN handling_type ht on ht.id = hm.type_id
	LEFT JOIN handling_message hm1 ON hm1.handling_id = h.id
	LEFT JOIN handling_type ht1 on ht1.id = hm1.type_id

WHERE
	hm.id = (SELECT
			MAX(hm2.id)
		FROM
			handling_message hm2
		WHERE
			hm2.handling_id = h.id AND
			(hm2.additional_type <> 'fm' OR hm2.additional_type IS NULL) AND
			hm2.id < (
				SELECT
					MAX(hm4.id)
				FROM
					handling_message hm4
				WHERE
					hm4.handling_id = h.id AND
					hm4.additional_type = 'fm'
			)
		) AND
	hm1.id = (
		SELECT
			MAX(hm3.id)
		FROM
			handling_message hm3
		WHERE
			hm3.handling_id = h.id AND
			hm3.additional_type = 'fm'
		)

-- DQL FOR PREV
SELECT
            h.id as handlingId,
            o.name as organizationName,
            ht.name as handlingMessageTypeName,
            hm.createdate as handlingMessageCreatedate,
            hm.description as handlingMessageDescription,
            hm.user_id as handlingMessageUserId,
            ht1.name as handlingMessageTypeName1,
            hm1.createdate as handlingMessageCreatedate1,
            hm1.description as handlingMessageDescription1,
            hm1.user_id as handlingMessageUserId1
        FROM
            ListsHandlingBundle:Handling h
            LEFT JOIN h.organization o
            LEFT JOIN h.HandlingMessages hm
            LEFT JOIN hm.type ht
            LEFT JOIN h.HandlingMessages hm1
            LEFT JOIN hm.type ht1

        WHERE
            hm.id = (SELECT
                    MAX(hm2.id)
                FROM
                    ListsHandlingBundle:HandlingMessage hm2
                WHERE
                    hm2.handling_id = h.id AND
                    (hm2.additionalType <> 'fm' OR hm2.additionalType IS NULL) AND
                    hm2.createdate > '2014-01-01 11:45:00' AND
                    hm2.createdate < '2014-01-31 11:45:00'
                ) AND
            hm1.id = (
                SELECT
                    MIN(hm3.id)
                FROM
                    ListsHandlingBundle:HandlingMessage hm3
                WHERE
                    hm3.handling_id = h.id AND
                    hm3.additionalType = 'fm' AND
                    hm3.id > (
                        SELECT
                            MAX(hm4.id)
                        FROM
                            ListsHandlingBundle:HandlingMessage hm4
                        WHERE
                            hm4.handling_id = h.id AND
                            (hm4.additionalType <> 'fm' OR hm4.additionalType IS NULL) AND
                            hm4.createdate > '2014-01-01 11:45:00' AND
                            hm4.createdate < '2014-01-31 11:45:00'
                    )
                )

-- DQL FOR LAST MESSAGES
SELECT
            h.id as handlingId,
            o.name as organizationName,
            ht.name as handlingMessageTypeName,
            hm.createdate as handlingMessageCreatedate,
            hm.description as handlingMessageDescription,
            hm.user_id as handlingMessageUserId,
            ht1.name as handlingMessageTypeName1,
            hm1.createdate as handlingMessageCreatedate1,
            hm1.description as handlingMessageDescription1,
            hm1.user_id as handlingMessageUserId1
        FROM
            ListsHandlingBundle:Handling h
            LEFT JOIN h.organization o
            LEFT JOIN h.HandlingMessages hm
            LEFT JOIN hm.type ht
            LEFT JOIN h.HandlingMessages hm1
            LEFT JOIN hm.type ht1

        WHERE
            hm.id = (SELECT
                    MAX(hm2.id)
                FROM
                    ListsHandlingBundle:HandlingMessage hm2
                WHERE
                    hm2.handling_id = h.id AND
                    (hm2.additionalType <> 'fm' OR hm2.additionalType IS NULL) AND
                    hm2.id < (
                        SELECT
                            MAX(hm4.id)
                        FROM
                            ListsHandlingBundle:HandlingMessage hm4
                        WHERE
                            hm4.handling_id = h.id AND
                            hm4.additionalType = 'fm'
                    )
                ) AND
            hm1.id = (
                SELECT
                    MAX(hm3.id)
                FROM
                    ListsHandlingBundle:HandlingMessage hm3
                WHERE
                    hm3.handling_id = h.id AND
                    hm3.additionalType = 'fm'
                )

----------------------------

SELECT
  h0_.id AS id0,
  o1_.name AS name1,
  h2_.name AS name2,
  h3_.createdate AS createdate3,
  h3_.description AS description4,
  h3_.user_id AS user_id5,
  m4_.last_name || ' ' || m4_.first_name || ' | ' || m4_.phone1 || ' | ' || m4_.phone2 AS sclr6,
  h5_.name AS name7,
  h6_.createdate AS createdate8,
  h6_.description AS description9,
  h6_.user_id AS user_id10,
  m7_.last_name || ' ' || m7_.first_name || ' | ' || m7_.phone1 || ' | ' || m7_.phone2 AS sclr11,
  h8_.name AS name12,
  h9_.createdate AS createdate13,
  h9_.description AS description14,
  h9_.user_id AS user_id15,
  m10_.last_name || ' ' || m10_.first_name || ' | ' || m10_.phone1 || ' | ' || m10_.phone2 AS sclr16,
  h11_.name AS name17,
  h12_.createdate AS createdate18,
  h12_.description AS description19,
  h12_.user_id AS user_id20,
  m13_.last_name || ' ' || m13_.first_name || ' | ' || m13_.phone1 || ' | ' || m13_.phone2 AS sclr21
FROM
  handling h0_
  LEFT JOIN organization o1_ ON h0_.organization_id = o1_.id
  LEFT JOIN handling_message h3_ ON h0_.id = h3_.handling_id
  LEFT JOIN handling_message_type h2_ ON h3_.type_id = h2_.id
  LEFT JOIN model_contact m4_ ON h3_.contact_id = m4_.id
  LEFT JOIN handling_message h6_ ON h0_.id = h6_.handling_id
  LEFT JOIN handling_message_type h5_ ON h6_.type_id = h5_.id
  LEFT JOIN model_contact m7_ ON h6_.contact_id = m7_.id
  LEFT JOIN handling_message h9_ ON h0_.id = h9_.handling_id
  LEFT JOIN handling_message_type h8_ ON h9_.type_id = h8_.id
  LEFT JOIN model_contact m10_ ON h9_.contact_id = m10_.id
  LEFT JOIN handling_message h12_ ON h0_.id = h12_.handling_id
  LEFT JOIN handling_message_type h11_ ON h12_.type_id = h11_.id
  LEFT JOIN model_contact m13_ ON h12_.contact_id = m13_.id
WHERE
  h3_.id = (
    SELECT
      MAX(h14_.id) AS dctrn__1
    FROM
      handling_message h14_
    WHERE h14_.handling_id = h0_.id AND
      (h14_.additional_type <> 'fm' OR h14_.additional_type IS NULL) AND
      h14_.user_id = 303 AND
      h14_.createdate >= '2014-02-01 00:00:00' AND
      h14_.createdate <= '2014-02-04 00:00:00'
  ) AND
  h6_.id = (
    SELECT
      MIN(h15_.id) AS dctrn__2
    FROM
      handling_message h15_
    WHERE
      h15_.handling_id = h0_.id AND
      h15_.additional_type = 'fm' AND
      h15_.user_id = 303 AND
      h15_.id > (
        SELECT
          MAX(h16_.id) AS dctrn__3
        FROM
          handling_message h16_
        WHERE h16_.handling_id = h0_.id AND
         (h16_.additional_type <> 'fm' OR h16_.additional_type IS NULL) AND
          h16_.user_id = 303 AND
          h16_.createdate >= '2014-02-01 00:00:00' AND
          h16_.createdate <= '2014-02-04 00:00:00'
      )
  ) AND
  h9_.id = (
    SELECT
      MAX(h17_.id) AS dctrn__4
    FROM
      handling_message h17_
    WHERE h17_.handling_id = h0_.id AND
      (h17_.additional_type <> 'fm' OR h17_.additional_type IS NULL) AND
      h17_.user_id = 303 AND
      h17_.id < (
        SELECT
          MAX(h18_.id) AS dctrn__5
        FROM
          handling_message h18_
        WHERE
          h18_.handling_id = h0_.id AND
          h18_.additional_type = 'fm' AND
          h18_.user_id = 303 AND
          h18_.createdate >= '2014-02-01 00:00:00' AND
          h18_.createdate <= '2014-02-04 00:00:00'
      )
  ) AND
  h12_.id = (
    SELECT
      MAX(h19_.id) AS dctrn__6
    FROM
      handling_message h19_
    WHERE
      h19_.handling_id = h0_.id AND
      h19_.additional_type = 'fm' AND
      h19_.user_id = 303 AND
      h19_.createdate >= '2014-02-01 00:00:00' AND
      h19_.createdate <= '2014-02-04 00:00:00'
  )
ORDER BY h6_.createdate DESC