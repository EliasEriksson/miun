use veterinary;

insert into addresses values (default, 'uppsala län', 75220, 'uppsala', 65, 'drottninggatan');
insert into addresses values (default, 'uppsala län', 75224, 'uppsala', 2, 'trädgårdsgatan');
insert into addresses values (default, 'uppsala län', 75228, 'uppsala', 8, 'lockgatan');
insert into addresses values (default, 'uppsala län', 75232, 'uppsala', 6, 'kungsgatan');
insert into addresses values (default, 'stockholms län', 75123, 'stockholm', 8, 'kungsgatan');
insert into addresses values (default, 'uppsala län', 75224, 'uppsala', 1, 'trädgårdsgatan');
insert into addresses values (default, 'uppsala län', 75224, 'uppsala', 3, 'trädgårdsgatan');

insert into employees values (default, 6, 'oscar', 'eriksson', '4675345435', str_to_date('03-05-1993', '%d-%m-%Y'),
                              'Higher Education Diploma with specialization in animal medicine');
insert into employees values (default, 7, 'maria', 'andersson', '4612357666', str_to_date('03-06-1993', '%d-%m-%Y'),
                              'Bachelor in animal medicine & master in veterinary');

insert into customerTypes values (default, 'privat');
insert into customerTypes values (default, 'företag');

insert into customers values (default, 1, 1, 'mattias dahlgren', '46701234567', null);
insert into customers values (default, 5, 2, 'arken zoo', '4612345678', '4687654321');
insert into customers values (default, 3, 1, 'elias eriksson', '46702879446', null);
insert into customers values (default, 2, 1, 'carl XVI gustaf', '4623456789', '4698765432');
insert into customers values (default, 4, 2, 'arken zoo', '46098754845', null);

insert into petTypes values (default, 'dog');
insert into petTypes values (default, 'cat');
insert into petTypes values (default, 'get');
insert into petTypes values (default, 'ödla');
insert into petTypes values (default, 'fågel');

insert into breeds values (default, 1, 'finsk lapphund');
insert into breeds values (default, 1, 'chihuahua');
insert into breeds values (default, 2, 'norsk skogskatt');
insert into breeds values (default, 2, 'maine coon');
insert into breeds values (default, 3, 'alpin');
insert into breeds values (default, 3, 'bergsget');
insert into breeds values (default, 4, 'kameleont');
insert into breeds values (default, 4, 'skäggig drake');
insert into breeds values (default, 5, 'undulat');
insert into breeds values (default, 5, 'papegoja');

insert into pets values (default, 3, 2, 'diva', 'F', str_to_date('01-01-2015', '%d-%m-%Y'));
insert into pets values (default, 2, 9, 'Spirit', 'F', str_to_date('02-03-2018','%d-%m-%Y'));
insert into pets values (default, 2, 9, 'Isabelle', 'F', str_to_date('04-05-2018','%d-%m-%Y'));
insert into pets values (default, 2, 9, 'Ramsey', 'M', str_to_date('02-04-2018','%d-%m-%Y'));
insert into pets values (default, 2, 9, 'Marshmallow', 'M', str_to_date('18-04-2018','%d-%m-%Y'));
insert into pets values (default, 1, 3, 'Ursula', 'F', str_to_date('20-08-2019', '%d-%m-%Y'));
insert into pets values (default, 4, 4, 'Argus', 'M', str_to_date('30-10-2016', '%d-%m-%Y'));
insert into pets values (default, 5, 10, 'Sugar', 'M', str_to_date('17-2-2017', '%d-%m-%Y'));
insert into pets values (default, 5, 10, 'Gusto', 'M', str_to_date('16-02-2017', '%d-%m-%Y'));
insert into pets values (default, 5, 10, 'Simba', 'M', str_to_date('15-02-2017', '%d-%m-%Y'));

insert into treatments values (default, 'kastrering', 3000);
insert into treatments values (default, 'allmän undersökning', 1500);
insert into treatments values (default, 'zink oxid - 4mg', 800);
insert into treatments values (default, 'lus spray', 600);
insert into treatments values (default, 'ving klippning', 2800);

insert into visits values (default, 1, 1, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (default, 2, 1, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (default, 3, 1, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (default, 4, 1, str_to_date('25-04-2018', '%d-%m-%Y'));
insert into visits values (default, 7, 1, str_to_date('20-02-2017', '%d-%m-%Y'));
insert into visits values (default, 8, 1, str_to_date('20-02-2017', '%d-%m-%Y'));
insert into visits values (default, 9, 1, str_to_date('20-02-2017', '%d-%m-%Y'));
insert into visits values (default, 1, 2, str_to_date('03-05-2018', '%d-%m-%Y'));
insert into visits values (default, 5, 2, str_to_date('21-09-2020', '%d-%m-%Y'));
insert into visits values (default, 3, 2, str_to_date('03-04-2017', '%d-%m-%Y'));

insert into preformedTreatments values (default, 1, 5);
insert into preformedTreatments values (default, 2, 5);
insert into preformedTreatments values (default, 3, 5);
insert into preformedTreatments values (default, 4, 5);
insert into preformedTreatments values (default, 5, 5);
insert into preformedTreatments values (default, 6, 5);
insert into preformedTreatments values (default, 7, 5);
insert into preformedTreatments values (default, 8, 2);
insert into preformedTreatments values (default, 8, 4);
insert into preformedTreatments values (default, 8, 3);
insert into preformedTreatments values (default, 9, 1);
insert into preformedTreatments values (default, 10, 1);