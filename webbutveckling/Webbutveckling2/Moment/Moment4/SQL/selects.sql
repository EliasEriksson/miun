-- cluck with amount of replies
select id, userID, content, postDate, lastEdited, countedReplies.cluckReplies
from clucks
         join (select replyCluckID, count(*) as cluckReplies from replies group by replyCluckID) countedReplies
              on clucks.id = countedReplies.replyCluckID;

-- cluck heat OBS 0 results if thre is no replies
select id,
       userID,
       content,
       postDate,
       countedReplies.cluckReplies,
       (countedReplies.cluckReplies / ((now() - postDate) / 1000000)) as heat
from clucks join (
    select replyCluckID, count(*) as cluckReplies from replies group by replyCluckID
) countedReplies on clucks.id = countedReplies.replyCluckID
order by heat desc
limit 10 offset 0;


-- the cluck that it responds to
select *
from clucks
where id = (
    select replies.id
    from clucks join replies on clucks.id = replies.thisCluckID
    where clucks.id = 2
);

select * from replies;

select * from users order by users.id desc limit 10;

select count(*) as postCount from users join clucks on users.id = clucks.userID where users.id = 1;

-- counts how many times a user have been replied to
select count(*) as replyCount
from (select clucks.id from users join clucks on users.id = clucks.userID where users.id = 1) joined
    join replies on joined.id = replies.replyCluckID;

select id, email, url, firstName, lastName, avatar, description
from users join userProfiles on users.id = userProfiles.userID
order by id desc limit 10 offset 0;