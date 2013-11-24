ALTER TABLE companystructure
  ADD CONSTRAINT griffinstructure_parent_id_griffinstructure_id FOREIGN KEY (parent_id)
      REFERENCES companystructure (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION;