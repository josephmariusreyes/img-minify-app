-- CREATE TABLE lookup.upload_status (
--     id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
--     name VARCHAR(100) NOT NULL,
--     value VARCHAR(50) NOT NULL UNIQUE
-- );

-- INSERT INTO lookup.upload_status (name, value)
-- VALUES
--     ('Pending', 'PENDING'),
--     ('Processing', 'PROCESSING'),
--     ('Completed', 'COMPLETED'),
--     ('Failed', 'FAILED');

-- ALTER TABLE public.uploads
-- ADD CONSTRAINT fk_uploads_upload_status
-- FOREIGN KEY (upload_status)
-- REFERENCES lookup.upload_status (value);
