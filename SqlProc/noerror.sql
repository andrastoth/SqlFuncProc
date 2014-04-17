/**
 * This is a demo procedure using AdventureWorks2012 database
 * return without error code
 * @params id int
 */
IF OBJECT_ID('dbo.test') IS NOT NULL DROP TABLE dbo.test;
SELECT * INTO dbo.test FROM HumanResources.Employee WHERE BusinessEntityID > ?;