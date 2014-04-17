/**
 * This is a demo function using AdventureWorks2012 database
 * @SalariedFlag bit
 * @OrganizationLevel int
 */
SELECT e.JobTitle
	,e.OrganizationLevel
	,p.FirstName
	,p.MiddleName
	,p.LastName
FROM HumanResources.Employee e
INNER JOIN Person.Person p ON e.BusinessEntityID = p.BusinessEntityID
WHERE e.SalariedFlag = ?
	AND e.OrganizationLevel >= ?
ORDER BY e.BusinessEntityID
	,e.OrganizationLevel
