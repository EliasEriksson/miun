insert into users
values (default, 'john@doe.com', '0123456789');

insert into users
values (default, 'jane@doe.com', '1234567890');

insert into users
values (default, 'doe@john.com', '2345678901');

insert into users
values (default, 'doe@jane.com', '3456789012');
--
insert into userProfiles
values (1, 'john', 'doe', null, null);

insert into userProfiles
values (2, 'jane', 'doe', null, null);

insert into userProfiles
values (3, 'john', 'doe', null, null);

insert into userProfiles
values (4, 'jane', 'doe', null, null);
--
insert into clucks
values (default, 1, 'this is a post', now(), null);

insert into clucks
values (default, 2, 'i disagree', now(), null);

insert into clucks
values (default, 3, 'i agree with OP', now(), null);

insert into clucks
values (default, 4, 'you are dumb', now(), null);

insert into replies
values (default, 2, 1);

insert into replies
values (default, 3, 2);

insert into replies
values (default, 4, 1);