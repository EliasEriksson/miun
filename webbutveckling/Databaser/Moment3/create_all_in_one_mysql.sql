
drop table OFFER cascade;
drop table BILL cascade;
drop table BOOTH cascade;
drop table RESTAURANT cascade;
drop table DESSERT cascade;


create table BOOTH  (
   BOOTHCODE               VARCHAR(10)                    not null,
   RESTAURANT_ID        VARCHAR(30)                    not null,
   SMOKE_SECTION_INDICATOR CHAR(1),
   BOOTH_CAPACITY        TINYINT,
   BOOTHTYP_CODE           CHAR(1),
   BOOTHTYP_TEXT          VARCHAR(100)
)ENGINE=InnoDB;

create table BILL (
   BILL_ID             VARCHAR(10)                    not null,
   BOOTHCODE               VARCHAR(10)                    not null,
   DESSERT_CODE          VARCHAR(10)                    not null,
   DESSERT_OFFERED_DATE_RANGE VARCHAR(40)                    not null,
   RESTAURANT_ID        VARCHAR(30)                    not null,
   BILL_DATE_AND_TIME  DATETIME,
   BILL_AMOUNT         FLOAT,
   GRATUITY_AMOUNT     FLOAT
)ENGINE=InnoDB;

create table DESSERT  (
   DESSERT_CODE          VARCHAR(10)                    not null,
   DESSERT_OFFERED_DATE_RANGE VARCHAR(40)                    not null,
   DESSERT_NAME         VARCHAR(25),
   DESSERT_DESCR  VARCHAR(150),
   DESSERT_CATEGORY_CODE VARCHAR(50),
   DRINK_NAME           VARCHAR(25),
   TOPPNING_NAME        VARCHAR(20),
   DESSERT_PRICE_AMOUNT   FLOAT
)ENGINE=InnoDB;


create table OFFER  (
   DESSERT_CODE          VARCHAR(10)                    not null,
   DESSERT_OFFERED_DATE_RANGE VARCHAR(40)                    not null,
   RESTAURANT_ID        VARCHAR(30)                    not null
);


create table RESTAURANT  (
   RESTAURANT_ID        VARCHAR(30)                    not null,
   RESTAURANT_OWNER1_NAME VARCHAR(30),
   RESTAURANT_OWNER2_NAME VARCHAR(30),
   RESTAURANT_OWNER3_NAME VARCHAR(30),
   RESTAURANT_REGION_CODE VARCHAR(10),
   RESTAURANT_REGION_NAME VARCHAR(30),
   RESTAURANT_MAX_CAPACITY SMALLINT
)ENGINE=InnoDB;

/* Creating Primary key constraints */

alter table BOOTH
   add constraint PK_BOOTH primary key (BOOTHCODE);

alter table BILL
   add constraint PK_BILL primary key (BILL_ID);


alter table DESSERT
   add constraint PK_DESSERT primary key (DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE);

alter table OFFER
   add constraint PK_OFFER primary key (DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE, RESTAURANT_ID);

alter table RESTAURANT
   add constraint PK_RESTAURANT primary key (RESTAURANT_ID);
 
 
   
/* Creating Foreign key constraints */

alter table BILL
   add constraint FK_BILL_CONTAIN_DESSERT foreign key (DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE)
      references DESSERT (DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE);

alter table BILL
   add constraint FK_CHECK_GENERATES_BOOTH foreign key (BOOTHCODE)
      references BOOTH (BOOTHCODE);

alter table BILL
   add constraint FK_CHECK_PROCESS_RESTAURA foreign key (RESTAURANT_ID)
      references RESTAURANT (RESTAURANT_ID);

alter table OFFER
   add constraint FK_OFFER_DESSERT foreign key (DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE)
      references DESSERT (DESSERT_CODE, DESSERT_OFFERED_DATE_RANGE);

alter table OFFER
   add constraint FK_OFFER_RESTAURA foreign key (RESTAURANT_ID)
      references RESTAURANT (RESTAURANT_ID);



