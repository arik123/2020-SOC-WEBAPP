CREATE TABLE IF NOT EXISTS public.speed (
	highway text,
	priority double precision,
	speed double precision
);

DELETE FROM public.speed;

INSERT INTO public.speed(
	highway, priority, speed)
	VALUES 
	('motorway', 1, 130),
	('motorway_link', 1, 130),
	('motorway_junction', 1, 130),
	('motorway_link', 1, 130),
	('trunk', 1.05, 110),
	('trunk_link', 1.05, 110),
	('primary', 1.15, 90),
	('primary_link', 1.15, 90),
	('secondary', 1.5, 90),
	('secondary_link', 1.5, 90),
	('tertiary', 1.75, 90),
	('tertiary_link', 1.75, 90),
	('residential', 2.5, 50),
	('living_street', 3, 20),
	('service', 2.5, 50),
	('unclasified', 3, 90),
	('road', 5, 50);