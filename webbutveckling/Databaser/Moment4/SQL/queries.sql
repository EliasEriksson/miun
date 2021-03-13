-- horizontal search: combines all the data in users and userProfile without the FK or passwordHash
select id, email, firstName, lastName, about, profilePicture, profileCreated
from users join userProfiles on users.id = userProfiles.userID
where id = 1;

-- vertical search: combines the user and userProfile table and selects the:
-- email, firstName, lastName, about and profile picture path
select email, firstName, lastName, about, profilePicture
from users join userProfiles on users.id = userProfiles.userID;

-- joined: combines the user profile with the posts.
select firstName, lastName, content
from userProfiles join posts on userProfiles.userID = posts.userID;

-- select with >: selects all users newer than user 2
select * from userProfiles where profileCreated > (select profileCreated from userProfiles where userID = 2);

-- select with LIKE: any first name with 4 characters from users combined with userProfiles
select * from users join userProfiles on users.id = userProfiles.userID where firstName like '____';

-- select with IN: selects userProfiles with names Jane or John
select * from userProfiles where firstName in ('Jane', 'John');

-- select with exists: selects posts with images
select * from posts where exists (select * from postImages where posts.id = postImages.postID);

-- group by: how many unique users have commented on post with id 2
select count(*) as uniqueCommentingUser
from (select count(*) as count from comments where id = 2 group by userID) as counted;

-- having: select all users with minimum one comment and combine the rows where this is true
select * from userProfiles join (select * from comments group by userID having count(*) > 1) minOneCommentUsers on minOneCommentUsers.userID = userProfiles.userID;

-- select with order by: selects the latest post
select * from posts
order by postDate desc limit 1;

-- count: counts the users with profiles
select count(*) as usersWithProfile from userProfiles;

-- delete from: removes a user and all data related to that user (cascading effect with help from all FKs)
delete from users where id = 1;

-- update the name of Jane to Jinny
update userProfiles set firstName = 'Jinny' where userID = 2;