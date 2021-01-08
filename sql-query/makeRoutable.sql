-- CREATE CAR TABLE
DROP TABLE IF EXISTS public.routing_car CASCADE;
CREATE TABLE public.routing_car
(
    id bigserial NOT NULL,
    cost double precision,
    reverse_cost double precision DEFAULT -1,
	source bigint,
	target bigint,
	way geometry(LINESTRING),
	oneway text,
	PRIMARY KEY (id)
);

INSERT INTO public.routing_car(id, cost, reverse_cost, way, oneway)
SELECT noded.id as id,
	case 
		when osm.oneway = '-1' then
			-1
		when osm.maxspeed IS NOT NULL then
			ST_LENGTH(osm.way) / cast(osm.maxspeed as double precision)--check for other units
		else
			case 
				when osm.highway IN (SELECT speed.highway from speed) then
					ST_LENGTH(osm.way) / (
						SELECT speed FROM public.speed 
						WHERE speed.highway = osm.highway 
						ORDER BY priority LIMIT 1
					)
				else
					-1
			end
	end  as cost,

	case 
		when osm.oneway = 'yes' then
			-1
		when osm.maxspeed IS NOT NULL then
			ST_LENGTH(osm.way) / cast(osm.maxspeed as double precision)--check for other units
		else
			case 
				when osm.highway IN (SELECT speed.highway from speed) then
					ST_LENGTH(osm.way) / (
						SELECT speed FROM public.speed 
						WHERE speed.highway = osm.highway 
						ORDER BY priority LIMIT 1
					)
				else
					-1
			end
	end  as reverse_cost,

	noded.way,

	osm.oneway

from  planet_osm_line_noded as noded
JOIN planet_osm_line as osm on noded.old_id=osm.id
WHERE osm.highway is not null
AND osm.highway IN (SELECT speed.highway from speed);


-- CREATE FOOT TABLE
DROP TABLE IF EXISTS public.routing_foot CASCADE;
CREATE TABLE public.routing_foot
(
    id bigserial NOT NULL,
    cost double precision,
    reverse_cost double precision,
	source bigint,
	target bigint,
	way geometry(LINESTRING),
    PRIMARY KEY (id)
);
INSERT INTO public.routing_foot(id, cost, reverse_cost, way)
SELECT noded.id as id,
	ST_LENGTH(noded.way),
	ST_LENGTH(noded.way),
	noded.way
from  planet_osm_line_noded as noded
JOIN planet_osm_line as osm on noded.old_id=osm.id
WHERE osm.highway is not null
AND osm.highway NOT IN ('construction', 'motorway', 'cycleway');

--CREATE TOPOLOGY
DROP TABLE IF EXISTS route_foot_vertices_pgr CASCADE;
SELECT pgr_createTopology('routing_foot', 0.0000001, 'way');
select pgr_analyzegraph('routing_foot', 0.0000001, 'way');

DROP TABLE IF EXISTS route_car_vertices_pgr CASCADE;
SELECT pgr_createTopology('routing_car', 0.0000001, 'way');
select pgr_analyzegraph('routing_car', 0.0000001, 'way');
select pgr_analyzeOneWay('routing_car',
						 	ARRAY['', 'no', '-1'],
							ARRAY['', 'no', 'yes'],
							ARRAY['', 'no', 'yes'],
							ARRAY['', 'no', '-1']
						);