insert into users
values (default, 'john@doe.com', 'pretendThisIsHash');
insert into users
values (default, 'jane@doe.com', 'pretendThisIsHash');
insert into users
values (default, 'tom@scot.com', 'pretendThisIsHash');
insert into users
values (default, 'sara@scot.com', 'pretendThisIsHash');

insert into userProfiles
values (1, 'John', 'Doe', 'This is John Doe', '/path/to/profile/picture.png', now());
insert into userProfiles
values (2, 'Jane', 'Doe', 'This is Jane Doe', '/path/to/profile/picture.png', now());
insert into userProfiles
values (3, 'Tom', 'scot', 'This is Tom Scot', 'path/to/profile/picture.png', now());
insert into userProfiles
values (4, 'Sara', 'scot', 'This is Sara Scot', 'path/to/profile/picture.png', now());

insert into followers
values (1, 4);
insert into followers
values (1, 3);
insert into followers
values (2, 1);
insert into followers
values (2, 4);
insert into followers
values (3, 2);
insert into followers
values (3, 1);
insert into followers
values (4, 3);
insert into followers
values (4, 2);

insert into posts
values (default, 1, 'I am John Doe', now());
insert into posts
values (default, 2, 'I am Jane Doe', now());
insert into posts
values (default, 3, 'I am Tom Scot', now());
insert into posts
values (default, 4, 'I am Sara Scot', now());

insert into postImages
values (default, 1, '/path/to/post/image.png');
insert into postImages
values (default, 3, '/path/to/post/image.png');

insert into postLikes
values (1, 2);
insert into postLikes
values (1, 4);
insert into postLikes
values (2, 1);
insert into postLikes
values (2, 3);
insert into postLikes
values (3, 2);
insert into postLikes
values (3, 4);
insert into postLikes
values (4, 3);
insert into postLikes
values (4, 1);

insert into comments
values (default, 1, 2, 'Yes you are Jane Doe', now());
insert into comments
values (default, 1, 2, 'You really are Jane Doe', now());
insert into comments
values (default, 2, 3, 'Yes you are Tom Scot', now());
insert into comments
values (default, 3, 4, 'Yes you are Sara Scot', now());
insert into comments
values (default, 4, 1, 'Yes you are John Doe', now());

insert into commentLikes
values (1, 4);
insert into commentLikes
values (2, 1);
insert into commentLikes
values (3, 2);
insert into commentLikes
values (4, 3);