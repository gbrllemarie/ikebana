
\c torres

COPY products (id, productname, priceeach, instock) FROM stdin;
1	Rae	1	100
2	Caryl	21	100
3	Sofie	3	100
4	Chryssy	10	100
5	Yza	1000	100
6	Loly	20	100
7	Catch	200	100
8	Zaps	300	100
9	Lara	300	100
10	Avie	1500	100
11	Eunice	1750	100
12	Clacla	2000	100
\.

COPY users (id, username, password, isadmin) FROM stdin;
1	admin	$2y$10$0iwvbFGPzpbaVi0qBDPtLO.bh1V6pEsA0bdt7VormsF4Yo/myC1b2	t
2	user	$2y$10$neLmT1fEqtYAbvyCGjbd4emMAXjHvxBnbhwNthDGmRRhEWmXL0MOK	f
\.
