create table regions
(
    id   int auto_increment not null,
    code char(2),
    name varchar(255),

    constraint regionsPK primary key (id)
);

create table owners
(
    id   int auto_increment not null,
    name varchar(255),

    constraint ownersPK primary key (id)
);

create table restaurants
(
    id       int auto_increment not null,
    regionID int,
    capacity int,

    constraint restaurantsPK primary key (id)
);

create table ownersRestaurants
(
    id           int auto_increment not null,
    ownerID      int,
    restaurantID int,

    constraint ownersRestaurantsPK primary key (id)
);

create table desertCategories
(
    id   int auto_increment not null,
    desertID int,
    name varchar(32),

    constraint desertCategoriesPK primary key (id)

);

create table offers
(
    id           int auto_increment not null,
    desertID     int,
    restaurantID int,
    offerStart   timestamp,
    offerEnd     timestamp,

    constraint offerTimeRange check ( offerStart < offers.offerEnd ),
    constraint offersPK primary key (id)
);

create table toppings
(
    id   int auto_increment not null,
    name varchar(255),

    constraint toppingsPK primary key (id)
);

create table deserts
(
    id          int auto_increment not null,
    description text,
    toppingID   int,
    price       int,

    constraint desertsPK primary key (id)
);

create table drinks
(
    desertID int not null,
    name     varchar(255),

    constraint drinksPK primary key (desertID)
);

create table booths
(
    id           int auto_increment not null,
    restaurantID int,
    allowSmoke   bool,
    capacity     int,

    constraint boothPK primary key (id)
);

create table tableBooths
(
    boothID int not null,
    code    char(1),

    constraint tableBoothsPK primary key (boothID)
);

create table benchBooths
(
    boothID int auto_increment not null,
    code    char(1),

    constraint benchBoothsPK primary key (boothID)
);

create table bills
(
    id         int auto_increment not null,
    boothID    int,
    occurrence timestamp,
    tips       int,

    constraint billsPK primary key (id)
);

create table billedDeserts
(
    id       int auto_increment not null,
    billID   int,
    desertID int,

    constraint billedDesertsPK primary key (id)
);

alter table restaurants
    add constraint restaurantsRegionFK
        foreign key (regionID)
            references regions (id)
            on delete cascade;

alter table ownersRestaurants
    add constraint ownersRestaurantsOwnerFK
        foreign key (ownerID)
            references owners (id)
            on delete cascade;

alter table ownersRestaurants
    add constraint ownersRestaurantsRestaurantFK
        foreign key (restaurantID)
            references restaurants (id)
            on delete cascade;

alter table offers
    add constraint offersDesertFK
        foreign key (desertID)
            references deserts (id)
            on delete cascade;

alter table offers
    add constraint offersRestaurantFK
        foreign key (restaurantID)
            references restaurants (id)
            on delete cascade;

alter table desertCategories
    add constraint desertCategoriesDesert
        foreign key (desertID)
            references restaurants (id)
            on delete cascade;

alter table deserts
    add constraint desertsToppingFK
        foreign key (toppingID)
            references toppings (id)
            on delete cascade;

alter table drinks
    add constraint drinksDesertFK
        foreign key (desertID)
            references deserts (id)
            on delete cascade;

alter table booths
    add constraint boothsRestaurantFK
        foreign key (restaurantID)
            references restaurants (id)
            on delete cascade;

alter table tableBooths
    add constraint tableBoothsBoothFK
        foreign key (boothID)
            references booths (id)
            on delete cascade;

alter table benchBooths
    add constraint benchBoothsBoothFK
        foreign key (boothID)
            references booths (id)
            on delete cascade;

alter table bills
    add constraint billsBoothFK
        foreign key (boothID)
            references booths (id)
            on delete cascade;

alter table billedDeserts
    add constraint billedDesertsBillFK
        foreign key (billID)
            references bills (id)
            on delete cascade;

alter table billedDeserts
    add constraint billedDesertsDesertsFK
        foreign key (desertID)
            references deserts (id)
            on delete cascade;
