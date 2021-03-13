drop database if exists web2mom4;
create database web2mom4;
use web2mom4;

create table users
(
    id           int auto_increment  not null,
    email        varchar(255) unique not null,
    passwordHash varchar(255),
    url          char(13) unique,
    constraint usersPK primary key (id)
);

create table userProfiles
(
    userID      int          not null,
    firstName   varchar(255),
    lastName    varchar(255),
    avatar      varchar(255) null,
    description text,

    -- weak check to at least make sure that these are part of the paths somewhere
    constraint avatarPath check ( avatar like '%writeable/web2mom4/media/avatars%'),
    constraint userProfilePK primary key (userID)
);

create table clucks
(
    id         int auto_increment not null,
    userID     int                not null,
    title      varchar(128),
    content    varchar(250),
    url        char(13) unique,
    postDate   timestamp default current_timestamp,
    lastEdited timestamp          null,

    constraint clucksPK primary key (id)
);

create table replies
(
    id           int auto_increment not null,
    thisCluckID  int                not null,
    replyCluckID int,

    constraint repliesPK primary key (id)
);

alter table userProfiles
    add constraint userProfileUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table clucks
    add constraint clucksUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table replies
    add constraint repliesSelfCluckFK
        foreign key (thisCluckID)
            references clucks (id)
            on delete cascade;

alter table replies
    add constraint repliesReplyCluckFK
        foreign key (replyCluckID)
            references clucks (id)
            on delete set null;