use veterinary;

-- selects employee 1 joined with its address without the reference key
select *
from (
    select employees.id, firstName, lastName, phoneNumber, hireDate, educationalDegree, state, zip, city, streetNumber, streetName
    from employees
    join addresses on employees.addressID = addresses.id
) as employee where employee.id = 1;

-- selects all pets whose name contains 'll' somewhere
select name
from pets
where name like '%ll%';

-- selects all visits that have occurred after the year of 2017
select *
from visits
where str_to_date('2018', '%Y') < dateOfVisit;

-- counts how many visits occurred during 2017
select count(*)
from visits
where str_to_date('2017', '%Y') <= visits.dateOfVisit and visits.dateOfVisit < str_to_date('2018', '%Y');

-- counts how many an animals of each gender
select gender, count(gender)
from pets
group by gender;

-- changes the last name of employee 0 to 'andersson'
update employees set lastName = 'andersson' where id = 0;