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

-- test

-- the cluck that it responds to
select *
from clucks
where id = (
    select replies.id
    from clucks join replies on clucks.id = replies.thisCluckID
    where clucks.id = 2
);
