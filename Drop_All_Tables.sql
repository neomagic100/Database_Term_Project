SET FOREIGN_KEY_CHECKS = 0;

-- 
SELECT CONCAT('DROP TABLE IF EXISTS `', table_name, '`;')
FROM information_schema.tables
WHERE table_schema = 'UniversityEvents';
-- 
-- After generated, replace above with list generated, then 

SET FOREIGN_KEY_CHECKS = 1;