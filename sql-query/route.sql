select * from routeBetweenPointsFoot(
	(SELECT point FROM points order by id desc limit 1),
	(SELECT point FROM points order by id asc limit 1)
);

select * from routeBetweenPointsCar(
	(SELECT point FROM points order by id desc limit 1),
	(SELECT point FROM points order by id asc limit 1)
);