insert into users values (1, 'john@doe.org', 'pretendThisIsHash');
insert into users values (default, 'john@doe.com', 'pretendThisIsHash');
insert into users values (default, '', 'pretendThisIsHash');
insert into users values (default, 'john@doe.com', '');
insert into users values (default, 'john doe', 'pretendThisIsHash');
insert into userProfiles values (3, 'john', 'doe', '', default, now());
insert into userProfiles values (5, 'john', 'doe', '', default, now());
insert into userProfiles values (default, '', 'doe', '', default, now());
insert into userProfiles values (default, 'john', '', '', default, now());
insert into userProfiles values (default, 'john', 'doe', '', 'path/to/file.png', now());
insert into followers values (1, 4);
insert into followers values (1, 5);
insert into followers values (5, 4);
insert into followers values (1, 1);
insert into posts values (1, 1, 'content', now());
insert into posts values (default, 5, 'content', now());
insert into posts values (default, 1, '', now());
insert into postLikes values (1, 2);
insert into postLikes values (5, 2);
insert into postLikes values (1, 5);
insert into postImages values (1, 2, '/file');
insert into postImages values (1, 6, '/file');
insert into postImages values (1, 2, 'file');
insert into comments values (1, 1, 4, 'comment', now());
insert into comments values (default, 6, 4, 'comment', now());
insert into comments values (default, 1, 5, 'comment', now());
insert into comments values (default, 1, 4, '', now());
insert into commentLikes values (1, 4);
insert into commentLikes values (5, 1);
insert into commentLikes values (1, 6);