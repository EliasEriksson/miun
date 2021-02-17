-- kommer ej slösa en >= 1 byte på att ha varchar som ID
drop database if exists veterinary;
create database veterinary;
use veterinary;


create table addresses
(
    id           integer,
    state        varchar(15),
    zip          integer,
    city         varchar(64),
    streetNumber integer,
    streetName   varchar(64)
);


create table customers
(
    id             integer,
    addressID      integer,
    customerTypeID integer,
    -- https://stackoverflow.com/questions/30485/what-is-a-reasonable-length-limit-on-person-name-fields#comment48518238_30509
    name           varchar(80),
    --  https://stackoverflow.com/a/4729239
    phoneNumber    varchar(15),
    faxNumber      varchar(15)
);


create table customerTypes
(
    id   integer,
    -- private or company
    type varchar(7)
);



create table employees
(
    id                integer,
    addressID         integer,
    -- https://stackoverflow.com/questions/30485/what-is-a-reasonable-length-limit-on-person-name-fields#comment48518238_30509
    firstName         varchar(54),
    lastName          varchar(54),
    -- https://stackoverflow.com/a/4729239
    phoneNumber       varchar(15),
    hireDate          date,
    educationalDegree varchar(256)
);


create table pets
(
    id          integer,
    ownerID     integer,
    breedID     integer,
    -- https://stackoverflow.com/questions/30485/what-is-a-reasonable-length-limit-on-person-name-fields#comment48518238_30509
    name        varchar(54),
    -- M or F
    gender      char(1),
    dateOfBirth date
);

create table breeds
(
    id        integer,
    petTypeID integer,
    -- guessing 64 is enough
    breed     varchar(64)
);


create table petTypes
(
    id   integer,
    -- guessing 64 is enough
    type varchar(64)
);

create table treatments
(
    id    integer,
    -- not really sure what could go in here as im not a veterinary myself
    name  varchar(256),
    price integer
);

create table visits
(
    id          integer,
    petID       integer,
    employeeID  integer,
    dateOfVisit date
);

create table preformedTreatments
(
    id          integer,
    visitID     integer,
    treatmentID integer
);


-- primary keys
alter table addresses
    add constraint addressesPK primary key (id);
alter table addresses
    modify id integer not null auto_increment;

alter table customers
    add constraint customersPK primary key (id);
alter table customers
    modify id integer not null auto_increment;

alter table customerTypes
    add constraint customerTypesPK primary key (id);
alter table customerTypes
    modify id integer not null auto_increment;

alter table employees
    add constraint employeesPK primary key (id);
alter table employees
    modify id integer not null auto_increment;

alter table pets
    add constraint petsPK primary key (id);
alter table pets
    modify id integer not null auto_increment;

alter table breeds
    add constraint breedsPK primary key (id);
alter table breeds
    modify id integer not null auto_increment;

alter table petTypes
    add constraint petTypesPK primary key (id);
alter table petTypes
    modify id integer not null auto_increment;

alter table treatments
    add constraint treatmentsPK primary key (id);
alter table treatments
    modify id integer not null auto_increment;

alter table visits
    add constraint visitsPK primary key (id);
alter table visits
    modify id integer not null auto_increment;

alter table preformedTreatments
    add constraint preformedTreatmentsPK primary key (id);
alter table preformedTreatments
    modify id integer not null auto_increment;


-- foreign keys
alter table employees
    add constraint employeesAddressFK foreign key (addressID) references addresses (id);

alter table customers
    add constraint customersAddressFK foreign key (addressID) references addresses (id);
alter table customers
    add constraint customersTypeFK foreign key (customerTypeID) references customerTypes (id);

alter table visits
    add constraint visitsPetFK foreign key (petID) references pets (id);
alter table visits
    add constraint visitsEmployeeFK foreign key (employeeID) references employees (id);

alter table preformedTreatments
    add constraint preformedTreatmentsVisitFK foreign key (visitID) references visits (id);
alter table preformedTreatments
    add constraint preformedTreatmentsTreatmentFK foreign key (treatmentID) references treatments (id);

alter table pets
    add constraint petsOwnerFK foreign key (ownerID) references customers (id);
alter table pets
    add constraint petsBreedFK foreign key (breedID) references breeds (id);

alter table breeds
    add constraint breedsPetType foreign key (petTypeID) references petTypes (id);
