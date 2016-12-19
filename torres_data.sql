
\c torres

COPY products (id, productname, description, status, priceeach, instock) FROM stdin;
1	Rae	Pineda	t	1	100
2	Caryl	Balbas	t	21	100
3	Sofie	Panga	t	3	100
4	Chryssy	Neith	t	10	100
5	Yza	Elia	f	1000	100
6	Loly	Loly	t	20	100
7	Catch	Ila	f	200	100
8	Zaps	Zap	f	300	100
9	Lara	Luh	f	300	100
10	Avie	Tah	f	1500	100
11	Eunice	De	f	1750	100
12	Clacla	Vil	f	2000	100
\.

SELECT setval('products_id_seq', (SELECT MAX(id) FROM products)+1);

COPY users (id, username, password, isadmin) FROM stdin;
1	admin	$2y$10$0iwvbFGPzpbaVi0qBDPtLO.bh1V6pEsA0bdt7VormsF4Yo/myC1b2	t
2	user	$2y$10$neLmT1fEqtYAbvyCGjbd4emMAXjHvxBnbhwNthDGmRRhEWmXL0MOK	f
\.

SELECT setval('users_id_seq', (SELECT MAX(id) FROM users)+1);
