use veterinary;

insert into addresses values (0, 'uppsala län', 75220, 'uppsala', 65, 'drottninggatan');
insert into addresses values (1, 'uppsala län', 75224, 'uppsala', 2, 'trädgårdsgatan');
insert into addresses values (2, 'uppsala län', 75228, 'uppsala', 8, 'lockgatan');
insert into addresses values (3, 'uppsala län', 75232, 'uppsala', 6, 'kungsgatan');
insert into addresses values (4, 'stockholms län', 75123, 'stockholm', 8, 'kungsgatan');
insert into addresses values (5, 'uppsala län', 75224, 'uppsala', 1, 'trädgårdsgatan');
insert into addresses values (6, 'uppsala län', 75224, 'uppsala', 3, 'trädgårdsgatan');

insert into employees values (0, 5, 'oscar', 'eriksson', '4675345435', str_to_date('03-05-1993', '%d-%m-%Y'));
insert into employees values (1, 6, 'maria', 'andersson', '4612357666', str_to_date('03-06-1993', '%d-%m-%Y'));

insert into customerTypes values (0, 'privat');
insert into customerTypes values (1, 'företag');

insert into customers values (0, 0, 0, 'mattias dahlgren', '46701234567', null);
insert into customers values (1, 4, 1, 'arken zoo', '4612345678', '4687654321');
insert into customers values (2, 2, 0, 'elias eriksson', '46702879446', null);
insert into customers values (3, 1, 0, 'carl XVI gustaf', '4623456789', '4698765432');
insert into customers values (4, 3, 1, 'arken zoo', '46098754845', null);

insert into petTypes values (0, 'dog');
insert into petTypes values (1, 'cat');
insert into petTypes values (2, 'get');
insert into petTypes values (3, 'ödla');
insert into petTypes values (4, 'fågel');

insert into breeds values (0, 0, 'finsk lapphund');
insert into breeds values (1, 0, 'chihuahua');
insert into breeds values (2, 1, 'norsk skogskatt');
insert into breeds values (3, 1, 'maine coon');
insert into breeds values (4, 2, 'alpin');
insert into breeds values (5, 2, 'bergsget');
insert into breeds values (6, 3, 'kameleont');
insert into breeds values (7, 3, 'skäggig drake');
insert into breeds values (8, 4, 'undulat');
insert into breeds values (9, 4, 'papegoja');

insert into pets values (0, 2, 1, 'diva', 'F', str_to_date('01-01-2015', '%d-%m-%Y'));
insert into pets values (1, 1, 8, 'Spirit', 'F', str_to_date('02-03-2018','%d-%m-%Y'));
insert into pets values (2, 1, 8, 'Isabelle', 'F', str_to_date('04-05-2018','%d-%m-%Y'));
insert into pets values (3, 1, 8, 'Ramsey', 'M', str_to_date('02-04-2018','%d-%m-%Y'));
insert into pets values (4, 1, 8, 'Marshmallow', 'M', str_to_date('18-04-2018','%d-%m-%Y'));
insert into pets values (5, 0, 2, 'Ursula', 'F', str_to_date('20-08-2019', '%d-%m-%Y'));
insert into pets values (6, 3, 3, 'Argus', 'M', str_to_date('30-10-2016', '%d-%m-%Y'));
insert into pets values (7, 4, 9, 'Sugar', 'M', str_to_date('17-2-2017', '%d-%m-%Y'));
insert into pets values (8, 4, 9, 'Gusto', 'M', str_to_date('16-02-2017', '%d-%m-%Y'));
insert into pets values (9, 4, 9, 'Simba', 'M', str_to_date('15-02-2017', '%d-%m-%Y'));

insert into treatments values (0, 'kastrering', 3000);
insert into treatments values (1, 'allmän undersökning', 1500);
insert into treatments values (2, 'zink oxid - 4mg', 800);
insert into treatments values (3, 'lus spray', 600);
insert into treatments values (4, 'ving klippning', 2800);

insert into visits values (0, 1, 0, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (1, 2, 0, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (2, 3, 0, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (3, 4, 0, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (4, 7, 0, str_to_date('20-02-2017', '%d-%m-%Y'));
insert into visits values (5, 8, 0, str_to_date('20-02-2017', '%d-%m-%Y'));
insert into visits values (6, 9, 0, str_to_date('20-02-2017', '%d-%m-%Y'));
insert into visits values (7, 0, 1, str_to_date('03-05-2018', '%d-%m-%Y'));
insert into visits values (8, 5, 1, str_to_date('21-09-2020', '%d-%m-%Y'));
insert into visits values (9, 3, 1, str_to_date('03-04-2017', '%d-%m-%Y'));

insert into preformedTreatments values (0, 0, 4);
insert into preformedTreatments values (1, 1, 4);
insert into preformedTreatments values (2, 2, 4);
insert into preformedTreatments values (3, 3, 4);
insert into preformedTreatments values (4, 4, 4);
insert into preformedTreatments values (5, 5, 4);
insert into preformedTreatments values (6, 6, 4);
insert into preformedTreatments values (7, 7, 1);
insert into preformedTreatments values (8, 7, 3);
insert into preformedTreatments values (9, 8, 0);
insert into preformedTreatments values (10, 9, 0);