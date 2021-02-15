-- kommer ej slösa en >= 1 byte på att ha varchar som ID
create database veterinary;
use veterinary;


create table addresses
(
    id           integer not null,
    state        varchar(15),
    zip          integer,
    city         varchar(64),
    streetNumber integer,
    streetName   varchar(64)
);


create table customers
(
    id             integer not null,
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
    id   integer not null,
    -- private or company
    type varchar(7)
);



create table employees
(
    id          integer not null,
    addressID   integer,
    -- https://stackoverflow.com/questions/30485/what-is-a-reasonable-length-limit-on-person-name-fields#comment48518238_30509
    firstName   varchar(54),
    lastName    varchar(54),
    -- https://stackoverflow.com/a/4729239
    phoneNumber varchar(15),
    hireDate    date
    -- TODO add degree
);


create table pets
(
    id          integer not null,
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
    id        integer not null,
    petTypeID integer,
    -- guessing 64 is enough
    breed     varchar(64)
);


create table petTypes
(
    id   integer not null,
    -- guessing 64 is enough
    type varchar(64)
);

create table treatments
(
    id    integer not null,
    -- not really sure what could go in here as im not a veterinary myself
    name  varchar(256),
    price integer
);

create table visits
(
    id          integer not null,
    petID       integer,
    employeeID  integer,
    dateOfVisit date
);

create table preformedTreatments
(
    id          integer not null,
    visitID     integer,
    treatmentID integer
);


-- primary keys
alter table addresses
    add constraint addressesPK primary key (id);
alter table customers
    add constraint customersPK primary key (id);
alter table customerTypes
    add constraint customerTypesPK primary key (id);
alter table employees
    add constraint employeesPK primary key (id);
alter table pets
    add constraint petsPK primary key (id);
alter table breeds
    add constraint breedsPK primary key (id);
alter table petTypes
    add constraint petTypesPK primary key (id);
alter table treatments
    add constraint treatmentsPK primary key (id);
alter table visits
    add constraint visitsPK primary key (id);
alter table preformedTreatments
    add constraint preformedTreatmentsPK primary key (id);


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
