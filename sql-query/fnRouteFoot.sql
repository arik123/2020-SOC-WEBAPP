DROP FUNCTION if exists routeBetweenPointsFoot;

--v2.6
CREATE FUNCTION routeBetweenPointsFoot(
    start_pt geometry(POINT),
    end_pt geometry(POINT)
)
RETURNS geometry(MULTILINESTRING) AS $$
DECLARE 
--    start_pt geometry(POINT);
--    end_pt geometry(POINT);
    source_id integer;
    source_way geometry(LINESTRING);
    target_id integer;
    target_way geometry(LINESTRING);
    next geometry(POINT);
BEGIN
--    SELECT point INTO start_pt
--    FROM points order by id desc limit 1;

--    SELECT point INTO end_pt
--    FROM points order by id asc limit 1;

    select id, way into source_id, source_way
    from routing_foot
    order by st_distance( way, start_pt) limit 1;

    select id, way into target_id, target_way
    from routing_foot
    order by st_distance( way, end_pt) limit 1;

    RAISE NOTICE 'PROCESSING:% %', target_id, target_way;

    CREATE TEMP TABLE route ON COMMIT DROP AS (
        SELECT di.seq, routing_foot.way, routing_foot_vertices_pgr.the_geom as node FROM pgr_trsp(
                'SELECT id::integer, source::integer, target::integer, cost, reverse_cost, way FROM routing_foot',
                source_id,
                ST_LineLocatePoint(source_way, start_pt),
                target_id,
                ST_LineLocatePoint(target_way, end_pt),
                true, true
                ) as di 
        LEFT JOIN routing_foot 
        ON di.id2 = routing_foot.id
        LEFT JOIN routing_foot_vertices_pgr
        ON di.id1 = routing_foot_vertices_pgr.id
    );
    --return query select * from route;

    SELECT route.node into next
    from route where route.seq=1;

    UPDATE route
    SET way=ST_LineSubstring(way, 
        (
            select * from ( 
            VALUES 
            (ST_LineLocatePoint(way, next)),
            (ST_LineLocatePoint(way, start_pt))
            ) as cul
            order by cul asc limit 1
        ),
        (
            select * from ( 
            VALUES 
            (ST_LineLocatePoint(way, next)),
            (ST_LineLocatePoint(way, start_pt))
            ) as cul
            order by cul desc limit 1
        )
    )
    WHERE 
    seq=0;

    SELECT route.node into next--next is actually prev
    from route where seq=(select count(*) from route)-3;

    UPDATE route
    SET way=ST_LineSubstring(way, 
        (
            select * from ( 
            VALUES 
            (ST_LineLocatePoint(way, end_pt)),
            (ST_LineLocatePoint(way, next))
            ) as cul
            order by cul asc limit 1
        ),
        (
            select * from ( 
            VALUES 
            (ST_LineLocatePoint(way, end_pt)),
            (ST_LineLocatePoint(way, next))
            ) as cul
            order by cul desc limit 1
        )
    )
    WHERE 
    seq=(select count(*) from route)-2;
    return (SELECT ST_Multi( ST_LineMerge(ST_union(way))) as way from route);
END;
$$ LANGUAGE plpgsql;