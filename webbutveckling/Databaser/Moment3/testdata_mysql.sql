

/*Inserts into table RESTAURANT */
insert into RESTAURANT  values ('123', 'Bob Jones', 'Paul Smith', 'Mary Doe', 'NE', 'Northeast', 50);
insert into RESTAURANT  values ('124', 'Bill Barker', 'James Pierce', 'Bob Jones', 'NE', 'Northeast', 200);
insert into RESTAURANT  values ('125', 'Mary Doe', NULL, NULL, 'SW', 'Southwest',175);
insert into RESTAURANT  values ('126', 'Mary Doe', 'Mike Myers', NULL, 'SW','Southwest',100);
insert into RESTAURANT  values ('454', 'Mary Doe', 'Mike Myers', NULL, 'NE','Northeast',145);
insert into RESTAURANT  values ('326', 'Mary Doe', 'Mike Myers', NULL, 'SW','Southwest',322);
insert into RESTAURANT  values ('789', 'Mary Doe', 'Mike Myers', NULL, 'NE','Northeast',678);



/*Inserts into table Dessert */
insert into DESSERT  values ('AB', '1999-10-1 - 1999-10-31', 'Scary Sundae', 'Lots of licorice, bats, and hot fudge over broomshaped cookies', 'Holiday, Cookie, Medium', 'Spooky Hot Chocolate', 'Hot fudge',2.95);
insert into DESSERT  values ('BC','1996-1-1 - 2002-12-31', 'Banana Split', 'Three large scoops of ice cream with two fresh bananas and an unbelievable number of toppings, including hot fudge and strawberry', 'Fruit, Frozen, Large', NULL, 'Hot fudge', 4.95);
insert into DESSERT  values ('EE','2001-6-1 - 2001-9-1', 'Summer Refresher', 'Lots of different types of fruits, all put into a blender', 'Fruit, Health, Seasonal, Small',NULL, 'Cherries', 1.95);

/*Inserts into table Booth */
insert into BOOTH values ('BOOTH_AB', '123','Y', 4, 'T', 'Table');
insert into BOOTH  values ('BOOTH_BC', '123', 'Y', 6, 'E', 'Bench');
insert into BOOTH  values ('BOOTH_AD', '125', 'N', 2, 'T', 'Table');
insert into BOOTH values ('BOOTH_AE', '124','Y', 8, 'T', 'Table');


/*Inserts into table OFFER */

insert into OFFER  values ('AB', '1999-10-1 - 1999-10-31','454');

insert into OFFER values ('BC','1996-1-1 - 2002-12-31','789');

insert into OFFER values ('EE','2001-6-1 - 2001-9-1','789');



/*Inserts into table BILL */

insert into BILL (BILL_ID, BOOTHCODE, DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE, RESTAURANT_ID, BILL_DATE_AND_TIME, BILL_AMOUNT, GRATUITY_AMOUNT) values 
('20', 'BOOTH_AB', 'AB', '1999-10-1 - 1999-10-31', '454','1999-10-12 20:34:59', 320, 20);

insert into BILL values ('29', 'BOOTH_AB', 'AB', '1999-10-1 - 1999-10-31', '454','1999-10-12 12:34:00', 330, 30);

insert into BILL values ('27', 'BOOTH_AE', 'EE', '2001-6-1 - 2001-9-1', '789','2000-10-21 15:30:12', 400.50, 0.50);

insert into BILL values ('77', 'BOOTH_AD', 'BC', '1996-1-1 - 2002-12-31', '789','2000-10-15 20:15:19', 1500.99, 80);

insert into BILL values ('9', 'BOOTH_BC', 'AB', '1999-10-1 - 1999-10-31', '454','1999-10-10 22:25:09', 310.50, 10.50);


select *from Dessert;
select * FROM Bill into outfile 'c:\\temp\\Bill.txt';
select *from Offer;
select *from Booth;
select *from RESTAURANT;

