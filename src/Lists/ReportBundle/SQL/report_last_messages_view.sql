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