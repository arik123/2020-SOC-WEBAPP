DROP TABLE IF EXISTS points;

CREATE TABLE points
(
	id serial,
	point GEOMETRY(POINT),
	PRIMARY KEY( id )
);