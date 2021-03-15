drop database if exists databasesProject;
create database if not exists databasesProject;
use databasesProject;

create table users
(
    id           int          not null auto_increment,
    email        varchar(255) not null unique,
    passwordHash varchar(255) not null,

    -- require email format to be something@something.something
    -- its not the best check but its better than nothing
    constraint usersEmail check ( email regexp '^[^@]+@[^.]+\..+' and length(email) >= 5),
    constraint passwordHash check ( length(passwordHash) > 0 ),
    constraint usersPK primary key (id)
);

create table userProfiles
(
    userID         int          not null,
    firstName      varchar(128) not null,
    lastName       varchar(128) not null,
    about          varchar(380) not null default '',
    profilePicture varchar(255) not null default '/path/to/default/profile.png',
    profileCreated timestamp    not null,

    -- makes sure filepath starts from root
    constraint userProfilesProfilePicture check ( profilePicture regexp '^/.+'),
    -- weak check to at least exclude numbers from the first name
    constraint userProfilesFirstName check ( firstName regexp '^[[:alpha:]]+$'),
    -- weak check to at least exclude numbers from the last name
    constraint userProfilesLastName check ( lastName regexp '^[[:alpha:]]+$'),
    constraint userProfilesPK primary key (userID)
);

create table followers
(
    thisUserID     int not null,
    followedUserID int not null,

    constraint followSelf check ( thisUserID != followedUserID ),
    constraint followersPK primary key (thisUserID, followedUserID)
);

create table posts
(
    id       int          not null auto_increment,
    userID   int          not null,
    content  varchar(320) not null,
    postDate timestamp    not null,

    constraint postsContent check ( length(content) > 0),
    constraint postsPK primary key (id)
);

create table postLikes
(
    userID int not null,
    postID int not null,

    constraint postLikesPK primary key (userID, postID)
);

create table postImages
(
    id       int          not null auto_increment,
    postID   int          not null,
    filePath varchar(255) not null,

    constraint postImagesFilePath check ( filePath regexp '^/.+' and length(filePath) > 1),
    constraint postImagesPK primary key (id)
);

create table comments
(
    id       int          not null auto_increment,
    userID   int          not null,
    postID   int          not null,
    content  varchar(320) not null,
    postDate timestamp    not null,

    constraint commentsContent check ( length(content) > 0 ),
    constraint commentsPK primary key (id)
);

create table commentLikes
(
    userID    int not null,
    commentID int not null,

    constraint commentLikesPK primary key (userID, commentID)
);

alter table userProfiles
    add constraint userProfilesUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table followers
    add constraint followersThisUserFK
        foreign key (thisUserID)
            references users (id)
            on delete cascade;

alter table followers
    add constraint followersFollowedUserFK
        foreign key (followedUserID)
            references users (id)
            on delete cascade;

alter table posts
    add constraint postsUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table postLikes
    add constraint postLikesUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table postLikes
    add constraint postLikesPostFK
        foreign key (postID)
            references posts (id)
            on delete cascade;

alter table postImages
    add constraint postImagesPostFK
        foreign key (postID)
            references posts (id)
            on delete cascade;

alter table comments
    add constraint commentsUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table comments
    add constraint commentsPostFK
        foreign key (postID)
            references posts (id)
            on delete cascade;

alter table commentLikes
    add constraint commentLikesUserFK
        foreign key (userID)
            references users (id)
            on delete cascade;

alter table commentLikes
    add constraint commentLikesCommentID
        foreign key (commentID)
            references comments (id)
            on delete cascade;
